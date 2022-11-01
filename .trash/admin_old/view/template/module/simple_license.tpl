<?php echo $header; ?><?php echo $column_left ?><?php include 'simple_header.tpl' ?>
<div class="help"><?php echo $l->get('text_license_help') ?></div>
<div class="help">dmitriy@simpleopencart.com</div>
<form action="<?php echo $action_main; ?>" method="post" enctype="multipart/form-data" id="form">
  <h3><?php echo $domain ?></h3>
  <h3><?php echo $l->get('entry_license') ?></h3>
  <h4><?php echo $l->get('text_license_exchange') ?> <a href="http://simpleopencart.com/index.php?route=checkout/license&key=<?php echo $old_license ?>&domain=<?php echo urlencode($domain) ?>" target="_blank">http://simpleopencart.com/index.php?route=checkout/license&key=<?php echo $old_license ?>&domain=<?php echo urlencode($domain) ?><a></h4>
  <div>
    <textarea name="simple_license" rows="2" cols="100"></textarea><br><br><a onclick="$('form#form').submit()" class="button btn btn-primary"><?php echo $l->get('button_save'); ?></a>
  </div>
</form>
<?php include 'simple_footer.tpl' ?>
<?php echo $footer; ?>