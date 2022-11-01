if (typeof(dvbusiness) === 'undefined') {
    dvbusiness = {};
}

dvbusiness.orders = new (function () {
    this.translations = {
        not_selected_error: 'Необходимо сперва отметить заказы, которые хотите отправить через Dostavista'
    }
});

$(function() {
    var orderListArea = $('#dvbusiness-orders');

    // Применения фильтра при изменении любого из элементов
    var filterForm = orderListArea.find('.filter form');
    filterForm.find(':input').change(function () {
        filterForm.submit();
    });

    // Работа с чекбоксами
    var ordersTable = orderListArea.find('table.orders');
    ordersTable.find('thead input[type="checkbox"]').change(function () {
        var isAllChecked = $(this).prop('checked');
        ordersTable.find('tbody input[type="checkbox"]').prop('checked', isAllChecked).trigger('change');
    });

    function getCheckedOrderIds() {
        var ids = [];
        ordersTable.find('tbody input[type="checkbox"]').each(function () {
            var jObj = $(this);
            if (jObj.prop('checked')) {
                ids.push(jObj.parents('tr:first').data('orderId'));
            }
        });

        return ids;
    }

    function processRowChecked(jObj) {
        if (jObj.prop('checked')) {
            jObj.parents('tr:first').addClass('row-selected');
        } else {
            jObj.parents('tr:first').removeClass('row-selected');
        }
    }

    ordersTable.on('change', 'tbody input[type="checkbox"]', function () {
        processRowChecked($(this));
    });

    ordersTable.find('tbody input[type="checkbox"]').each(function () {
        processRowChecked($(this));
    });

    // Отправить в Dostavista
    orderListArea.find('.filter .send-to-dostavista').click(function (e) {
        var ids = getCheckedOrderIds();
        if (ids.length <= 0) {
            alert(dvbusiness.orders.translations.not_selected_error);
            return;
        }

        window.location.href = $(this).data('action') + '&' + $.param({'ids': ids});
    });
});