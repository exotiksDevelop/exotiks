if (typeof(dvbusiness) === 'undefined') {
    dvbusiness = {};
}

dvbusiness.warehouseForm = new (function () {
    this.translations = {
        saving_error: 'Не удалось сохранить склад. Проверьте правильность заполненных полей и попробуйте повторить позже.',
    }
});

$(function() {
    var warehouseForm = $('#dvbusiness-warehouse-form');

    function saveWarehouse() {
        if (!getInputElements().length) {
            return;
        }

        licode.preloader.on(warehouseForm);

        var data = {};
        getInputElements().each(function () {
            var jInput = $(this);
            var name = jInput.attr('name');
            if (typeof (name) !== 'undefined') {
                if (name !== 'workdays') {
                    data[name] = jInput.is(':checkbox') ? jInput.prop('checked') : jInput.val();
                } else {
                    data['workdays'] = typeof(data['workdays']) === 'undefined' ? [] : data['workdays'];
                    if (jInput.prop('checked')) {
                        data['workdays'].push(jInput.val());
                    }
                }
            }
        });

        dvbusiness.frontendHttpClient.saveWarehouse(data, function (success, error, parameterErrors, response) {
            if (!success || typeof (response.warehouse_id) === 'undefined' || !response.warehouse_id) {
                licode.preloader.off(warehouseForm);
                alert(dvbusiness.warehouseForm.translations.saving_error);
            } else {
                window.location.href =
                    '/admin/?route=' + encodeURIComponent(response.redirect_route)
                    + '&user_token=' + encodeURIComponent(response.user_token);
            }
        });
    };

    function getInputElements() {
        return warehouseForm.find(':input');
    }

    warehouseForm.find('.button-save-warehouse').click(function() {
        saveWarehouse();
    });
});
