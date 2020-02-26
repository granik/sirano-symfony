$( function () {
    var conferenceSliderWidth = $( '#conference-slider' ).hasClass( 'with-schedule' ) ? true : false;
    var conferenceSliderCount = $( '#conference-slider' ).hasClass( 'with-schedule' ) ? 1 : 2;

    $( '#conference-slider' ).slick({
        prevArrow: $( '#conference-control-prev' ),
        nextArrow: $( '#conference-control-next' ),
        slidesToShow: 2,
        appendDots: $( '#conference-indicators' ),
        dots: true,
        variableWidth: conferenceSliderWidth,
        swipe: false,
        touchMove: false,
        customPaging : function( slider, i ) {
            return '<span></span>';
        },
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    variableWidth: false,
                    swipe: true,
                    touchMove: true,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: conferenceSliderCount,
                    variableWidth: false,
                    swipe: true,
                    touchMove: true,
                }
            },
        ]
    });

    $( '#webinar-slider' ).slick({
        prevArrow: $( '#webinar-control-prev' ),
        nextArrow: $( '#webinar-control-next' ),
        slidesToShow: 2,
        appendDots: $( '#webinar-indicators' ),
        dots: true,
        swipe: false,
        touchMove: false,
        customPaging : function( slider, i ) {
            return '<span></span>';
        },
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    swipe: true,
                    touchMove: true,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    swipe: true,
                    touchMove: true,
                }
            },
        ]
    });

    $( '#news-slider' ).slick({
        prevArrow: $( '#news-control-prev' ),
        nextArrow: $( '#news-control-next' ),
        slidesToShow: 3,
        appendDots: $( '#news-indicators' ),
        dots: true,
        swipe: false,
        touchMove: false,
        customPaging : function( slider, i ) {
            return '<span></span>';
        },
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    swipe: true,
                    touchMove: true,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    swipe: true,
                    touchMove: true,
                }
            },
        ]
    });

    $( '.slick-slider' ).on( 'setPosition', function () {
        $this = $( this );

        $this.find( '.slick-slide' ).height( 'auto' );
        var slickTrack       = $this.find( '.slick-track' );
        var slickTrackHeight = $( slickTrack ).height();

        $this.find( '.slick-slide.slick-active' ).css( 'height', slickTrackHeight + 'px' );

        if ( $this.is( '#conference-slider.with-schedule' ) ) {
            $this.find( '.conference-schedule__wrapper' ).css( 'max-height', slickTrackHeight + 'px' );
            $this.find( '.conference-schedule__list-wrapper' ).css( 'max-height', '100%' );
        }
    });

    function resizeBanners() {
        $( '#carouselDesktopAds' ).width( 'auto' );
        var bannerWidth = $( '#carouselDesktopAds' ).width();
        $( '#carouselDesktopAds' ).css( 'width', bannerWidth + 'px' );
    }

    var resTimeout;
    window.onresize = function() {
        clearTimeout( resTimeout );
        resTimeout = setTimeout( resizeBanners, 100);
    };

    resizeBanners();

    $( '#carouselDesktopAds' ).on( 'slid.bs.carousel', function () {
        resizeBanners();
    });

    var schedules = $( '.conference-schedule' );
    if ( schedules ) {
        schedules.each(function () {
            var list = $( this ).find( '.conference-schedule__list' );
            var item = $( list ).find( 'li:not(.past)' )[0];

            if ( item && list ) {
                $( list ).scrollTop( $( list ).scrollTop() + $( item ).position().top - $( list ).height() / 2 + $( item ).height() / 2 );
            }
        });
    }

    var $sceduleLists = $( '.conference-schedule__list' );
    $sceduleLists.each( function() {
        var $this = $( this );
        $this.mCustomScrollbar({
            scrollbarPosition: 'inside',
            axis: 'y',
            theme: 'sirano',
            callbacks:{
                onInit:function() {
                    var $that = $( this );
                    setTimeout( function() {
                        var pastEls = $that.find( 'li.past' );
                        if ( pastEls.length > 3 ) {
                            var targetEl = $that.find( 'li.past:nth-child(' + (pastEls.length - 2) + ')' );
                            $this.mCustomScrollbar( 'scrollTo', $(targetEl) );
                        }
                    }, 500 );
                }
            }
        });
    });
});