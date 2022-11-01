<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php echo $button_delete_no_create; ?>
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $cancel; ?>"><?php echo $button_return_module; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_orders_wb; ?>"><?php echo $text_orders_wb; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_attributes; ?>"><?php echo $text_attributes; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_rima; ?>"><?php echo $text_rima; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_supplies; ?>" target="_blank"><?php echo $text_supplies; ?></a></li>
				  </ul>
				</div>
				<button type="button" class="btn btn-primary check-product" data-toggle="tooltip" title="<?php echo $text_check_product; ?>" <?php echo $button_check_product ? false : ' disabled'; ?>><i class="fa fa-exchange"></i></button>
				<button type="button" class="btn btn-primary download-product" data-toggle="tooltip" title="<?php echo $text_download_product; ?>"><i class="fa fa-download"></i></button>
				<button type="button" class="btn btn-primary update-products" data-toggle="tooltip" title="<?php echo $text_update_product; ?>"><i class="fa fa-refresh"></i></button>
				<button type="button" class="btn btn-primary check-img" data-toggle="tooltip" title="<?php echo $text_check_img; ?>"><i class="fa fa-picture-o"></i></button>
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
						<label class="control-label"><?php echo $entry_name; ?></label>
						<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $text_barcode; ?></label>
						<input type="text" name="filter_barcode" value="<?php echo $filter_barcode; ?>" placeholder="<?php echo $text_barcode; ?>" class="form-control" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_sku; ?></label>
						<input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" placeholder="<?php echo $entry_sku; ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $column_nm; ?></label>
						<input type="text" name="filter_nm" value="<?php echo $filter_nm; ?>" placeholder="<?php echo $column_nm; ?>" class="form-control" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_model; ?></label>
						<input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $category_shop; ?></label>
						<select class="form-control" name="filter_category">
							<option value=""></option>
							<?php foreach ($export_categorys as $key => $export_category) { ?>
							<?php if ($key == $filter_category) { ?>
							<option value="<?php echo $key; ?>" selected="selected"><?php echo $export_category; ?></option>
							<?php } else { ?>
							<option value="<?php echo $key; ?>"><?php echo $export_category; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_status; ?></label>
						<select class="form-control filter-status" name="filter_status">
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
		<form id="wb_products" action="" method="post">
		<table class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<td style="width: 1px;" class="text-center"><input type="checkbox" class="checked-all" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
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
					<td class="text-left"><?php echo $text_barcode; ?></td>
					<td class="text-left"><?php echo $column_imt; ?></td>
					<td class="text-left"><?php echo $column_nm; ?></td>
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
					<td class="text-left"><?php echo $text_error; ?></td>
					<td style="width:150px;" class="text-right"></td>
				</tr>
			</thead>
			<tbody>
				<?php if ($products) { ?>
				<?php foreach ($products as $product) { ?>
				<tr id="">
				<td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
				<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
				<?php } else { ?>
				<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
				<?php } ?></td>
				<td class="text-left"><?php echo $product['name']; ?></td>
				<td class="text-left"><?php echo $product['model']; ?></td>
				<td class="text-left"><?php echo $product['sku']; ?></td>
				<td class="text-left"><?php echo $product['barcode']; ?></td>
				<td class="text-left"><?php echo $product['imt_id']; ?></td>
				<td class="text-left"><?php echo $product['nm_id']; ?></td>
				<td class="text-left" data-status="<?php echo $product['product_id']; ?>"><?php echo $product['status']; ?></td>
				<td class="text-left"><?php echo $product['date']; ?></td>
				<td class="text-left"><?php echo $product['error']; ?></td>
				<td class="text-right">
					<a href="index.php?route=catalog/product/edit&token=<?php echo $token; ?>&product_id=<?php echo $product['product_id']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-pencil"></i></a>
					<?php echo $product['view']; ?>
				</td>
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
	var url = 'index.php?route=module/cdl_wildberries/product&token=<?php echo $token; ?>';
	var filter_name = $('input[name=\'filter_name\']').val();
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_barcode = $('input[name=\'filter_barcode\']').val();
	if (filter_barcode) {
		url += '&filter_barcode=' + encodeURIComponent(filter_barcode);
	}
	var filter_model = $('input[name=\'filter_model\']').val();
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	var filter_sku = $('input[name=\'filter_sku\']').val();
	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}
	var filter_nm = $('input[name=\'filter_nm\']').val();
	if (filter_nm) {
		url += '&filter_nm=' + encodeURIComponent(filter_nm);
	}
	var filter_status = $('select[name=\'filter_status\']').val();
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	var filter_category = $('select[name=\'filter_category\']').val();
	if (filter_category != '') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}
	location = url;
});

// Кнопка проверить статус товара
$('.check-product').on('click', function() {
	$.ajax({
		url: '<?php echo $url_update_status_product; ?>',
		type: 'post',
    data: 'pass=<?php echo $pass; ?>',
		beforeSend: function() {
			$('.check-product').button('loading');
		},
		success: function() {
			location.reload();
		}
	});
});

// Кнопка скачать товары
$('.download-product').on('click', function() {
	$.ajax({
		url: '<?php echo $url_download_product; ?>',
		type: 'post',
    data: 'pass=<?php echo $pass; ?>',
		beforeSend: function() {
			$('.download-product').button('loading');
		},
		success: function(html) {
			$('.log_process').after('<div class="alert alert-info" role="alert">' + html + '</div>');
			$('.download-product').button('reset');
		}
	});
});

// Кнопка обновить товары
$(document).on('click', '.update-products', function() {
	$.ajax({
		url: '<?php echo $url_update_products; ?>',
		type: "POST",
		data: $('#wb_products').serialize() + '&pass=<?php echo $pass; ?>',
		beforeSend: function() {
			$('.update-products').button('loading');
		},
		success: function() {
			location.reload();
		}
	});
});

// Фильтр по Enter
$(document).keypress(function (e) {
  if (e.which == 13) {
    document.getElementById('button-filter').click();
  }
});

// Активность кнопок
$('.update-products').prop('disabled', true);
$('.check-img').prop('disabled', true);
$('input[name^=\'selected\']:first').trigger('change');

$('input[name^=\'selected\']').on('change', function() {
	btnStatus();
});
$('.checked-all').on('change', function() {
	btnStatus();
});

function btnStatus() {
	$('.update-products').prop('disabled', true);
	$('.check-img').prop('disabled', true);
	var selected = $('input[name^=\'selected\']:checked');
	if (selected.length) {
		$('.update-products').prop('disabled', false);
		$('.check-img').prop('disabled', false);
	}
}

// Кнопка проверить фото товаров
$(document).on('click', '.check-img', function() {
	$.ajax({
		url: '<?php echo $url_check_img; ?>',
		type: 'POST',
		data: $('#wb_products').serialize() + '&pass=<?php echo $pass; ?>',
		beforeSend: function() {
			$('.check-img').button('loading');
		},
		success: function() {
			location.reload();
		}
	});
});

// Выпадющий список статуса товара
$('.filter-status').change(function() {
	document.getElementById('button-filter').click();
});

//--></script>
