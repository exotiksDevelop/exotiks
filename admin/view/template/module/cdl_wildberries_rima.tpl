<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<?php if ($error_warning) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
			<?php if ($success) { ?>
				<div class="alert alert-success alert-dismissible" role="alert"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			<?php } ?>
			<div class="pull-right">
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $cancel; ?>"><?php echo $button_return_module; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_orders_wb; ?>"><?php echo $text_orders_wb; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $text_product; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_attributes; ?>"><?php echo $text_attributes; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_supplies; ?>" target="_blank"><?php echo $text_supplies; ?></a></li>
				  </ul>
				</div>
				<?php echo $button_export; ?>
			</div>

			<h1><?php echo $heading_title_rima; ?></h1>

			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>

			<div class="well">
				<div class="row">
					<div class="col-sm-1">
						<input class="form-control" placeholder="<?php echo $text_start; ?>" name="filter-start" value="<?php echo $filter_start; ?>" />
					</div>
					<div class="col-sm-1">
						<select class="form-control" name="filter-limit">
							<?php foreach ($export_limit as $limit) { ?>
							<?php if ($filter_limit == $limit) { ?>
								<option value="<?php echo $limit; ?>" selected="selected"><?php echo $limit; ?></option>
							<?php } else { ?>
								<option value="<?php echo $limit; ?>"><?php echo $limit; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-4">
						<select class="form-control" name="filter-category">
							<?php foreach ($rima_categorys as $rima_category) { ?>
								<?php if ($filter_category == $rima_category) { ?>
									<option selected="selected"><?php echo $rima_category; ?></option>
								<?php } else { ?>
									<option><?php echo $rima_category; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-3">
						<select class="form-control" name="filter-manufacturer">
							<?php foreach ($manufacturers as $manufacturer) { ?>
								<?php if ($filter_manufacturer == $manufacturer['manufacturer_id']) { ?>
									<option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-2">
						<button type="button" id="button-filter" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
					</div>
					<div class="col-sm-1">
						<a href="<?php echo $url_rima; ?>" type="button" class="btn btn-danger btn-block"><i class="fa fa-eraser"></i></a>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<?php if (!empty($products)) { ?>
				<?php foreach ($products as $product) { ?>
				<div class="col-xs-12" style="background-color:#6696ed;font-size:1.7em;color:white;padding-bottom:5px;"><?php echo $product['product'][0]['category']; ?></div>
        <table class="table table-sm table-striped table-bordered">
          <thead>
            <tr>
              <td style="min-width:250px;"><?php echo $column_name; ?></td>
              <td><?php echo $text_vendor_code; ?></td>
              <td><?php echo $text_brend; ?></td>
              <td><?php echo $text_country; ?></td>
              <td><?php echo $text_barcode; ?></td>
              <td><?php echo $text_price; ?></td>
							<td><?php echo $text_description; ?></td>
							<?php foreach ($product['attr_wb'] as $attr_wb) { ?>
								<td><?php echo $attr_wb['type'] . ' ' . $attr_wb['units']; ?></td>
							<?php } ?>
            </tr>
          </thead>
					<tbody>
						<?php foreach ($product['product'] as $rima_product) { ?>
							<tr>
								<td style="position:relative">
									<div data-name="<?php echo $rima_product['product_id']; ?>"><?php echo $rima_product['name']; ?></div>
									<div data-name-btn="<?php echo $rima_product['product_id']; ?>" style="position:absolute;right:0px;bottom:0px;">
										<button type="button" class="btn btn-sm pull-right change" data-toggle="tooltip" title="<?php echo $text_replace_name; ?>" data-change-name="<?php echo $rima_product['product_id']; ?>" style="padding:1px 5px;"><i class="fa fa-pencil"></i></button>
									</div>
								</td>
								<td <?php echo empty($rima_product['vendor_code']) ? 'style="background-color:#ff00009e;color:white;"' : false ; ?>><?php echo $rima_product['vendor_code']; ?></td>
								<td <?php echo empty($rima_product['brend']) ? 'style="background-color:#ff00009e;color:white;position:relative;"' : 'style="position:relative;"'; ?>>
									<?php echo empty($rima_product['brend']) ? $text_not_matched : $rima_product['brend']; ?>
									<div style="position:absolute;right:0px;bottom:0px;">
										<button type="button" class="btn btn-sm pull-right manufacturer-set" style="padding:1px 5px;"><i class="fa fa-pencil"></i></button>
									</div>
								</td>
								<td <?php echo empty($rima_product['country']) ? 'style="background-color:#ff00009e;color:white;"' : false ; ?>><?php echo $rima_product['country']; ?></td>
								<td <?php echo empty($rima_product['barcode']) ? 'style="background-color:#ff00009e;color:white;"' : false ; ?>><?php echo $rima_product['barcode']; ?></td>
								<td <?php echo empty($rima_product['price']) ? 'style="background-color:#ff00009e;color:white;"' : false ; ?>><?php echo $rima_product['price']; ?></td>
								<td><?php echo $rima_product['description']; ?></td>
								<?php foreach ($rima_product['attributes'] as $attributes) { ?>
									<td <?php if (!empty($attributes['required']) && $attributes['required'] == '&nbsp;') {echo 'style="background-color:#ff00009e;color:white;"';} elseif (isset($attributes['required']) || empty($attributes[0])) {echo 'style="background-color:#fdfdc1;"';} ?>>
									<?php foreach ($attributes as $attribute) { ?>
										<?php echo $attribute . '<br />'; ?>
									<?php } ?>
									</td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php } ?>
			<?php } else { ?>
					<?php echo $text_export_no_product; ?>
				<?php } ?>
			</div>
    </div>
  </div>
	<!-- <pre>
	<? print_r($products); ?> -->
</div>


<?php echo $footer; ?>

<script type="text/javascript"><!--
// Экспорт товара
$('.export').on('click', function() {
	var category = $('select[name=\'filter-category\']').val();
	var start = $('input[name=\'filter-start\']').val();
	var limit = $('select[name=\'filter-limit\']').val();
	var manufacturer = $('select[name=\'filter-manufacturer\']').val();
	$.ajax({
		url: '<?php echo $url_product_export; ?>' + category + '&limit=' + limit + '&start=' + start + '&manufacturer=' + manufacturer,
		type: 'post',
		data: 'pass=<?php echo $cdl_wildberries_pass; ?>',
		beforeSend: function() {
			$('.export').button('loading');
		},
		success: function(html) {
			$('.table-responsive').replaceWith('<div class="alert alert-info" role="alert">' + html + '</div>');
			$('.export').button('reset');
			$('.export').prop('disabled', true);
		}
	});
});

// Изменить наименование товара
$(document).on('click', '.change', function() {
	var product_id = $(this).attr('data-change-name');
	var name = $("[data-name='" + product_id +"']").text();
	var href = $("[data-name='" + product_id +"']").html();
	$("[data-name='" + product_id +"']").html('<textarea data-name-new="' + product_id + '" rows="4" cols="40">' + name + '</textarea><div style="display:none;" data-name-hidden="' + product_id + '">' + href + '</div>');
	$("[data-name-btn='" + product_id +"']").html('<button type="button" class="btn btn-success btn-sm pull-right save" data-name-save="' + product_id + '"><i class="fa fa-check"></i></button><button type="button" class="btn btn-danger btn-sm pull-right cancel" data-name-cancel="' + product_id + '"><i class="fa fa-ban"></i></button>');
});
$(document).on('click', '.cancel', function() {
	var product_id = $(this).attr('data-name-cancel');
	var text_replace_name = '<?php echo $text_replace_name; ?>';
	$("[data-name='" + product_id +"']").html($("[data-name-hidden='" + product_id +"']").html());
	$("[data-name-btn='" + product_id +"']").html('<button type="button" class="btn btn-sm pull-right change" data-toggle="tooltip" title="' + text_replace_name + '" data-change-name="' + product_id + '"><i class="fa fa-pencil"></i></button>');
});
$(document).on('click', '.save', function() {
	var product_id = $(this).attr('data-name-save');
	var name_new = $("[data-name-new='" + product_id +"']").val();
	var text_replace_name = '<?php echo $text_replace_name; ?>';
	$.ajax({
		url: '<?php echo $url_change_name; ?>&name=' + name_new + '&product_id=' + product_id,
		success: function(html) {
			$("[data-name='" + product_id +"']").html(html);
			$("[data-name-btn='" + product_id +"']").html('<button type="button" class="btn btn-sm pull-right change" data-toggle="tooltip" title="' + text_replace_name + '" data-change-name="' + product_id + '"><i class="fa fa-pencil"></i></button>');
		}
	});
});

// Вызываем модальное
$(function() {
	var myModal = new ModalApp.ModalProcess({ id: 'myModal'});
	myModal.init();
	// Вызываем модальное производителей
	$('.manufacturer-set').on('click', function(e) {
		e.preventDefault();
		$.get('index.php?route=module/cdl_wildberries/manufacturerset&token=<?php echo $token; ?>',
			function(data) {
			var data = JSON.parse(data);
			myModal.changeTitle(data['title']);
			myModal.changeBody(data['body']);
			myModal.changeFooter(data['footer']);
			myModal.showModal();
		});
	});
});

// SCRIPT MODAL
var ModalApp = {};
ModalApp.ModalProcess = function (parameters) {
	this.id = parameters['id'] || 'modal';
	this.selector = parameters['selector'] || '';
	this.title = parameters['title'] || 'Заголовок модального окна';
	this.body = parameters['body'] || 'Содержимое модального окна';
	this.footer = parameters['footer'] || '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';
	this.content = '<div id="'+this.id+'" class="modal fade" tabindex="-1" role="dialog">'+
		'<div class="modal-dialog" role="document" style="width:70%;">'+
			'<div class="modal-content">'+
				'<div class="modal-header">'+
					'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<h4 class="modal-title">'+this.title+'</h4>'+
				'</div>'+
				'<div class="modal-body">'+this.body+'</div>'+
				'<div class="modal-footer">'+this.footer+'</div>'+
			'</div>'+
		'</div>'+
	'</div>';
	this.init = function() {
		if ($('#'+this.id).length==0) {
			$('body').prepend(this.content);
		}
		if (this.selector) {
			$(document).on('click',this.selector, $.proxy(this.showModal,this));
		}
	}
}
ModalApp.ModalProcess.prototype.changeTitle = function(content) {
	$('#' + this.id + ' .modal-title').html(content);
};
ModalApp.ModalProcess.prototype.changeBody = function(content) {
	$('#' + this.id + ' .modal-body').html(content);
};
ModalApp.ModalProcess.prototype.changeFooter = function(content) {
	$('#' + this.id + ' .modal-footer').html(content);
};
ModalApp.ModalProcess.prototype.showModal = function() {
	$('#' + this.id).modal('show');
};
ModalApp.ModalProcess.prototype.hideModal = function() {
	$('#' + this.id).modal('hide');
};
ModalApp.ModalProcess.prototype.updateModal = function() {
	$('#' + this.id).modal('handleUpdate');
};

// Фильтр
$('#button-filter').on('click', function() {
	var url = '<?php echo $url_rima; ?>';
	var filter_limit = $('select[name=\'filter-limit\']').val();
	url += '&filter_limit=' + encodeURIComponent(filter_limit);
	var filter_start = $('input[name=\'filter-start\']').val();
	url += '&filter_start=' + encodeURIComponent(filter_start);
	var filter_category = $('select[name=\'filter-category\']').val();
	url += '&filter_category=' + encodeURIComponent(filter_category);
	var filter_manufacturer = $('select[name=\'filter-manufacturer\']').val();
	url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);
	location = url;
});

// Фильтр по Enter
$(document).keypress(function (e) {
  if (e.which == 13) {
    document.getElementById('button-filter').click();
  }
});

//--></script>
