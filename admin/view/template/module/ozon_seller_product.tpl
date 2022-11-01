<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php echo $btn_update_status; ?>
				<button type="button" id="checkbox-update" data-toggle="tooltip" title="<?php echo $update_products_ozon; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
				<button type="button" data-toggle="tooltip" title="<?php echo $text_download_product; ?>" class="btn btn-primary download-product"><i class="fa fa-download"></i></button>
				<button type="button" class="btn btn-primary update-status-product" data-toggle="tooltip" title="<?php echo $text_status_product; ?>"><i class="fa fa-exchange"></i></button>
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $url_general; ?>"><?php echo $text_edit; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_order; ?>"><?php echo $order_ozon; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_export; ?>"><?php echo $text_export; ?></a></li>
				  </ul>
				</div>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title_my_product; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="log_process"></div>
			<div class="well">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
							<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-sku"><?php echo $entry_sku; ?></label>
							<input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
							<input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="select-status"><?php echo $entry_status; ?></label>
							<select class="form-control" name="filter_status">
								<option value="*"></option>
								<?php foreach ($statuses as $key => $status) { ?>
								<?php if ($key == $filter_status) { ?>
								<option value="<?php echo $key; ?>" selected="selected"><?php echo $status; ?></option>
								<?php } else { ?>
								<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
						<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
					</div>
				</div>
			</div>
			<form id="ozon_products" action="" method="post">
			<table class="table table-bordered table-hover table-sm">
				<thead>
					<tr>
						<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
						<td class="text-left"><?php if ($sort == 'pd.name') { ?>
						<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'p.model') { ?>
						<a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'p.sku') { ?>
						<a href="<?php echo $sort_sku; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sku; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_sku; ?>"><?php echo $column_sku; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'p.status') { ?>
						<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'p.date') { ?>
						<a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
						<?php } ?></td>
						<td><?php echo $text_error; ?></td>
						<td style="min-width:60px;"><i class="fa fa-cube"></i> <?php echo $text_fbs; ?></td>
						<td style="min-width:60px;"><i class="fa fa-cube"></i> <?php echo $text_fbo; ?></td>
						<td style="min-width:60px;"><i class="fa fa-rub"></i> <?php echo $text_price_oc; ?></td>
						<td style="min-width:60px;"><i class="fa fa-rub"></i> <?php echo $text_price_oz; ?></td>
						<td style="min-width:60px;"><i class="fa fa-pie-chart"></i> <?php echo $text_fbo; ?></td>
						<td style="min-width:60px;"><i class="fa fa-pie-chart"></i> <?php echo $text_fbs; ?></td>
						<td style="width:150px;"></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($products) { ?>
					<?php foreach ($products as $product) { ?>
					<tr id="<?php echo $product['ozon_product_id']; ?>">
					<td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
					<?php } ?></td>
					<td class="text-left"><?php echo $product['name']; ?></td>
					<td class="text-left"><?php echo $product['model']; ?></td>
					<td class="text-left"><?php echo $product['sku']; ?></td>
					<td class="text-left"><?php echo $product['status']; ?></td>
					<td class="text-left"><?php echo $product['date']; ?></td>
					<td class="text-left"><?php echo $product['error']; ?></td>
					<td class="text-left"><?php echo $product['stock_fbs']; ?></td>
					<td class="text-left"><?php echo $product['stock_fbo']; ?></td>
					<td class="text-left"><?php echo $product['price_oc']; ?></td>
					<td class="text-left"><?php echo $product['price_oz']; ?></td>
					<td class="text-left"><?php echo $product['komission_fbo']; ?></td>
					<td class="text-left"><?php echo $product['komission_fbs']; ?></td>
					<td class="text-right">
						<button type="button" class="btn btn-primary btn-sm update" id="update<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>" data-toggle="tooltip" title="<?php echo $button_update_ozon; ?>"><i class="fa fa-refresh"></i></button>
						<button type="button" class="btn btn-danger btn-sm archive" value="<?php echo $product['ozon_product_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete_ozon; ?>"><i class="fa fa-trash-o"></i></button>
						<?php echo $product['view_ozon']; ?>
						<a href="index.php?route=catalog/product/edit&token=<?php echo $token; ?>&product_id=<?php echo $product['product_id']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-pencil"></i></a></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
					<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			</form>
			<div class="row">
				<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
				<div class="col-sm-6 text-right"><?php echo $results; ?></div>
			</div>
	</div>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--

$('#button-filter').on('click', function() {
	var url = 'index.php?route=module/ozon_seller/product&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_sku = $('input[name=\'filter_sku\']').val();

	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});

$(document).on('click', '.archive', function() {
	var product_id = $(this).attr('value');
	$.ajax({
		url: '/index.php?route=module/ozon_seller/archive&cron_pass=<?php echo $ozon_seller_cron_pass; ?>&product_id=' + product_id,
		success: function(respon) {
			if (respon == true) {
				$('#' + product_id).remove();
			} else {
				alert(respon);
			}
		}
	});
});

$(document).on('click', '.update', function() {
	var product_id = $(this).attr('value');
	$.ajax({
		url: 'index.php?route=module/ozon_seller/updateproductozon&token=<?php echo $token; ?>&product_shop_id=' + product_id,
		success: function() {
			$('#update' + product_id).removeClass('btn-primary');
			$('#update' + product_id).addClass('btn-success');
		}
	});

});

$(document).on('click', '#checkbox-update', function() {
	$.ajax({
		url: 'index.php?route=module/ozon_seller/updateproductozon&token=<?php echo $token; ?>',
		type: "POST",
		data: $("#ozon_products").serialize(),
		success: function() {
			location.reload();
		}
	});
});

$(document).on('click', '.download-product', function() {
	$.ajax({
		url: '/index.php?route=module/ozon_seller/downloadproduct&cron_pass=<?php echo $ozon_seller_cron_pass; ?>',
		beforeSend: function() {
			$('.download-product').button('loading');
		},
		success: function(html) {
			alert(html);
			location.reload();
		}
	});
});

//обновить статусы товара
$('.update-status-product').on('click', function() {
	$.ajax({
		url: '<?php echo $url_update_status_product; ?>',
		beforeSend: function() {
			$('.update-status-product').button('loading');
		},
		success: function() {
			$('.update-status-product').button('reset');
		}
	});
	var interval = 1000;
	function doAjax() {
		$.ajax({
			url: "/system/ozon_seller_process.txt",
			cache: false,
			success: function(html){

				$(".log_process").empty();
				$(".log_process").append("<div class=\"alert alert-success\">" + html + "</div>");
			},
			complete: function (data) {
				setTimeout(doAjax, interval);
			}
		});
	}
	setTimeout(doAjax, interval);
});

//--></script>
