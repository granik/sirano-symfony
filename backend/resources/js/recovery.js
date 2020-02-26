$( function() {
    if( $( 'form[name="recovery"]' ).length ) {
        $( 'form[name="recovery"]' ).validate({
            rules: {
                'recovery[recovery-email]': {
                    required: true,
                    email: true,
                },
            },
            messages: {},
            errorPlacement: function( error, element ) {
                var placement = $(element).closest( '.col-12' ).children( '.error-text' );
                if ( placement.length ) {
                    $( placement ).append( error );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function( form ) {
                var formData       = new FormData( form );
                var errorContainer = $( form ).find( '.error-text' );

                errorContainer.text('');

                $.ajax({
                    type: 'POST',
                    url: recoveryUserPath,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function( data, textStatus, jqXHR ) {
                        if ( data.status === 'ok' ) {
                            $( '#modal-recovery' ).modal( 'hide' );
                            $( '#modal-recovery-sent' ).modal( 'show' );
                        } else if ( data.status === 'error' ) {
                            errorContainer.text( data.errors );
                        }
                    },
                    error: function( data, textStatus, jqXHR ) {
                        console.log( data );
                    },
                });
            }
        });

        $.validator.messages.required = 'Это обязательное поле';
    }
});