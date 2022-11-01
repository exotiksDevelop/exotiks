$(function () {
    $('body').on('change', '.shipping_dvbusiness_description select', function () {
        sendFieldsData();
    });

    function sendFieldsData() {
        var data = {};
        $('.shipping_dvbusiness_description select').each(function () {
            data[this.name] = $(this).val();
        });

        $.ajax ({
            async   : false,
            url     : 'index.php?route=extension/shipping/dvbusiness/setShippingFields',
            type    : 'POST',
            dataType: 'json',
            data    : data
        });
    }

    if ($('.shipping_dvbusiness_description select').length) {
        sendFieldsData();
    }
});