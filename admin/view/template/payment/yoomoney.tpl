<?php

/**
 * @var Language $language
 */

echo $header;
echo $column_left;

?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-payment-yoomoney" data-toggle="tooltip" title="<?php echo $language->get('button_save'); ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $language->get('button_cancel'); ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $language->get('module_title'); ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $successMessage; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>

        <?php if ($is_needed_show_nps): ?>
        <div class="alert alert-success yoomoney_nps_block success yoomoney_nps_block"><i class="fa fa-exclamation-circle"></i>
            <?php echo $nps_block_text; ?>
            <button type="button" class="close yoomoney_nps_close"  data-dismiss="alert"
                    data-link="<?php echo $callback_off_nps; ?>">&times;</button>
        </div>
        <?php endif; ?>

        <ul class="nav nav-tabs">
            <li<?php echo ($lastActiveTab == 'tab-kassa' ? ' class="active"' : ''); ?>><a href="#tab-kassa" data-toggle="tab"><?php echo $language->get('kassa_tab_header'); ?></a></li>
            <li<?php echo ($lastActiveTab == 'tab-wallet' ? ' class="active"' : ''); ?>><a href="#tab-wallet" data-toggle="tab"><?php echo $language->get('wallet_tab_header'); ?></a></li>
            <li<?php echo ($lastActiveTab == 'tab-update' ? ' class="active"' : ''); ?>><a href="#tab-update" data-toggle="tab"><?php echo $language->get('update_tab_header'); ?></a></li>
        </ul>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment-yoomoney" class="form-horizontal">
            <input type="hidden" name="yoomoney_nps_prev_vote_time" value="<?php echo $yoomoney_nps_prev_vote_time; ?>">
            <input type="hidden" name="language_reload" value="1" />
            <input type="hidden" name="last_active_tab" id="last-active-tab" value="<?php echo $lastActiveTab; ?>" />
            <div class="tab-content bootstrap">
                <div class="tab-pane<?php echo ($lastActiveTab == 'tab-kassa' ? ' active' : ''); ?>" id="tab-kassa">
                    <?php include_once $module->incl('kassa.tpl'); ?>
                </div>
                <div class="tab-pane<?php echo ($lastActiveTab == 'tab-wallet' ? ' active' : ''); ?>" id="tab-wallet">
                    <?php include_once $module->incl('wallet.tpl'); ?>
                </div>
                <div class="tab-pane<?php echo ($lastActiveTab == 'tab-update' ? ' active' : ''); ?>" id="tab-update">
                    <?php include_once $module->incl('update.tpl'); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function () {
    var buttons = jQuery('.enable-button');
    buttons.change(function () {
        if (this.checked) {
            for (var i = 0; i < buttons.length; ++i) {
                if (buttons[i] != this) {
                    buttons[i].checked = false;
                }
            }
        }
    });
    var currentTabInput = jQuery('#last-active-tab')[0];
    $('ul.nav-tabs li').on('shown.bs.tab', function (e) {
        currentTabInput.value = e.target.getAttribute('href').substr(1);
    });

    function yoomoney_nps_close() {
        $.ajax({url: $('.yoomoney_nps_close').data('link')})
            .done(function () {
                $('.yoomoney_nps_block').slideUp();
                $('input[name=yoomoney_nps_prev_vote_time]').val('<?php echo $yoomoney_nps_current_vote_time; ?>');
            });
    }

    function yoomoney_nps_goto() {
        window.open('https://yandex.ru/poll/6JLRa1j44WnTx9UVc6k94g');
        yoomoney_nps_close();
    }

    $('.yoomoney_nps_link').on('click', yoomoney_nps_goto);
    $('.yoomoney_nps_close').on('click', yoomoney_nps_close);
});
</script>

<?php echo $footer; ?>