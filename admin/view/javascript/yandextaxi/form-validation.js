(function (window) {
    'use strict';

    function yandexTaxiDeliveryFormValidator() {
        var _yandexTaxiDeliveryFormValidator = {};

        'use strict';

        _yandexTaxiDeliveryFormValidator.validateForm = function (form, callbackOnError) {
            let valid = true;

            form.find('input[required]').each(function () {
                if (!validateSingleField($(this))) {
                    valid = false;
                }
            });

            if (!valid) {
                scrollToError();
            }

            return valid;
        };

        _yandexTaxiDeliveryFormValidator.validateField = function (field) {
            validateSingleField(field);
        };

        function scrollToError() {
            $([document.documentElement, document.body]).animate({
                scrollTop: $('.has-error').first().offset().top
            }, 100);
        }

        function validateSingleField(field) {
            if (field.prop('required') && field.val().trim().length === 0) {
                addError(field, validationTranslations.error_required_field);
                return false;
            }
            if (field.prop('type') === 'tel' && !field.intlTelInput("isValidNumber")) {
                addError(field, validationTranslations.error_invalid_telephone);
                return false;
            }

            clearError(field);
            return true;
        }

        function addError(field, message) {
            const formGroup = field.closest('.form-group').addClass('has-error');
            formGroup.find('.help-block').text(message).show();
        }

        function clearError(field) {
            const formGroup = field.closest('.form-group').removeClass('has-error');
            formGroup.find('.help-block').hide();
        }

        return _yandexTaxiDeliveryFormValidator;
    }

    if (typeof (window.yandexTaxiDeliveryFormValidator) === 'undefined') {
        window.yandexTaxiDeliveryFormValidator = yandexTaxiDeliveryFormValidator();
    }
})(window);
