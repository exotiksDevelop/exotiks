<?php if (($has_oauth_token || $kassa->getShopId()) && $isConnectionFailed && $kassa->isEnabled()) : ?>
    <div class="col-sm-offset-2 alert alert-danger"><i
                class="fa fa-exclamation-circle"></i> <?= $language->get('kassa_auth_connection_error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-warning btn_oauth_connect qa-yookassa-entrance ">
                <?= $language->get('kassa_auth_connect_to_kassa') ?>
            </button>
        </div>
    </div>
<?php elseif ($has_oauth_token) : ?>
    <div class="col-sm-offset-2 col-sm-10 qa-oauth-info">

        <?php if ($kassa->isEnabled()) : ?>
            <p class="qa-shop-type" data-qa-shop-type="<?= $kassa->isTestShop() ? 'test' : 'prod' ?>">
                <?= $kassa->isTestShop() ? $language->get('kassa_auth_test_shop') : $language->get('kassa_auth_real_shop') ?>
            </p>
        <?php endif ?>

        <?php if ($kassa->getShopId()) : ?>
            <p class="qa-shop-id" data-qa-shop-id="<?= $kassa->getShopId() ?>">Shop ID: <?= $kassa->getShopId() ?></p>
        <?php endif ?>

        <?php if ($kassa->isTestShop() && $kassa->isEnabled()) : ?>
            <p style="margin-top: 20px;"><?= $language->get('kassa_auth_switch_mode') ?></p>
        <?php endif ?>

    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-warning btn_oauth_connect qa-change-shop-button ">
                <?= $language->get('kassa_auth_change_btn_title') ?>
            </button>
        </div>
    </div>
<?php elseif ($kassa->getShopId()) : ?>
    <div class="row">
        <div class="col-sm-offset-2 col-md-6">
            <h3><?= $language->get('kassa_auth_connect_title') ?></h3>
        </div>
    </div>
    <div class="form-group required">
        <label class="col-sm-2 control-label" for="kassa-shop-id"><?= $language->get('kassa_shop_id_label') ?></label>
        <div class="col-sm-10">
            <input type="text" name="yoomoney_kassa_shop_id" value="<?= $kassa->getShopId() ?>"
                   placeholder="<?= $language->get('kassa_shop_id_label') ?>" id="kassa-shop-id" class="form-control"/>
        </div>
    </div>

    <div class="form-group required">
        <label class="col-sm-2 control-label"
               for="kassa-password"><?= $language->get('kassa_password_label') ?></label>
        <div class="col-sm-10">
            <input type="text" name="yoomoney_kassa_password" value="<?= $kassa->getPassword() ?>"
                   placeholder="<?= $language->get('kassa_password_label') ?>" id="kassa-password"
                   class="form-control"/>
            <p class="help-block"><?= $language->get('kassa_auth_help') ?></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-warning btn_oauth_connect qa-change-shop-button ">
                <?= $language->get('kassa_auth_change_btn_title') ?>
            </button>
        </div>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col-sm-offset-2 col-md-6">
            <h3><?= $language->get('kassa_auth_connect_title') ?></h3>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-warning btn_oauth_connect qa-connect-shop-button ?> ">
                <?= $language->get('kassa_auth_connect_btn_title') ?>
            </button>
        </div>
    </div>
<?php endif ?>

<div class="col-sm-offset-2 alert alert-danger auth-error-alert hidden"><i class="fa fa-exclamation-circle"></i>
    <?= $language->get('kassa_auth_connect_error') ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<script>
    jQuery(document).ready(function () {

        /**
         * Событие на кнопки Подключить магазин и Сменить магазин
         */
        jQuery(document).on('click', 'button.btn_oauth_connect', function (e) {
            jQuery(this).attr('disabled', true);
            jQuery(this).text('');
            jQuery(this).html('<i class="fa fa-refresh fa-spin fa-lg fa-fw qa-spinner"></i>');
            e.preventDefault()
            fetchOauthLink();
        })

        /**
         * Запрос на бэк для получения ссылки на авторизацию в OAuth
         */
        function fetchOauthLink() {
            jQuery.ajax({
                url: "<?= $oauth_connect_url ?>",
                dataType: "json",
                method: "GET",
                success: function (response) {
                    const responseData = JSON.parse(response);
                    showOauthWindow(responseData.oauth_url);
                },
                error: function(jqXHR, textStatus, error){
                    showError();
                    if (typeof jqXHR.responseJSON == "undefined") {
                        console.error(jqXHR, textStatus, error);
                        return;
                    }
                    console.error(jqXHR.responseJSON, textStatus, error);
                }
            });
        }

        /**
         * Показ окна с авторизацией в OAuth
         * @param url - Ссылка в OAuth
         */
        function showOauthWindow(url) {
            const oauthWindow = window.open(
                url,
                'Авторизация',
                'width=600,height=600, top='+((screen.height-600)/2)+', left='+((screen.width-600)/2 + window.screenLeft)+', menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=yes');

            const timer = setInterval(function() {
                if(oauthWindow.closed) {
                    if(oauthWindow.closed) {
                        clearInterval(timer);
                        getOauthToken();
                    }
                }
            }, 1000);
        }

        /**
         * Инициализация получения OAuth токена
         */
        function getOauthToken() {
            jQuery.ajax({
                url: "<?= $oauth_token_url ?>",
                dataType: "json",
                method: "GET",
                success: function (response) {
                    location.reload();
                },
                error: function(jqXHR, textStatus, error){
                    showError();
                    if (typeof jqXHR.responseJSON == "undefined") {
                        console.error(jqXHR, textStatus, error);
                        return;
                    }
                    console.error(jqXHR.responseJSON, textStatus, error);
                }
            });
        }

        function showError() {
            jQuery('.auth-error-alert').removeClass('hidden');
        }
    })
</script>