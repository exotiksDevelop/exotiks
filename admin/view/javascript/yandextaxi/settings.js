$(function () {
    $(document).ready(function ($) {
        const useOrderPriceForFreeSelect = $('[name="shipping_yandextaxi_free_shipping_enabled"]');

        handleUseOrderPriceForFreeSelectState(useOrderPriceForFreeSelect);

        useOrderPriceForFreeSelect.change(function() {
            handleUseOrderPriceForFreeSelectState($(this));
        });

        const fixedPriceIsOnSelect = $('[name="shipping_yandextaxi_fixed_shipping_enabled"]');

        handleFixedPriceIsOnSelectState(fixedPriceIsOnSelect);

        fixedPriceIsOnSelect.change(function() {
            handleFixedPriceIsOnSelectState($(this));
        });

        const discountIsOnSelect = $('[name="shipping_yandextaxi_discount_shipping_enabled"]');

        handleDiscountIsOnSelectState(discountIsOnSelect);

        discountIsOnSelect.change(function() {
            handleDiscountIsOnSelectState($(this));
        });


        function handleUseOrderPriceForFreeSelectState(select) {
            const input = $('[name="shipping_yandextaxi_free_shipping_value"]');

            if(select.val() == 1) {
                input.prop('disabled', false);
            } else {
                input.prop('disabled', true);
            }
        }

        function handleFixedPriceIsOnSelectState(select) {
            const input = $('[name="shipping_yandextaxi_fixed_shipping_value"]');

            if(select.val() == 1) {
                input.prop('disabled', false);
            } else {
                input.prop('disabled', true);
            }
        }

        function handleDiscountIsOnSelectState(select) {
            const input1 = $('[name="shipping_yandextaxi_discount_shipping_value"]');
            const input2 = $('[name="shipping_yandextaxi_discount_shipping_from"]');

            if(select.val() == 1) {
                input1.prop('disabled', false);
                input2.prop('disabled', false);
            } else {
                input1.prop('disabled', true);
                input2.prop('disabled', true);
            }
        }
    });
});

