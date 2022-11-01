if (typeof(dvbusiness) === 'undefined') {
    dvbusiness = {};
}

dvbusiness.warehouses = new (function () {
    this.translations = {
        deletion_confirm: 'Если склад был выбран по умолчанию для точки забора, то после его удаления служба доставки перестанет работать, пока не будет выбран новый склад. Вы действительно хотите удалить?',
        deletion_error  : 'Не удалось удалить склад. Попробуйте повторить позже.'
    }
});

$(function() {
    var warehousesArea = $('#dvbusiness-warehouses');

    warehousesArea.on('click', '.button-warehouse-delete', function(e) {
        e.preventDefault();

        if (!window.confirm(dvbusiness.warehouses.translations.deletion_confirm)) {
            return;
        }

        var parentTr = $(this).parents('tr:first');
        var data = {
            'warehouse_id': parentTr.data('warehouseId'),
        };

        licode.preloader.on(parentTr);

        dvbusiness.frontendHttpClient.deleteWarehouse(data, function (success, error, parameterErrors, response) {
            if (!success) {
                licode.preloader.off(parentTr);
                alert(dvbusiness.warehouses.translations.deletion_error);
            } else {
                window.location.reload();
            }
        });
    });
});
