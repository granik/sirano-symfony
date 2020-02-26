$( function () {
    $( '.mobile-menu-switcher' ).on( 'click', function () {
        $( 'body' ).toggleClass( 'menu-mobile-opened' );
        return false;
    });

    $( '.menu-element.arrow .menu-element__link' ).on( 'click', function () {
        if ( !$( this ).closest( '.menu-element' ).hasClass( 'submenu-opened' ) ) {
            $( '.menu-element.arrow .submenu' ).slideUp( 'medium' );
            $( '.menu-element.arrow' ).removeClass( 'submenu-opened' );
            $( this ).closest( '.menu-element.arrow' ).find( '.submenu' ).slideDown( 'medium' );
            $( this ).closest( '.menu-element.arrow' ).addClass( 'submenu-opened' );
        } else {
            $( this ).closest( '.menu-element.arrow' ).find( '.submenu' ).slideUp( 'medium' );
            $( this ).closest( '.menu-element.arrow' ).removeClass( 'submenu-opened' );
        }
        return false;
    });

    function siranoWelcomeAccept() {
        $.cookie( 'welcome', 'accepted', {
            expires: 365,
            path: '/',
        });
        $( '.sirano-welcome' ).fadeTo( 'medium', '0', function() {
            $( '.sirano-welcome' ).hide();
        });
        return false;
    }

    $( '.sirano-welcome__accept' ).on( 'click', siranoWelcomeAccept);

    $( document ).ready( function() {
        setTimeout( function() {
            siranoWelcomeAccept();
        }, 5000 );
    });

    $( '.additional-menu__profile > a' ).on( 'click', function() {
        $( '.additional-menu' ).toggleClass( 'menu-opened' );
        return false;
    });

    $( '.menu-directions input[type=radio]' ).on( 'change', function() {
        $( this ).closest( 'form' ).submit();
        return false;
    });

    $( '.modal' ) .on( 'show.bs.modal', function () {
        $( '.modal' ).not( $( this ) ).each(function () {
            $( this ).modal( 'hide' );
            setTimeout( function() {
                $( 'body' ).addClass( 'modal-open' );
            }, 200);
        });
    });

    $( 'a[aria-controls="conference"], a[aria-controls="webinar"]' ).on( 'shown.bs.tab', function (e) {
        var tabId = $( e.target ).attr( 'aria-controls' );

        if ( tabId ) {
            $( '#all-conference, #all-webinar' ).toggleClass( 'd-none' ).toggleClass( 'd-block' );

            $( '#conference-slider' ).slick( 'setPosition' );
            $( '#webinar-slider' ).slick( 'setPosition' );
        }
    });

    $( '.page-header-advanced__filters select,\
        .page-header-advanced__filters input,\
        .page-header-advanced__additions select'
    ).on( 'change', function() {
        $( this ).closest( 'form' ).submit();
    });

    $( '.page-header-advanced__filters-toggler,\
        .page-header-advanced__filters-mobile__fade,\
        .page-header-advanced__filters-mobile__close'
    ).on( 'click', function() {
        $( 'body' ).toggleClass( 'filters-mobile-opened' );
        $( '.page-header-advanced__filters-mobile' ).toggleClass( 'd-flex' );
        return false;
    });

    $( '.page-news-item__share-mobile a' ).on( 'click', function() {
        $( '.page-news-item__share' ).toggleClass( 'd-flex' );
        return false;
    });

    $( 'a[data-type="submit"]' ).on( 'click', function() {
        $( this ).closest( 'form' ).submit();
        return false;
    });

    $( '[data-type="webinar-subscribe-card"]' ).on( 'click', function() {
        var $this      = $( this );
        var $container = $this.closest( '.template-webinar__footer-buttons' );
        var $form      = $this.closest( 'form' );
        var $card      = $this.closest( '.template-webinar__item-card' );
        var title      = $card.find( '[data-title]' ).text();

        var id         = $form.find( '[name="id"]' ).val();
        var formData   = {
            id: id,
        }

        $.ajax({
            type: 'POST',
            url: $form.attr( 'action' ),
            data: formData,
            dataType: 'json',
            success: function( data, textStatus, jqXHR ) {
                if (data.status === 'ok') {
                    $container.html('<div class="registered">Вы зарегистрированы<br />на онлайн-трансляцию</div>');

                    $card.addClass( 'active' );

                    $( '#modal-event-register-success' ).modal( 'show' );
                    $( '#modal-event-register-success' ).find( '.event-name' ).text( title );
                }
            },
            error: function( data, textStatus, jqXHR ) {
                console.log( data );
            },
        });

        return false;
    });

    $( '[data-type="conference-subscribe-card"]' ).on( 'click', function() {
        var $this      = $( this );
        var $container = $this.closest( '.template-conference__footer-buttons' );
        var $form      = $this.closest( 'form' );
        var $card      = $this.closest( '.template-conference__item-card' );
        var title      = $card.find( '[data-title]' ).text();

        var id         = $form.find( '[name="id"]' ).val();
        var formData   = {
            id: id,
        }

        $.ajax({
            type: 'POST',
            url: $form.attr( 'action' ),
            data: formData,
            dataType: 'json',
            success: function( data, textStatus, jqXHR ) {
                if (data.status === 'ok') {
                    $container.html('<div class="registered">Вы зарегистрированы<br />на посещение</div>');

                    $card.addClass( 'active' );

                    $( '#modal-event-register-success' ).modal( 'show' );
                    $( '#modal-event-register-success' ).find( '.event-name' ).text( title );
                }
            },
            error: function( data, textStatus, jqXHR ) {
                console.log( data );
            },
        });

        return false;
    });

    $( '[data-type="webinar-subscribe"],\
        [data-type="webinar-unsubscribe"],\
        [data-type="conference-subscribe"],\
        [data-type="conference-unsubscribe"]'
    ).on( 'click', function() {
        var $this = $( this );
        var $form = $this.closest( 'form' );

        var id       = $form.find( '[name="id"]' ).val();
        var formData = {
            id: id,
        }

        $.ajax({
            type: 'POST',
            url: $form.attr( 'action' ),
            data: formData,
            dataType: 'json',
            success: function( data, textStatus, jqXHR ) {
                if (data.status === 'ok') {
                    location.reload();
                }
            },
            error: function( data, textStatus, jqXHR ) {
                console.log( data );
            },
        });

        return false;
    });

    if ( $( '.radio-dropdown' ).length ) {
        $( '.radio-dropdown' ).each( function() {
            if ( $( this ).find( 'input[type=radio]:checked' ) ) {
                var newValueText = $( this ).find( 'input[type=radio]:checked' ).siblings( 'span' ).text();
                $( this ).find( '.radio-dropdown__link' ).text( newValueText );
            }
        });
    }

    $( document ).on( 'click', '.radio-dropdown .radio-dropdown__link', function() {
        if ( $( this ).closest( '.radio-dropdown' ).hasClass( 'radio-dropdown__disabled' ) ) return false;

        if ( !$( this ).closest( '.radio-dropdown' ).hasClass( 'radio-dropdown__opened' ) ) {
            $( '.radio-dropdown__submenu' ).slideUp( 'medium' );
            $( '.radio-dropdown' ).removeClass( 'radio-dropdown__opened' );
            $( this ).closest( '.radio-dropdown' ).find( '.radio-dropdown__submenu' ).slideDown( 'medium' );
            $( this ).closest( '.radio-dropdown' ).addClass( 'radio-dropdown__opened' );
        } else {
            $( this ).closest( '.radio-dropdown' ).find( '.radio-dropdown__submenu' ).slideUp( 'medium' );
            $( this ).closest( '.radio-dropdown' ).removeClass( 'radio-dropdown__opened' );
        }
        return false;
    });

    $( document ).on( 'change', '.radio-dropdown input[type=radio]', function() {
        var newValueText = $( this ).siblings( 'span' ).text();
        $( this ).closest( '.radio-dropdown' ).find( '.radio-dropdown__link' ).text( newValueText );

        $( this ).closest( '.radio-dropdown' ).find( '.radio-dropdown__submenu' ).slideUp( 'medium' );
        $( this ).closest( '.radio-dropdown' ).removeClass( 'radio-dropdown__opened' );

        return false;
    });

    $( document ).on( 'change', '.mobile-direction-filter .radio-dropdown input[type=radio]', function() {
        var $this = $( this );
        $directionSelect = $this.closest( '.radio-dropdown' );
        $categoryTarget  = $( '.mobile-category-filter' );

        $.ajax({
            url: $directionSelect.data( 'category-url' ),
            data: {
                direction: $this.val()
            },
            success: function ( html ) {
                $categoryTarget.html( html );
                $categoryTarget.find( '.radio-dropdown' ).find( '.radio-dropdown__link' ).text( 'Все категории' );
            }
        });
    });

    $( '[data-share]' ).on( 'click', function () {
        var url          = $( this ).attr( 'data-url' );
        var widthScreen  = ( screen.width - 700 ) / 2;
        var heightScreen = ( screen.height - 400 ) / 2;
        var params = 'menubar=0, toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, width=700, height=400, left=' + widthScreen + ', top=' + heightScreen;

        if ( !url ) return false;

        window.open( url, 'new window', params );

        return false;
    });
});