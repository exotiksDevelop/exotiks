<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php $simple_page = 'simpleregister'; include $simple_header; ?>
<div class="simple-content">
<?php } ?>
    <?php if (!$ajax || ($ajax && $popup)) { ?>
    <script type="text/javascript">
    (function($) {
    <?php if (!$popup && !$ajax) { ?>
        $(function(){
    <?php } ?>
            if (typeof Simplepage === "function") {
                var simplepage = new Simplepage({
                    additionalParams: "<?php echo $additional_params ?>",
                    additionalPath: "<?php echo $additional_path ?>",
                    mainUrl: "<?php echo $action; ?>",
                    mainContainer: "#simplepage_form",
                    useAutocomplete: <?php echo $use_autocomplete ? 1 : 0 ?>,
                    useGoogleApi: <?php echo $use_google_api ? 1 : 0 ?>,
                    scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                    javascriptCallback: function() {<?php echo $javascript_callback ?>}
                });

                simplepage.init();
            }
    <?php if (!$popup && !$ajax) { ?>
        });
    <?php } ?>
    })(jQuery || $);
    </script>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simplepage_form">
        <div class="simpleregister" id="simpleregister">
            <p class="simpleregister-have-account"><?php echo $text_account_already; ?></p>

			
			  <?php /* start socnetauth2 */ ?>
			  <?php echo $SOCNETAUTH2_DATA['code']; ?>
			  <?php /* end socnetauth2 */ ?>
			  
            
            <?php if ($error_warning) { ?>
            <div class="warning alert alert-danger"><?php echo $error_warning; ?></div>
            <?php } ?>
            <div class="simpleregister-block-content">
                <?php foreach ($rows as $row) { ?>
                  <?php echo $row ?>
                <?php } ?>
                <?php foreach ($hidden_rows as $row) { ?>
                  <?php echo $row ?>
                <?php } ?>
            </div>
            <div class="simpleregister-button-block buttons">
                <div class="simpleregister-button-right">
                    <?php if ($display_agreement_checkbox) { ?><span id="agreement_checkbox"><label><input type="checkbox" name="agreement" value="1" <?php if ($agreement == 1) { ?>checked="checked"<?php } ?> /><?php echo $text_agreement; ?></label>&nbsp;</span><?php } ?>
                    <a class="button btn-primary button_oc btn" data-onclick="submit" id="simpleregister_button_confirm"><span><?php echo $button_continue; ?></span></a>
                </div>
            </div>
        </div>
        <?php if ($redirect) { ?>
            <script type="text/javascript">
                location = "<?php echo $redirect ?>";
            </script>
        <?php } ?>
    </form>
<?php if (!$ajax && !$popup && !$as_module) { ?>
</div>
<?php include $simple_footer ?>
<?php } ?>