var updateTimerTimer;
var updateStartTimeTimer;
var updateFinishTimeTimer;
var stopWebinarTimer;
var popupTime1Timer;
var popupTime2Timer;
var popupTime3Timer;
var player;
var nowPlaying = false;
var confirmBlock = $( '#modal-webinar-check' );

var webinarOverBlock   = $( '#webinar-over' );
var webinarSoonBlock   = $( '#webinar-soon' );
var webinarPlayerBlock = $( '#webinar-player' );

// Load the IFrame Player API code asynchronously.
var tag = document.createElement( 'script' );
tag.src = 'https://www.youtube.com/player_api';
var firstScriptTag = document.getElementsByTagName( 'script' )[0];
firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );

function printTimer( days, hours, minutes ) {
    return  '<div class="d-flex flex-column p-2">\
                <span class="count">' + days + '</span>\
                <span class="sub">дней</span>\
            </div>\
            <div class="d-flex flex-column p-2">\
                <span class="count">' + hours + '</span>\
                <span class="sub">часов</span>\
            </div>\
            <div class="d-flex flex-column p-2">\
                <span class="count">' + minutes + '</span>\
                <span class="sub">минут</span>\
            </div>';
}

function showConfirm() {
    confirmBlock.modal( 'show' );
}

function setConfirm() {
    $.ajax({
        type: 'POST',
        cache: false,
        url: webinar.confirmUrl,
        dataType: 'json',
        data: { id: webinar.id },
        success: function (data) {
            if (data.status === 'ok') {
                confirmBlock.modal( 'hide' );
            }
        },
        error: function ( xhr, ajaxOptions, thrownError ) {
            confirmBlock.modal( 'show' );
        }
    });
}

$( '#modal-webinar-check' ).on( 'hidden.bs.modal', function () {
    setConfirm();
});

function startWebinar() {
    if ( nowPlaying ) {
        return;
    }

    nowPlaying = true;

    console.log( 'start video' );

    webinarOverBlock.removeClass( 'd-flex' ).addClass( 'd-none');
    webinarSoonBlock.removeClass( 'd-flex' ).addClass( 'd-none');
    webinarPlayerBlock.removeClass( 'd-none').addClass( 'd-flex' );

    player = new YT.Player( 'player', {
        width: '100%',
        videoId: webinar.youtubeCode,
        events: {
            'onReady': onPlayerReady,
        }
    });

    function onPlayerReady( event ) {
        event.target.playVideo();
    }

    var now = Date.now();
    stopWebinarTimer = setTimeout( stopWebinar, webinar.finishTime - now );

    if ( webinar.popupTime1 > now ) {
        popupTime1Timer = setTimeout( showConfirm, webinar.popupTime1 - now );
    }
    if ( webinar.popupTime2 > now ) {
        popupTime2Timer = setTimeout( showConfirm, webinar.popupTime2 - now );
    }
    if ( webinar.popupTime3 > now ) {
        popupTime3Timer = setTimeout( showConfirm, webinar.popupTime3 - now );
    }

    updateFinishTimeTimer = setInterval( updateFinishTime, UPDATE_INTERVAL );
}

var stopWebinar = function () {
    if ( !nowPlaying ) {
        return;
    }

    nowPlaying = false;

    console.log( 'stop video' );

    player.stopVideo();

    clearInterval( updateFinishTimeTimer );

    webinarOverBlock.removeClass( 'd-none' ).addClass( 'd-flex');
    webinarSoonBlock.removeClass( 'd-flex' ).addClass( 'd-none');
    webinarPlayerBlock.removeClass( 'd-flex').addClass( 'd-none' );
};

var updateTimer = function () {
    var currentTime = Date.now();

    if ( currentTime <= webinar.startTime ) {
        countdown(
            webinar.startTime,
            function(ts) {
                document.getElementById( 'webinar-countdown' ).innerHTML = printTimer( ts.days, ts.hours, ts.minutes );
            },
            countdown.DAYS|countdown.HOURS|countdown.MINUTES|countdown.SECONDS
        );
    } else {
        clearInterval( updateTimerTimer );
        startWebinar();
    }
};

var updateStartTime = function () {
    $.get(
        webinar.getTimes,
        {
            id: webinar.id
        },
        function ( data ) {
            webinar.startTime = data.start_time;
            webinar.finishTime = data.finish_time;
            webinar.popupTime1 = data.popup_start_time;
            webinar.popupTime2 = data.popup_mid_time;
            webinar.popupTime3 = data.popup_end_time;

            clearInterval( updateStartTimeTimer );
            clearInterval( updateTimerTimer );

            checkWebinarStatus( webinar.startTime, webinar.finishTime );
        }
    );
};

var updateFinishTime = function () {
    $.get(
        webinar.getTimes,
        {
            id: webinar.id
        },
        function ( data ) {
            webinar.startTime = data.start_time;
            webinar.finishTime = data.finish_time;
            webinar.popupTime1 = data.popup_start_time;
            webinar.popupTime2 = data.popup_mid_time;
            webinar.popupTime3 = data.popup_end_time;

            clearTimeout( stopWebinarTimer );
            clearTimeout( popupTime1Timer );
            clearTimeout( popupTime2Timer );
            clearTimeout( popupTime3Timer );

            var now = Date.now();
            stopWebinarTimer = setTimeout( stopWebinar, webinar.finishTime - now );

            if ( webinar.popupTime1 > now ) {
                popupTime1Timer = setTimeout( showConfirm, webinar.popupTime1 - now );
            }
            if ( webinar.popupTime2 > now ) {
                popupTime2Timer = setTimeout( showConfirm, webinar.popupTime2 - now );
            }
            if ( webinar.popupTime3 > now ) {
                popupTime3Timer = setTimeout( showConfirm, webinar.popupTime3 - now) ;
            }
        }
    );
};

if ( webinar.isStarted ) {
    function onYouTubeIframeAPIReady() {
        startWebinar();
    }
} else {
    var checkWebinarStatus = function ( startTime, finishTime ) {
        var currentTime = Date.now();

        if ( finishTime < currentTime ) {
            console.log( 'Трансляция закончилась' );

            stopWebinar();
        } else if ( startTime <= currentTime ) {
            console.log( 'Трансляция идёт' );

            startWebinar();
        } else {
            console.log( 'Трансляция скоро начнётся' );

            updateTimerTimer     = setInterval( updateTimer, 1000 );
            updateStartTimeTimer = setInterval( updateStartTime, UPDATE_INTERVAL );
        }
    };

    countdown(
        webinar.startTime,
        function(ts) {
            document.getElementById( 'webinar-countdown' ).innerHTML = printTimer( ts.days, ts.hours, ts.minutes );
        },
        countdown.DAYS|countdown.HOURS|countdown.MINUTES|countdown.SECONDS
    );

    checkWebinarStatus(webinar.startTime, webinar.finishTime);
}

$( '#webinar-message' ).on( 'submit', function () {
    var form     = this;
    var formData = new FormData( this );

    $.ajax({
        type: 'POST',
        url: webinar.message,
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function( data, textStatus, jqXHR ) {
            if ( data.status === 'ok' ) {
                $( '#modal-message-sent' ).modal( 'show' );
                form.reset();
            }
        },
        error: function( data, textStatus, jqXHR ) {
            console.log( data );
        },
    });

    return false;
});