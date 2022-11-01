<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php $simple_page = 'simpleedit'; include $simple_header; ?>
<div class="simple-content">
<?php } ?>
    <?php if (!$ajax || ($ajax && $popup)) { ?>
    <script type="text/javascript">
        var startSimpleInterval = window.setInterval(function(){
            if (typeof jQuery !== 'undefined' && typeof Simplepage === "function" && jQuery.isReady) {
                window.clearInterval(startSimpleInterval);

                var simplepage = new Simplepage({
                    additionalParams: "<?php echo $additional_params ?>",
                    additionalPath: "<?php echo $additional_path ?>",
                    mainUrl: "<?php echo $action; ?>",
                    mainContainer: "#simplepage_form",
                    scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                    notificationDefault: <?php echo $notification_default ? 1 : 0 ?>,
                    notificationToasts: <?php echo $notification_toasts ? 1 : 0 ?>,
                    notificationCheckForm: <?php echo $notification_check_form ? 1 : 0 ?>,
                    notificationCheckFormText: "<?php echo $notification_check_form_text ?>",
                    javascriptCallback: function() {<?php echo $javascript_callback ?>}
                });

                if (typeof toastr !== 'undefined') {
                    toastr.options.positionClass = "<?php echo $notification_position ? $notification_position : 'toast-top-right' ?>";
                    toastr.options.timeOut = "<?php echo $notification_timeout ? $notification_timeout : '5000' ?>";
                    toastr.options.progressBar = true;
                }

                simplepage.init();
            }
        },0);
    </script>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simplepage_form">
        <div class="simpleregister" id="simpleedit">
            <?php if ($error_warning) { ?>
            <div class="warning alert alert-danger"><?php echo $error_warning; ?></div>
            <?php } ?>
            <div class="simpleregister-block-content">
                <?php foreach ($rows as $row) { ?>
                  <?php echo $row ?>
                <?php } ?>
            </div>
            <div class="simpleregister-button-block buttons">
                <div class="simpleregister-button-right">
                    <a class="button btn-primary button_oc btn" data-onclick="submit" id="simpleregister_button_confirm"><span><?php echo $button_continue; ?></span></a>
                </div>
            </div>
        </div>
        <?php if ($redirect) { ?>
            <input type="hidden" id="simple_redirect_url" value="<?php echo $redirect ?>">
        <?php } ?>
    </form>
<?php if (!$ajax && !$popup && !$as_module) { ?>
</div>
<?php include $simple_footer ?>
<?php } ?>