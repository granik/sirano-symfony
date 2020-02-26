$( function() {
    function openQuestion( number ) {
        $( '[data-question]' ).addClass( 'd-none' );
        $( '[data-question="' + number +'"]' ).removeClass( 'd-none' );
    }

    // Check selected answer
    $( '.btn.next' ).on( 'click', function () {
        var currentQuestionBlock    = $( '[data-question="' + currentQuestion + '"]' );
        var isSelectedAnswerCorrect = currentQuestionBlock.find( '[type="radio"]:checked' ).val();
        var correctAnswerText       = $.trim( currentQuestionBlock.find( '[value="1"]' ).siblings( 'span' ).text());

        if ( isSelectedAnswerCorrect == undefined ) {
            $( '#modal-test-reminder' ).modal( 'show' );
        } else if ( isSelectedAnswerCorrect == 0 ) {
            $( '#modal-test-wrong-answer' ).find( '.right-answer' ).text( correctAnswerText );
            $( '#modal-test-wrong-answer' ).modal( 'show' );

            currentQuestion++;
            openQuestion( currentQuestion );
        } else {
            currentQuestion++;
            openQuestion( currentQuestion );
        }

        markAnswersInBar();
        finishTest();
        
        return false;
    });

    // Mark answers in progress bar
    function markAnswersInBar() {
        var answers = $( '[data-question]' );
        var boxes   = $( '[data-progress]' );

        boxes.removeClass( 'true false active' );

        answers.each(function () {
            var answer = $( this );
            var index  = $( this ).index();
            var isSelectedAnswerCorrect = answer.find( '[type="radio"]:checked' ).val();

            if ( index === currentQuestion ) {
                boxes.eq( index ).addClass( 'active' );
            } else if ( isSelectedAnswerCorrect == undefined ) {
                return;
            } else if ( isSelectedAnswerCorrect == 0 ) {
                boxes.eq( index ).addClass( 'false' );
            } else {
                boxes.eq( index ).addClass( 'true' );
            }
        });
    }

    // Finish test
    function finishTest() {
        if ( currentQuestion > 10 ) {
            var totalAnswers   = $( '[value="1"]' ).length;
            var correctAnswers = $( '[value="1"]:checked' ).length;

            sendResults( correctAnswers );

            $( '.page-test-item__buttons' ).addClass( 'd-none' );
            $( '.page-test-item-over' ).removeClass( 'd-none' );

            setTimeout( function() {
                if ( correctAnswers >= 8 ) {
                    $( '#modal-test-success' ).find( '.correct-answers' ).text( correctAnswers );
                    $( '#modal-test-success' ).find( '.module-id' ).text( moduleItem.id );
                    $( '#modal-test-success' ).modal( 'show' );
                } else {
                    $( '#modal-test-failure' ).find( '.correct-answers' ).text( correctAnswers );
                    $( '#modal-test-failure' ).find( '.module-id' ).text( moduleItem.id );
                    $( '#modal-test-failure' ).modal( 'show' );
                }
            }, 3000);
        }
    }

    function sendResults( correctAnswers ) {
        var form = $( '#test-form' );

        $.ajax({
            type: 'POST',
            cache: false,
            url: test.url,
            dataType: 'json',
            data: {
                'id':  test.id,
                'answers': form.serializeObject(),
                'correct': correctAnswers,
            },
            success: function (data) {
                console.log( data );
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                console.log( thrownError );
            }
        });
    }

    // Start test
    $( '[data-question]' ).find( 'input[type="radio"]' ).prop( 'checked', false );
    openQuestion( currentQuestion );
    markAnswersInBar();
});

(function ($) {
    $.fn.serializeObject = function () {
        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push": /^$/,
                "fixed": /^\d+$/,
                "named": /^[a-zA-Z0-9_]+$/
            };

        this.build = function (base, key, value) {
            base[key] = value;
            return base;
        };

        this.push_counter = function (key) {
            if (push_counters[key] === undefined) {
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function () {
            // skip invalid keys
            if (!patterns.validate.test(this.name)) {
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while ((k = keys.pop()) !== undefined) {

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // push
                if (k.match(patterns.push)) {
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if (k.match(patterns.fixed)) {
                    merge = self.build([], k, merge);
                }

                // named
                else if (k.match(patterns.named)) {
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };
})(jQuery);