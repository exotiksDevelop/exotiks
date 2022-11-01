/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
function ll_ozon_init(collection) {
    ymaps.ready(
        function () {
            if (typeof collection != 'undefined' && collection != '') {
                ll_ozon_create_modal();

                var map = new ymaps.Map('ll_ozon_map', {
                        center: collection.data.features[0].geometry.coordinates,
                        controls: collection.controls,
                        zoom: 10,
                    }),
                    cluster = new ymaps.ObjectManager({
                        clusterize: true,
                        gridSize: 32,
                        margin: 20,
                        preset: 'islands#invertedGrayClusterIcons',
                    });

                cluster.add(collection.data);

                map.geoObjects.add(cluster);

                cluster.clusters.events
                    .add('mouseenter', function (e) {
                        cluster.clusters.setClusterOptions(e.get('objectId'), {
                            preset: 'islands#grayClusterIcons'
                        });
                    })
                    .add('mouseleave', function (e) {
                        cluster.clusters.setClusterOptions(e.get('objectId'), {
                            preset: 'islands#invertedGrayClusterIcons'
                        });
                    });

                for (var i in collection.delivery) {
                    switch (i) {
                        case 'pickpoint':
                            var floatIndex = 2;
                            break;
                        case 'postamat':
                            var floatIndex = 1;
                            break;
                        default:
                            var floatIndex = 0;
                            break;
                    }

                    var delivery = collection.delivery[i],
                        button = new ymaps.control.Button({
                            data: {
                                content: delivery.content,
                                title: delivery.title,
                                code: delivery.code,
                            },
                            options: {
                                selectOnClick: true,
                                size: 'small',
                                float: 'left',
                                floatIndex: floatIndex,
                                maxWidth: 170,
                            }
                        });

                    map.controls.add(button);

                    button.events
                        .add('press', function(e) {
                            target = e.get('target');
                            code = target.data.get('code');

                            map.controls.each(function(e) {
                                if (e.options.getName() == 'button' && e != target && e.deselect()) {
                                    e.deselect();
                                }
                            });

                            cluster.setFilter("object.params.code == code");
                        })
                        .add('deselect', function(e) {
                            cluster.setFilter(false);
                        });
                }
            }
        }
    );
}

function ll_ozon_create_modal() {
    $('#ll_ozon_modal').remove();

    html  = '<div id="ll_ozon_modal" class="modal ll_map">';
    html += '  <div class="modal-dialog modal-lg ll_modal">';
    html += '    <div class="modal-content">';
    html += '      <div class="modal-header">';
    html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    html += '        <h4 class="modal-title"><img src="image/catalog/ll_ozon/logo.png" /></h4>';
    html += '      </div>';
    html += '      <div class="modal-body"></div>';
    html += '    </div';
    html += '  </div>';
    html += '</div>';

    $('body').append(html);

    var width = $('#ll_ozon_modal .ll_modal').width() - 30,
        height = document.documentElement.clientHeight - 150;

    $('#ll_ozon_modal .modal-body').append('<div id="ll_ozon_map" style="width: ' + width + 'px; height: ' + height + 'px;"></div>');
}

var ll_ozon_code = null;

function ll_ozon_show_modal(filter, code) {
    if (typeof code != 'undefined' && code != '') {
        ll_ozon_code = code;

        $('#ll_ozon_filter_' + ll_ozon_code).click();
    }

    $('#ll_ozon_modal').modal('show');
}

function ll_ozon_hide_modal() {
    $('#ll_ozon_modal').modal('hide');
}

function ll_ozon_set_pickup_id(id, type) {
    $.ajax({
        url: 'index.php?route=shipping/ll_ozon/setPickupId', 
        type: 'post',
        data: 'id=' + id + '&type=' + type,
        dataType: 'json',
        complete: function() {
            ll_ozon_hide_modal();

            if (type) {
                $('input[value="ll_ozon.ll_ozon_' + type + '"]').prop('checked', true);
            } 

            // simplecheckout
            if (typeof (reloadAll) == 'function') {
                reloadAll();
            }

            // checkout
            setTimeout(function () {
                $('#button-shipping-address,#button-guest-shipping').trigger('click');
            }, 500);

            $('a[href=\'#collapse-shipping-method\']').trigger('click');

            // oct_fastorder
            if ($('.fastorder-panel-default').length) {
                $('.shipping-method').load('index.php?route=checkout/oct_fastorder/shipping_method', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, input[name=\'shipping_method\']:first, .checkout_form textarea, .checkout_form select'), function() {
                    $('#cart-table').load('index.php?route=checkout/oct_fastorder/cart', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select'));
                });
            }

            // newstorecheckout
            if (typeof (update_checkout) == 'function') {
                update_checkout();
            }

            // Quick n Easy checkout
            if (typeof (ajax_update_cart) == 'function') {
                ajax_update_cart(true);
            }

            // lightshop
            if (typeof (refreshmetods) == 'function') {
                refreshmetods();
            }

            // uni_checkout
            if (typeof (uniShippingUpdate) == 'function') {
                uniShippingUpdate();
            }

            // ll_shipping_widget
            if (typeof (ll_shipping_widget_update) == 'function') {
                ll_shipping_widget_update();
            }
        }
    });
};

function ll_ozon_set_pickup_city(pickup_city) {
    $.ajax({
        url: 'index.php?route=shipping/ll_ozon/setPickupCity', 
        type: 'post',
        data: 'pickup_city=' + pickup_city,
        dataType: 'json',
        complete: function() {
            // simplecheckout
            if (typeof (reloadAll) == 'function') {
                reloadAll()
            }

            // checkout
            setTimeout(function () {
                $('#button-shipping-address,#button-guest-shipping').trigger('click');
            }, 500);

            $('a[href=\'#collapse-shipping-method\']').trigger('click');

            // oct_fastorder
            if ($('.fastorder-panel-default').length) {
                $('.shipping-method').load('index.php?route=checkout/oct_fastorder/shipping_method', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, input[name=\'shipping_method\']:first, .checkout_form textarea, .checkout_form select'), function() {
                    $('#cart-table').load('index.php?route=checkout/oct_fastorder/cart', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select'));
                });
            }

            // newstorecheckout
            if (typeof (update_checkout) == 'function') {
                update_checkout();
            }

            // Quick n Easy checkout
            if (typeof (ajax_update_cart) == 'function') {
                ajax_update_cart(true);
            }

            // lightshop
            if (typeof (refreshmetods) == 'function') {
                refreshmetods();
            }

            // uni_checkout
            if (typeof (uniShippingUpdate) == 'function') {
                uniShippingUpdate();
            }

            // ll_shipping_widget
            if (typeof (ll_shipping_widget_update) == 'function') {
                ll_shipping_widget_update();
            }
        }
    });
};
