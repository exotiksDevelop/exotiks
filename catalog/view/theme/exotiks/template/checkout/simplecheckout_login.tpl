<div class="simplecheckout-block" id="simplecheckout_login" <?php echo $has_error ? 'data-error="true"' : '' ?>>
    <div class="simplecheckout-block-content">
        <div id="simple_login_header"><img style="cursor:pointer;" data-onclick="close" src="<?php echo $additional_path ?>catalog/view/theme/exotiks/stylesheet/images/availability-x.svg"></div>
        <?php if ($error_login) { ?>
        <div class="alert alert-danger simplecheckout-warning-block"><?php echo $error_login ?></div>
        <?php } ?>
        <fieldset>
            <div class="form-group">
                <label class="control-label"><?php echo $entry_email; ?></label>
                <input class="form-control" data-onkeydown="detectEnterAndLogin" type="text" name="email" value="<?php echo $email; ?>" /></label>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo $entry_password; ?></label>
                <input class="form-control" data-onkeydown="detectEnterAndLogin" type="password" name="password" value="" /></label>
                <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
            </div>
            <a id="simplecheckout_button_login" data-onclick="login" class="button"><span><?php echo $button_login; ?></span></a>
        </fieldset>
    </div>
</div>