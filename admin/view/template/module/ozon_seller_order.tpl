<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $url_general; ?>"><?php echo $text_edit; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $product; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_export; ?>"><?php echo $text_export; ?></a></li>
				  </ul>
				</div>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
						<label class="control-label"><?php echo $text_posting_number; ?></label>
						<input type="text" name="filter_posting_number" value="<?php if ($filter_posting_number) echo $filter_posting_number; ?>" placeholder="<?php echo $text_posting_number; ?>" class="form-control" />
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_status; ?></label>
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
				</div>
				<!-- <div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $text_barcode; ?></label>
						<input type="text" value="" placeholder="<?php echo $text_barcode; ?>" id="filter_barcode" class="form-control" />
					</div>
				</div> -->
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label"><?php echo $entry_shipment_date; ?></label>
						<div class="input-group date">
							<input type="text" name="filter_shipment_date" value="<?php if ($filter_shipment_date) echo $filter_shipment_date; ?>" placeholder="<?php echo $entry_shipment_date; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
							<span class="input-group-btn">
							<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span></div>
					</div>
					<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<form id="ozon_order" action="" method="post">
		<table id="postings" class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
					<td style="width:240px;" class="text-left"><?php if ($sort == 'posting_number') { ?>
					<a href="<?php echo $sort_posting_number; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_posting_number; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_posting_number; ?>"><?php echo $text_posting_number; ?></a>
					<?php } ?></td>
					<td class="text-left"><?php if ($sort == 'status') { ?>
					<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
					<?php } ?></td>
					<td style="width:150px;" class="text-left"><?php if ($sort == 'shipment_date') { ?>
					<a href="<?php echo $sort_shipment_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_shipment_date; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_shipment_date; ?>"><?php echo $entry_shipment_date; ?></a>
					<?php } ?></td>
					<td style="width:190px;" class="text-right"></td>
				</tr>
			</thead>
			<tbody>

				<?php if ($postings) { ?>
					<?php foreach ($postings as $posting) { ?>
						<tr id="<?php echo $posting['posting_number']; ?>">
						<td class="text-center"><?php if (in_array($posting['posting_number'], $selected)) { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $posting['posting_number']; ?>" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $posting['posting_number']; ?>" />
						<?php } ?></td>
						<td class="text-left"><?php echo $posting['posting_number']; ?></td>
						<td class="text-left"><?php echo $posting['status']; ?></td>
						<td class="text-left"><?php echo $posting['shipment_date']; ?></td>
						<td class="text-right">
							<button type="button" class="btn btn-primary btn-sm view" value="<?php echo $posting['posting_number']; ?>" style="width:40px;"><i class="fa fa-eye"></i></button>
							<button type="button" class="btn btn-success btn-sm packing<?php echo $posting['status'] != 'Ожидает сборки' ? ' disabled' : false; ?>" value="<?php echo $posting['posting_number']; ?>" style="width:40px;" data-id-packing="<?php echo $posting['posting_number']; ?>"><i class="fa fa-cubes"></i></button>
							<a href="/index.php?route=module/ozon_seller/printsticker&post=<?php echo $posting['posting_number']; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" title="<?php echo $text_alert_ms_admin; ?>" style="width:40px;"><i class="fa fa-print"></i></a>
							<button type="button" class="btn btn-danger btn-sm delete<?php echo $posting['status'] != 'Ожидает сборки' ? ' disabled' : false; ?>" value="<?php echo $posting['posting_number']; ?>" data-id-delete="<?php echo $posting['posting_number']; ?>" data-toggle="tooltip" title="<?php echo $text_alert_ms_del; ?>"><i class="fa fa-trash"></i></button>
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
	var url = 'index.php?route=module/ozon_seller/order&token=<?php echo $token; ?>';

	var filter_posting_number = $('input[name=\'filter_posting_number\']').val();

	if (filter_posting_number) {
		url += '&filter_posting_number=' + encodeURIComponent(filter_posting_number);
	}

	var filter_shipment_date = $('input[name=\'filter_shipment_date\']').val();

	if (filter_shipment_date) {
		url += '&filter_shipment_date=' + encodeURIComponent(filter_shipment_date);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});

$(document).on('click', '.view', function() {
	var posting_number = $(this).attr('value');
	if ($("tr[data-id=\'" + posting_number + "\']").data()) {
		$("tr[data-id=\'" + posting_number + "\']").remove();
	} else {
		$.ajax({
			url: '/index.php?route=module/ozon_seller/getorderozonadmin&cron_pass=<?php echo $ozon_seller_cron_pass; ?>&posting=' + posting_number,
			success: function(respon) {
				var posting = $.parseJSON(respon);
				$.ajax({
					url: 'index.php?route=module/ozon_seller/vieworder&token=<?php echo $token; ?>&postingnumber=' + posting_number,
					type: 'POST',
					data: { posting:posting },
					success: function(data) {
						$('#' + posting_number).after(data);
						init_magnificPopup();
					}
				});
			},
		  error: function(xhr, ajaxOptions, thrownError) {
		    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		  }
		});
	}
});

$(document).on('click', '.packing', function() {
	var posting_number = $(this).attr('value');
	$.ajax({
		url: '/index.php?route=module/ozon_seller/packorderadmin&cron_pass=<?php echo $ozon_seller_cron_pass; ?>&posting=' + posting_number,
		success: function(respon) {
			$("button[data-id-packing=\'" + posting_number + "\']").addClass('disabled');
			$("button[data-id-delete=\'" + posting_number + "\']").addClass('disabled');
			console.log(respon);
			if (respon == 'Отправление уже было собрано') {
				alert('Отправление уже было собрано');
			}
		},
	  error: function(xhr, ajaxOptions, thrownError) {
	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	  }
	});
});

$(document).on('click', '.delete', function() {
	var posting_number = $(this).attr('value');
	$.ajax({
		url: '/index.php?route=module/ozon_seller/deleteorder&cron_pass=<?php echo $ozon_seller_cron_pass; ?>&posting=' + posting_number,
		success: function(data) {
			$('#' + posting_number).remove();
		},
	  error: function(xhr, ajaxOptions, thrownError) {
	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
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

$('.date').datetimepicker({
	pickTime: false
});

function init_magnificPopup(){
	$('.image-popup').magnificPopup({
		type: 'image'
	});
}
//--></script>
