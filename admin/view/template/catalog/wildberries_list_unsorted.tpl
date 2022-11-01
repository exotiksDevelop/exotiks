<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
	  </div>
	</div>
	<div class="container-fluid" style="display: flex;min-height: 70vh;">
		<ul id="wb_auth_nav_pills" class="nav nav-pills nav-stacked col-md-2">
			<?php foreach ($stores as $idx => $store) : ?>
			<li class="auth_tabs <?= $idx == 0 ? 'active': ''; ?>" data-store="<?= $store['wb_uuid'];?>">
				<a href="#tab_<?= $idx;?>" data-toggle="tab"><?= $store['wb_store_name'];?></a>
			</li>
			<?php endforeach;?>
		</ul>
		<div class="tab-content" style="width: 100%;">
			<?php foreach ($stores as $idx => $store) :?>
			<div class="tab-pane <?= $idx == 0 ? 'active' : ''; ?>" id="tab_<?= $idx;?>">
				<div class="filters-wb"> 
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name">Название</label>
							<input type="text" name="wb_product_filter_name" value="" placeholder="Название" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name"><?= $compare_field;?></label>
							<input type="text" name="wb_product_filter_compare_field" value="" placeholder="Артикул" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name">nmId</label>
							<input type="text" name="wb_product_filter_nmId" value="" placeholder="nmId" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group" style="display: flex;flex-direction: column;">
							<label class="control-label" style="visibility: hidden;" for="input-name">Артикул</label>
							<input type="submit" onclick="searchWBProduct(event)" class="btn btn-primary" value="Поиск" placeholder="Артикул" class="form-control" /> 
						</div>
					</div>
				</div>
				<div class="wb_product_table_wrapper">
					<?php include DIR_APPLICATION . implode(DIRECTORY_SEPARATOR, ['view', 'template', 'catalog', 'wildberries_list_unsorted_table.tpl']);?>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'product_filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/wildberries/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});
//--></script>
</div>
<script>
var wb_store_id = 0;
var wb_id = 0;
function handleOnclick(store_id, iId) {
	wb_store_id = store_id;
	wb_id = iId;
}
function handleSlick(product_id) {
	if(confirm("Вы действительно хотите прикрепить к этому товару?")) {
		$.ajax({
			url: "<?= htmlspecialchars_decode($attach_url); ?>",
			method: "POST",
			data: { store_id: wb_store_id, wb_id: wb_id, product_id: product_id },
			success: function(data) {
				$('table#' + wb_store_id).closest('.wb_product_table_wrapper').html(data);
				$('body').removeClass('modal-open');
				$('.modal-backdrop.in').remove();
			}
		});
	}
}
function searchProduct(event) {
	event.preventDefault();
	var filter = $(event.target).closest('.filters');
	var filter_name = filter.find('[name="product_filter_name"]').val();
	var filter_sku = filter.find('[name="product_filter_sku"]').val();
	var filter_model = filter.find('[name="product_filter_model"]').val();
	var wrapper = filter.closest('.common_body');
	$.ajax({
		url: "<?= htmlspecialchars_decode($paginate_url); ?>",
		method: "POST",
		data: { store_id: wb_store_id, offset: 0, filter_data: {filter_name: filter_name, filter_sku: filter_sku, filter_model: filter_model} },
		success: function(data) {
			wrapper.find('.oc_product_table_wrapper').html(data);
		}
	});
}
function searchWBProduct(event) {
    var filter = $(event.target).closest('.filters-wb');
	var filter_name = filter.find('[name="wb_product_filter_name"]').val();
	var filter_compare_field = filter.find('[name="wb_product_filter_compare_field"]').val();
	var filter_nmId = filter.find('[name="wb_product_filter_nmId"]').val();
	var wrapper = filter.closest('.tab-pane');
	$.ajax({
		url: "<?= htmlspecialchars_decode($paginate_product_url); ?>",
		method: "POST",
		data: { store_id: wb_store_id, offset: 0, filter_data: {filter_name: filter_name, filter_compare_field: filter_compare_field, filter_nmId: filter_nmId} },
		success: function(data) {
			wrapper.find('.wb_product_table_wrapper').html(data);
		}
	});
}
$(document).ready(function() {
	wb_store_id = $('.auth_tabs.active').data('store');
	$('.auth_tabs a').on('click', function (event) {
		var current = $(event.target).closest('.auth_tabs');
		wb_store_id = current.data('store');
	});
})
</script>
<?php echo $footer; ?>
