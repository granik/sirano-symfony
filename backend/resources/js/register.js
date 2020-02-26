$(function () {
    if ($('form[name="customer"]').length) {
        // masked input
        $('#customer_phone').mask('+9 (999) 999-99-99');

        // validate
        $('form[name="customer"]').validate({
            rules: {
                'customer[lastname]': {
                    required: true,
                    lastnameExt: true
                },
                'customer[name]': {
                    required: true,
                    nameExt: true
                },
                'customer[middlename]': {
                    required: false,
                    middlenameExt: true
                },
                'address': {
                    required: true,
                    addressExt: {
                        check: [
                            'customer[country]',
                            'customer[cityName]',
                            'customer[fullCityName]'
                        ]
                    },
                },
                'customer[specialty]': {
                    required: true,
                    specExt: true
                },
                'customer[email]': {
                    required: true,
                    email: true,
                    emailExt: true,
                    remote: checkUserEmailPath
                },
                'customer[password][second]': {
                    equalTo: '#customer_password_first'
                },
                'customer[agreeTerms]': 'required',
            },
            messages: {
                'customer[agreeTerms]': 'Чтобы продолжить регистрацию, вы должны дать согласие'
            },
            errorPlacement: function (error, element) {
                var placement = $(element).closest('.col-12').children('.error-text');
                if (placement.length) {
                    $(placement).append(error);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: registerUserPath,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.status === 'ok') {
                            $('#modal-register').modal('hide');
                            $('#modal-register-success').modal('show');
                        }
                    },
                    error: function (data, textStatus, jqXHR) {
                        console.log(data);
                    },
                });
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
        $.validator.messages.email = 'Неверный формат адреса';
        $.validator.messages.equalTo = 'Введённые значения не совпадают';

        // Capitalize first letter
        function ucwords(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        $('#customer_lastname,\
           #customer_name,\
           #customer_middlename,\
           #customer_specialty'
        ).keyup(function (evt) {
            var cp_value = ucwords($(this).val(), true);
            $(this).val(cp_value);
        });

        // Additional speciality check
        function checkMainSpecialtySelect() {
            var $mainSelection = $('#customer_mainSpecialtyId');
            var $additionalSelection = $('#customer_additionalSpecialtyId');

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

        $('#customer_mainSpecialtyId').on('change', function () {
            checkMainSpecialtySelect();
        });

        $('#modal-register .select2').select2({
            width: 'style',
            theme: 'sirano',
            minimumResultsForSearch: Infinity
        });
    }
});