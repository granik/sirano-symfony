$( '#clinical-analysis-message' ).on( 'submit', function () {
    var form     = this;
    var formData = new FormData( this );

    $.ajax({
        type: 'POST',
        url: clinicalAnalysis.message,
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

$( '#modal-clinical-feedback form' ).on( 'submit', function () {
    var form     = this;
    var formData = new FormData( this );

    $.ajax({
        type: 'POST',
        url: clinicalAnalysis.feedback,
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function( data, textStatus, jqXHR ) {
            if ( data.status === 'ok' ) {
                $( '#modal-clinical-feedback-sent' ).modal( 'show' );
                form.reset();
            }
        },
        error: function( data, textStatus, jqXHR ) {
            console.log( data );
        },
    });

    return false;
});