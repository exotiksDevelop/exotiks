if (typeof(dvbusiness) === 'undefined') {
    dvbusiness = {};
}

dvbusiness.wizard = new (function () {
    this.translations = {}
});

$(function () {
    var area = $('#dvbusiness-wizard');
    var _settings = {};

    function getStepArea(stepIndex) {
        return area.find('div.step[data-step-index="' + stepIndex + '"]');
    }

    var _step = 1;
    function showStep(stepIndex) {
        getStepArea(_step).fadeOut(200, function() {
            getStepArea(stepIndex).show();
        });
        _step = stepIndex;
    }

    function saveShopDataFromStepForm(stepIndex) {
        stepArea = getStepArea(stepIndex);
        stepArea.find(':input').each(function () {
            var jInput = $(this);
            var name = jInput.attr('name');
            if (typeof (name) !== 'undefined') {
                _settings[name] = jInput.is(':checkbox') ? jInput.prop('checked') : (jInput.val() ? jInput.val().trim() : '');
            }
        });
    }

    // Начинаем не с 1го шага, а с того, который пользователь еще не заполнил
    var startStep = area.data('start-step');
    showStep(startStep);

    var orderPageUrl = $('#btn-orders-page').attr('href');

    function setStepIsFinished(stepIndex)
    {
        var stepArea = getStepArea(stepIndex);
        var data = {
            step_number : stepIndex
        };

        dvbusiness.frontendHttpClient.setLastFinishedWizardStep(data, function (success) {
            licode.preloader.off(stepArea);
            if (success) {
                if (stepIndex >= 4) {
                    licode.preloader.on(stepArea);
                    window.location.href = orderPageUrl;
                } else {
                    showStep(stepIndex + 1);
                }
            }
        });
    }

    // Шаг 1
    area.find('.nav-next-1-install').click(function (e) {
        var data = {
            'client_login'   : getStepArea(1).find('input[name="client_login"]').val(),
            'client_password': getStepArea(1).find('input[name="client_password"]').val(),
            'is_apitest'     : !!getStepArea(1).find('input[name="is_apitest"]').prop('checked'),
        };

        licode.preloader.on(getStepArea(1));
        dvbusiness.frontendHttpClient.createAuthToken(data, function (success, error, parameterErrors, response) {
            licode.preloader.off(getStepArea(1));
            if (!success) {
                alert(dvbusiness.wizard.translations.wizard_step_1_client_not_found);
            } else {
                var isApiTest = data['is_apitest'];

                var newProdTokent = !isApiTest ? response['api_auth_token'] : getStepArea(1).data('prod-api-token');
                var newTestTokent = isApiTest ? response['api_auth_token'] : getStepArea(1).data('test-api-token');

                _settings['shipping_dvbusiness_cms_module_api_prod_auth_token'] = newProdTokent;
                _settings['shipping_dvbusiness_cms_module_api_test_auth_token'] = newTestTokent;

                _settings['shipping_dvbusiness_is_api_test_server'] = isApiTest;
                area.find('input[name="shipping_dvbusiness_cms_module_api_callback_secret_key"]').val(response['api_callback_secret_key']);

                getStepArea(1).data('isApiTest', isApiTest);
                getStepArea(1).data('testApiToken', newTestTokent);
                getStepArea(1).data('prodApiToken', newProdTokent);

                getStepArea(1).find('.login-form').hide();
                getStepArea(1).find('.alert-warning').hide();
                getStepArea(1).find('.alert-success').show();
                getStepArea(1).find('.nav-next-1-install').hide();
                getStepArea(1).find('.nav-next-1-reset').show();
                getStepArea(1).find('.nav-next-1').show();

                // Обновим список банковских карт пользователя
                dvbusiness.frontendHttpClient.getPaymentMethods({} ,function (success, error, parameterErrors, response) {
                    if (success) {
                        var defaultPaymentTypeElement = $('select[name="shipping_dvbusiness_default_payment_type"]:first');
                        var defaultPaymentCardElement = $('select[name="shipping_dvbusiness_default_payment_card_id"]:first');
                        defaultPaymentTypeElement.html('');
                        defaultPaymentCardElement.html('');
                        $.each(response.payment_methods, function (index, val) {
                            var newPayOption = $('<option data-is-card="'+ val.is_card +'" value="' + val.code + '" >' +  val.name + '</option>');
                            defaultPaymentTypeElement.append(newPayOption);
                        });
                        $.each(response.cards, function (index, val) {
                            var newCardOption = $('<option value="'+ val.id +'" >' +  dvbusiness.wizard.translations.dvbusiness_payment_type_card + ' '+ val.mask +'</option>');
                            defaultPaymentCardElement.append(newCardOption);
                        });
                    }
                });

                setStepIsFinished(1);
            }
        });
    });
    area.find('.nav-next-1').click(function (e) {
        _settings['shipping_dvbusiness_cms_module_api_prod_auth_token'] = getStepArea(1).data('prodApiToken');
        _settings['shipping_dvbusiness_cms_module_api_test_auth_token'] = getStepArea(1).data('testApiToken');
        _settings['shipping_dvbusiness_is_api_test_server'] = getStepArea(1).data('isApiTest');

        setStepIsFinished(1);

        getStepArea(1).find('.login-form').hide();
        getStepArea(1).find('.alert-warning').hide();
        getStepArea(1).find('.alert-success').show();
        getStepArea(1).find('.nav-next-1-install').hide();
        getStepArea(1).find('.nav-next-1-reset').show();
        getStepArea(1).find('.nav-next-1').show();
    });
    area.find('.nav-next-1-reset').click(function (e) {
        getStepArea(1).find('.login-form').show();
        getStepArea(1).find('.alert-success').hide();
        getStepArea(1).find('.alert-warning').show();
        if (
            !getStepArea(1).data('prodApiToken')
            && !getStepArea(1).data('testApiToken')
        ) {
            getStepArea(1).find('.nav-next-1').hide();
        }
        getStepArea(1).find('.nav-next-1-reset').hide();
        getStepArea(1).find('.nav-next-1-install').show();
    });

    // Шаг 2
    area.find('.nav-back-2').click(function (e) {
        showStep(1);
    });
    area.find('.nav-next-2').click(function (e) {
        var warehouseData = {};
        getStepArea(2).find(':input').each(function () {
            var jInput = $(this);
            var name = jInput.attr('name');
            if (name !== 'workdays') {
                warehouseData[name] = jInput.is(':checkbox') ? jInput.prop('checked') : jInput.val();
            } else {
                warehouseData['workdays'] = typeof(warehouseData['workdays']) === 'undefined' ? [] : warehouseData['workdays'];
                if (jInput.prop('checked')) {
                    warehouseData['workdays'].push(jInput.val());
                }
            }
        });

        if (!warehouseData['city'] || !warehouseData['address'] || !warehouseData['contact_phone']) {
            alert(dvbusiness.wizard.translations.wizard_validation_error);
        } else {
            licode.preloader.on(getStepArea(2));

            dvbusiness.frontendHttpClient.saveWarehouse(warehouseData, function (success, error, parameterErrors, response) {
                licode.preloader.off(getStepArea(2));
                if (!success || typeof (response.warehouse_id) === 'undefined' || !response.warehouse_id) {
                    alert(dvbusiness.wizard.translations.wizard_step_2_fail);
                } else {
                    getStepArea(2).find(':input[name="warehouse_id"]').val(response.warehouse_id);
                    _settings['shipping_dvbusiness_default_pickup_warehouse_id'] = response.warehouse_id;
                    setStepIsFinished(2);
                }
            });
        }
    });

    // Шаг 3
    area.find('.nav-back-3').click(function (e) {
        showStep(2);
    });
    area.find('.nav-next-3').click(function (e) {
        saveShopDataFromStepForm(3);

        if (!_settings['shipping_dvbusiness_default_vehicle_type_id'] || !_settings['shipping_dvbusiness_default_order_weight_kg']) {
            alert(dvbusiness.wizard.translations.wizard_validation_error);
        } else {
            setStepIsFinished(3);
        }
    });

    // Шаг 4
    area.find('.nav-back-4').click(function (e) {
        showStep(3);
    });
    area.find('.nav-next-4').click(function (e) {
        saveShopDataFromStepForm(4);
        licode.preloader.on(getStepArea(4));

        dvbusiness.frontendHttpClient.storeSettings(
            _settings,
            function (success) {
                licode.preloader.off(getStepArea(4));

                if (success) {
                    setStepIsFinished(4);
                } else {
                    alert(dvbusiness.wizard.translations.wizard_fail);
                }
            }
        );
    });

    // Шаг 5
    area.find('.nav-back-5').click(function (e) {
        showStep(4);
    });

    $(function () {
        // Логика поля "Фиксированная стоимость оплаты"
        var orderPaymentMarkupField   = getStepArea(3).find('input[name="shipping_dvbusiness_dostavista_payment_markup_amount"]');
        var orderPaymentDiscountField = getStepArea(3).find('input[name="shipping_dvbusiness_dostavista_payment_discount_amount"]');
        var fixOrderPaymentField      = getStepArea(3).find('input[name="shipping_dvbusiness_fix_order_payment_amount"]');

        function fixOrderPaymentProcessor() {
            orderPaymentMarkupField.prop('disabled', fixOrderPaymentField.val().trim() > 0);
            orderPaymentDiscountField.prop('disabled', fixOrderPaymentField.val().trim() > 0);
        }

        fixOrderPaymentField.change(function () {
            fixOrderPaymentProcessor();
        });

        fixOrderPaymentField.keydown(function () {
            fixOrderPaymentProcessor();
        });

        fixOrderPaymentProcessor();
    });

    $('select[name="shipping_dvbusiness_default_payment_type"]:first').on('change', defaultPaymentTypeChanged);
    defaultPaymentTypeChanged();
    function defaultPaymentTypeChanged()
    {
        if ($("select[name='shipping_dvbusiness_default_payment_type'] option:selected").data('is-card')) {
            $('select[name="shipping_dvbusiness_default_payment_card_id"]:first').parents('.form-group:first').show();
        } else {
            $('select[name="shipping_dvbusiness_default_payment_card_id"]:first').parents('.form-group:first').hide();
        }
    }
});
