<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $cancel; ?>"><?php echo $button_return_module; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $text_product; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_attributes; ?>"><?php echo $text_attributes; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_rima; ?>"><?php echo $text_rima; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_supplies; ?>" target="_blank"><?php echo $text_supplies; ?></a></li>
				  </ul>
				</div>
				<button type="button" class="btn btn-success packing" data-toggle="tooltip" title="<?php echo $text_packing; ?>"><i class="fa fa-cubes"></i></button>
			</div>
			<h1><?php echo $heading_title_order; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="well">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $text_wb_order_id; ?></label>
						<input type="text" name="filter_wb_order_id" value="<?php if ($filter_wb_order_id) echo $filter_wb_order_id; ?>" placeholder="<?php echo $text_wb_order_id; ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $text_barcode; ?></label>
						<input type="text" placeholder="<?php echo $text_barcode; ?>" name="filter_barcode" class="form-control" value="<?php if ($filter_barcode) echo $filter_barcode; ?>" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_status; ?></label>
						<select class="form-control" name="filter_status">
							<?php foreach ($statuses as $key => $status) { ?>
							<?php if ($key == $filter_status) { ?>
							<option value="<?php echo $key; ?>" selected="selected"><?php echo $status; ?></option>
							<?php } else { ?>
							<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $text_sticker_number; ?></label>
						<input type="text" placeholder="<?php echo $text_sticker_number; ?>" name="filter_sticker" class="form-control" value="<?php if ($filter_sticker) echo $filter_sticker; ?>" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_user_status; ?></label>
						<select class="form-control" name="filter_user_status">
							<?php foreach ($user_statuses as $key => $user_status) { ?>
							<?php if ($key == $filter_user_status) { ?>
							<option value="<?php echo $key; ?>" selected="selected"><?php echo $user_status; ?></option>
							<?php } else { ?>
							<option value="<?php echo $key; ?>"><?php echo $user_status; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $text_supplies; ?></label>
						<input type="text" placeholder="<?php echo $text_supplies; ?>" name="filter_supplies" class="form-control" value="<?php if ($filter_supplies) echo $filter_supplies; ?>" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_shipment_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_shipment_date" value="<?php if ($filter_shipment_date) echo $filter_shipment_date; ?>" placeholder="<?php echo $entry_shipment_date; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span></div>
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $text_created; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_date_created" value="<?php if ($filter_date_created) echo $filter_date_created; ?>" placeholder="<?php echo $text_created; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span></div>
					</div>
					<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<form id="wb_order" action="" method="post">
		<table id="postings" class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
					<td><?php if ($sort == 'date_created') { ?>
					<a href="<?php echo $sort_date_created; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_created; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_date_created; ?>"><?php echo $text_created; ?></a>
					<?php } ?></td>
					<td><?php if ($sort == 'wb_order_id') { ?>
					<a href="<?php echo $sort_wb_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_wb_order_id; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_wb_order_id; ?>"><?php echo $text_wb_order_id; ?></a>
					<?php } ?></td>
					<td><?php echo $column_image; ?></td>
					<td><?php echo $column_name; ?></td>
					<td><?php echo $text_price; ?></td>
					<td class="text-left"><?php if ($sort == 'status') { ?>
					<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
					<?php } ?></td>
					<td class="text-left"><?php if ($sort == 'user_status') { ?>
					<a href="<?php echo $sort_user_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_user_status; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_user_status; ?>"><?php echo $column_user_status; ?></a>
					<?php } ?></td>
					<td><?php if ($sort == 'shipment_date') { ?>
					<a href="<?php echo $sort_shipment_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_shipment_date; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_shipment_date; ?>"><?php echo $entry_shipment_date; ?></a>
					<?php } ?></td>
					<td><?php if ($sort == 'supplie') { ?>
					<a href="<?php echo $sort_supplies; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_supplies; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_supplies; ?>"><?php echo $text_supplies; ?></a>
					<?php } ?></td>
					<td><?php echo $column_date; ?></td>
					<td style="width:100px;"></td>
				</tr>
			</thead>
			<tbody>
				<?php if ($postings) { ?>
					<?php foreach ($postings as $posting) { ?>
						<tr id="<?php echo $posting['wb_order_id']; ?>">
						<td class="text-center"><?php if (in_array($posting['wb_order_id'], $selected)) { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $posting['wb_order_id']; ?>" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $posting['wb_order_id']; ?>" />
						<?php } ?></td>
						<td class="text-left"><?php echo $posting['date_created']; ?></td>
						<td class="text-left"><?php echo $posting['wb_order_id']; ?></td>
						<td class="text-left"><a class="image-popup" href="<?php echo $posting['image']; ?>"><img src="<?php echo $posting['thumb']; ?>"></a></td>
						<td class="text-left"><?php echo $posting['product_name']; ?></td>
						<td class="text-left"><?php echo $posting['price']; ?></td>
						<td class="text-left"><?php echo $posting['status']; ?></td>
						<td class="text-left"><?php echo $posting['user_status']; ?></td>
						<td class="text-left"><?php echo $posting['shipment_date']; ?></td>
						<td class="text-left"><?php echo $posting['supplies']; ?></td>
						<td class="text-left"><?php echo $posting['date_update']; ?></td>
						<td class="text-right">
							<a href="<?php echo $url_sticker . '&order=' . $posting['wb_order_id']; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" title="<?php echo $text_alert_ms_admin; ?>" style="width:40px;"><i class="fa fa-print"></i></a>
							<button type="button" class="btn btn-danger btn-sm delete<?php echo $posting['status'] != 'Новый' ? ' disabled' : false; ?>" value="<?php echo $posting['wb_order_id']; ?>" data-toggle="tooltip" title="<?php echo $text_alert_ms_del; ?>"><i class="fa fa-trash"></i></button>
						</tr>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
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
	var url = 'index.php?route=module/cdl_wildberries/orders&token=<?php echo $token; ?>';
	var filter_wb_order_id = $('input[name=\'filter_wb_order_id\']').val();
	if (filter_wb_order_id) {
		url += '&filter_wb_order_id=' + encodeURIComponent(filter_wb_order_id);
	}

	var filter_shipment_date = $('input[name=\'filter_shipment_date\']').val();
	if (filter_shipment_date) {
		url += '&filter_shipment_date=' + encodeURIComponent(filter_shipment_date);
	}

	var filter_status = $('select[name=\'filter_status\']').val();
	url += '&filter_status=' + encodeURIComponent(filter_status);

	var filter_user_status = $('select[name=\'filter_user_status\']').val();
	if (filter_user_status) {
		url += '&filter_user_status=' + encodeURIComponent(filter_user_status);
	}

	var filter_barcode = $('input[name=\'filter_barcode\']').val();
	if (filter_barcode) {
		url += '&filter_barcode=' + encodeURIComponent(filter_barcode);
	}

	var filter_sticker = $('input[name=\'filter_sticker\']').val();
	if (filter_sticker) {
		url += '&filter_sticker=' + encodeURIComponent(filter_sticker);
	}

	var filter_supplies = $('input[name=\'filter_supplies\']').val();
	if (filter_supplies) {
		url += '&filter_supplies=' + encodeURIComponent(filter_supplies);
	}

	var filter_date_created = $('input[name=\'filter_date_created\']').val();
	if (filter_date_created) {
		url += '&filter_date_created=' + encodeURIComponent(filter_date_created);
	}

	location = url;
});

// К сборке
$(document).on('click', '.packing', function() {
	$.ajax({
		url: '<?php echo $url_packing; ?>',
		type: "POST",
		data: $("#wb_order").serialize() + '&pass=<?php echo $pass; ?>',
		success: function(html) {
			if (html == 'OK') {
				$.ajax({
					url: '<?php echo $url_orders; ?>',
					success: function(html) {
						location.reload();
					}
				});
			} else {
				alert(html);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	  }
	});
});
$('input[name^=\'selected\']').on('change', function() {
	$('.packing').prop('disabled', true);
	var selected = $('input[name^=\'selected\']:checked');
	if (selected.length) {
		$('.packing').prop('disabled', false);
	}
});
$('.packing').prop('disabled', true);
$('input[name^=\'selected\']:first').trigger('change');

$(document).on('click', '.delete', function() {
	var wb_order_id = $(this).attr('value');
	$.ajax({
		url: '<?php echo $url_delete; ?>' + '&posting=' + wb_order_id,
		success: function(data) {
			$('#' + wb_order_id).remove();
		},
	  error: function(xhr, ajaxOptions, thrownError) {
	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	  }
	});
});

$('.date').datetimepicker({
	pickTime: false
});

$('.image-popup').magnificPopup({
	type: 'image'
});

$(document).keypress(function (e) {
  if (e.which == 13) {
    document.getElementById('button-filter').click();
  }
});

//--></script>
