<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php include $simple_header; ?>
<?php } ?>
  <div class="content"><?php echo $text_error; ?></div>
  <div class="simplecheckout-button-block buttons">
    <div class="simplecheckout-button-right right"><a href="<?php echo $continue; ?>" class="button btn-primary button_oc btn"><span><?php echo $button_continue; ?></span></a></div>
  </div>
<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php include $simple_footer ?>
<?php } ?>