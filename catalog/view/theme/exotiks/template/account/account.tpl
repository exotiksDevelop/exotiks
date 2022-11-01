<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><div class="left"><?//php echo $column_left; ?></div>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><a class="my_account_menu_openner closed" href="javascript:void(0);"><?php echo $text_my_account; ?>&nbsp;&nbsp;<span class="xicon open-icon">↓</span><span class="xicon close-icon" style="display:none;">↑</span></a></h2>
      <div class="xhidden xslide my_account_menu" style="display:none;">
	  <ul class="list-unstyled">
	    <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
        <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
        <!--<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>-->
      </ul>
      <!--<h2><?php echo $text_my_orders; ?></h2>-->
      <ul class="list-unstyled">
        <!--<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>-->
        <?php if ($reward) { ?>
        <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
        <?php } ?>
        <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
        <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
        <!--<li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>-->
      </ul>
	  </div>
      <h2><?php echo $text_my_newsletter; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      </ul>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script>
function my_account_menu_open(aelm) {
	$(aelm).removeClass('closed');
	$(aelm).addClass('openned');
	$(aelm).find(".open-icon").hide();
	$(".my_account_menu").slideDown(400, function() {
		$(aelm).find(".close-icon").show();
	});
}
function my_account_menu_close(aelm) {
	$(aelm).removeClass('openned');
	$(aelm).addClass('closed');
	$(aelm).find(".close-icon").hide();
	$(".my_account_menu").slideUp(400, function() {
		$(aelm).find(".open-icon").show();
	});
}
$(document).on("click", ".my_account_menu_openner.closed", function() {
	my_account_menu_open(this);
});
$(document).on("click", ".my_account_menu_openner.openned", function() {
	my_account_menu_close(this);
});
</script>
