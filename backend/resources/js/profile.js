$(function () {
    // masked input
    $('#customer_update_phone').mask('+9 (999) 999-99-99');

    // validate
    $('form[name="customer_update"]').validate({
        rules: {
            'customer_update[lastname]': {
                required: true,
                lastnameExt: true
            },
            'customer_update[name]': {
                required: true,
                nameExt: true
            },
            'customer_update[middlename]': {
                required: false,
                middlenameExt: true
            },
            'customer_update[cityName]': {
                required: true,
                addressExt: {
                    check: [
                        'customer_update[country]',
                        'customer_update[fullCityName]'
                    ]
                },
            },
            'customer_update[specialty]': {
                required: true,
                specExt: true
            },
            'customer_update[email]': {
                required: true,
                email: true,
                emailExt: true
            }
        },
        errorPlacement: function (error, element) {
            var placement = $(element).closest('.col-12').children('.error-text');
            if (placement.length) {
                $(placement).append(error);
            } else {
                error.insertAfter(element);
            }
        }
    });

    $.validator.addMethod('emailExt', function (value, element, param) {
        return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    }, 'Неверный формат адреса');

    $.validator.addMethod('lastnameExt', function (value, element, param) {
        return value.match(/^([- A-Za-zа-яА-ЯёЁ]+)$/);
    }, 'Использованы недопустимые символы');

    $.validator.addMethod('nameExt', function (value, element, param) {
        return value.match(/^([A-Za-zа-яА-ЯёЁ]+)$/);
    }, 'Использованы недопустимые символы');

    $.validator.addMethod('middlenameExt', function (value, element, param) {
        if (!value) return [value, value];
        return value.match(/^([A-Za-zа-яА-ЯёЁ]+)$/);
    }, 'Использованы недопустимые символы');

    $.validator.addMethod('specExt', function (value, element, param) {
        return value.match(/^([-A-Za-zа-яА-ЯёЁ]+)$/);
    }, 'Использованы недопустимые символы');

    $.validator.addMethod('addressExt', function (value, element, param) {
        var result = true;

        param.check.forEach(function (item, index, array) {
            var str = $('input[name="' + item + '"]').val();

            if (str != null && typeof str !== 'undefined') {
                str = str.trim();
            }

            if (!str) {
                result = false;
            }
        });

        return result;
    }, 'Неизвестный населенный пункт');

    $.validator.messages.required = 'Это обязательное поле';

    // Capitalize first letter
    function ucwords(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    $('#customer_update_lastname,\
       #customer_update_name,\
       #customer_update_middlename,\
       #customer_update_specialty'
    ).keyup(function (evt) {
        var cp_value = ucwords($(this).val(), true);
        $(this).val(cp_value);
    });

    // Additional speciality check
    function checkMainSpecialtySelect() {
        var $mainSelection = $('#customer_update_mainSpecialtyId');
        var $additionalSelection = $('#customer_update_additionalSpecialtyId');

        if ($mainSelection.val() === '18') {
            $additionalSelection.parent().removeClass('d-none');
            $additionalSelection.removeAttr('disabled');
        } else {
            $additionalSelection.val('');
            $additionalSelection.parent().addClass('d-none');
            $additionalSelection.prop('disabled', true);
        }
    }

    checkMainSpecialtySelect();

    $('#customer_update_mainSpecialtyId').on('change', function () {
        checkMainSpecialtySelect();
    });

    $('.select2').select2({
        width: 'style',
        theme: 'sirano',
        minimumResultsForSearch: Infinity
    });
});