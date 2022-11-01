<?php

/**
 * @var \YooMoneyModule\Model\KassaModel $kassa
 */
?>
<style type="text/css">

    .yoomoney-pay-button {
        font-family: YandexSansTextApp-Regular, Arial, Helvetica, sans-serif;
        text-align: center;
        height: 60px;
        width: 155px;
        border-radius: 4px;
        transition: 0.1s ease-out 0s;
        color: #000;
        box-sizing: border-box;
        outline: 0;
        border: 0;
        background: #FFDB4D;
        cursor: pointer;
        font-size: 12px;
    }

    .yoomoney-pay-button:hover, .yoomoney-pay-button:active {
        background: #f2c200;
    }

    .yoomoney-pay-button span {
        display: block;
        font-size: 20px;
        line-height: 20px;
    }

    .yoomoney-pay-button_type_fly {
        box-shadow: 0 1px 0 0 rgba(0, 0, 0, 0.12), 0 5px 10px -3px rgba(0, 0, 0, 0.3);
    }
</style>

<?php if ($fullView) : ?>

<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div class="container">
    <?php echo $content_top; ?>
    <?php endif; ?>
    <h3><?php echo $kassa->getDisplayName(); ?></h3>
    <form method="post" action="" id="yoomoney-payment-form">
        <input type="hidden" name="kassa_payment_method" value="b2b_sberbank" />
        <div class="buttons">
            <div class="pull-right">
                <button class="btn btn-primary" id="continue-button" type="button"><?php echo $language->get('text_continue'); ?></button>
            </div>
        </div>
    </form>
    <script type="text/javascript"><!--
        jQuery(document).ready(function() {
            var paymentType = jQuery('input[name=kassa_payment_method]');
            paymentType.change(function () {
                var id = '#payment-' + jQuery(this).val();
                jQuery('.additional').css('display', 'none');
                jQuery(id).css('display', 'block');
            });

            jQuery('#continue-button').off('click').on('click', function (event) {
                event.preventDefault();
                var form = jQuery("#yoomoney-payment-form")[0];
                jQuery.ajax({
                    url: "<?php echo $validate_url; ?>",
                    dataType: "json",
                    method: "GET",
                    data: {
                        paymentType: form.kassa_payment_method.value,
                        qiwiPhone: (form.qiwiPhone ? form.qiwiPhone.value : ''),
                        alphaLogin: (form.alfaLogin ? form.alfaLogin.value : '')
                    },
                    success: function (data) {
                        if (data.success) {
                            document.location = data.redirect;
                        } else {
                            onValidateError(data.error);
                        }
                    },
                    failure: function () {
                        onValidateError('Failed to create payment');
                    }
                });
            });

            jQuery('.yoomoney-payment-form-installments').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const form = this;
                jQuery.ajax({
                    url: "<?php echo $validate_url; ?>",
                    dataType: "json",
                    method: "GET",
                    data: {
                        paymentType: 'installments',
                    },
                    success: function (data) {
                        if (data.success) {
                            document.location = data.redirect;
                        } else {
                            onValidateError(data.error);
                        }
                    },
                    failure: function () {
                        onValidateError('Failed to create payment');
                    }
                });
            });

            function onValidateError(errorMessage) {
                var warning = jQuery('#yoomoney-payment-form .alert');
                if (warning.length > 0) {
                    warning.fadeOut(300, function () {
                        warning.remove();
                        var content = '<div class="alert alert-danger">' + errorMessage + '<button type="button" class="close" data-dismiss="alert">×</button></div>';
                        jQuery('#yoomoney-payment-form').prepend(content);
                        jQuery('#yoomoney-payment-form .alert').fadeIn(300);
                    });
                } else {
                    var content = '<div class="alert alert-danger">' + errorMessage + '<button type="button" class="close" data-dismiss="alert">×</button></div>';
                    jQuery('#yoomoney-payment-form').prepend(content);
                    jQuery('#yoomoney-payment-form .alert').fadeIn(300);
                }
            }

        });
        //--></script>
    <?php if ($kassa->showInstallmentsBlock()): ?>
        <script>
            if (typeof CheckoutCreditUI !== "undefined") {
                const yoomoneyCheckoutCreditUI = CheckoutCreditUI({
                    shopId: '<?= $shopId?>',
                    sum: '<?= $sum?>',
                    language: '<?= $language->get("code")?>'
                });
                yoomoneyCheckoutCreditUI({
                    type: 'button',
                    theme: 'default',
                    domSelector: '.installment-wrapper'
                });
            }
        </script>
    <?php endif; ?>
    <?php if ($fullView) : ?>
    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
<?php endif; ?>
