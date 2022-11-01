<?php echo $header; ?>
<div class="container">
  <div class="row">
    <!-- <div class="col-md-8 col-sm-8 col-xs-12"> -->
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
    </ul>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($orders) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-account-orders">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-right"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_customer; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <td class="hidden-xs"></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right col-order-id">#<?php echo $order['order_id']; ?></td>
              <td class="text-left col-status"><?php echo $order['status']; ?></td>
              <td class="text-left col-date-added"><?php echo $order['date_added']; ?></td>
              <td class="text-right col-products"><?php echo $order['products']; ?></td>
              <td class="text-left col-name"><?php echo $order['name']; ?></td>
              <td class="text-right col-total"><?php echo $order['total']; ?></td>
              <td class="text-right col-button"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip"
                  title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </div>
</div>
<script>
$(window).on('load', function() {
	var col_products_h = $(".table-account-orders").find(".col-products").height();
	var max_h = 52;
	$(".table-account-orders tbody td").each(function() {
		if ($(this).height() > col_products_h || $(this).height() > max_h) {
			max_h = $(this).height();
		}
	});
	$(".table-account-orders tbody td").each(function() {
		$(this).height(max_h);
	});
});
</script>
<?php echo $footer; ?>
