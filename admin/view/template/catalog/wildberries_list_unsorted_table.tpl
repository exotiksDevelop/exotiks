<table class="table table-striped" id="<?= $store['wb_uuid'];?>">
  <thead>
    <tr>
      <th scope="col">#</th>
	  <th scope="col">Название</th>
      <th scope="col"><?= $compare_field;?></th>
      <th scope="col">nmId</th>
      <th scope="col">chrtId</th>
	  <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
	<?php $productData = $products[$store['wb_uuid']]['items'];?>
	<?php $productTotal = $products[$store['wb_uuid']]['total'];?>
	<?php if (empty($productData)):?>
		<tr>
			<th colspan="5" style="text-align: center;">Данные отсутствуют</th>
		</tr>
	<?php endif;?>
	<?php foreach($productData as $key => $item):?>
		<tr>
			<th scope="row"><?= $offset + ($key + 1);?></th>
			<td><?= $item['wb_name'];?></td>
			<td><?= $item['vendor_code'];?></td>
			<td><?= $item['nmId'];?></td>
			<td><?= $item['chrtId'];?></td>
			<td>
				<button type="button" onclick="handleOnclick('<?=$store['wb_uuid'];?>', '<?= $item['id'];?>')" data-id="" class="btn btn-light" data-toggle="modal" data-target="#link_popup_<?= $store['wb_uuid'];?>">Назначить товар</button>
			</td>
		</tr>
	<?php endforeach;?>
  </tbody>
</table>
<?php $to = ceil($productTotal / $product_limit);?>
<?php if ($to > 1):?>
<?php $offset_cursor = $offset == 0 ? 0 : $offset / $product_limit;?>
<nav class="pagination-wrapper" data-total="<?= $to;?>">
	<ul class="pagination product_table">
		<li class="page-item <?= $offset_cursor == 0 ? 'disabled' : '' ;?>" data-offset="0">
			<a class="page-link" href="#" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			</a>
		</li>
		<?php for($i = 0; $i < $to; $i++):?>
			<li class="page-item <?= $i == $offset_cursor ? 'disabled' : '';?>" data-offset="<?= $i;?>"><a class="page-link" href="#"><?= $i + 1;?></a></li>
		<?php endfor;?>
		<li class="page-item <?= $i - 1 == $offset_cursor ? 'disabled' : '' ;?>" data-offset="<?= $i - 1;?>">
			<a class="page-link" href="#" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			</a>
		</li>
	</ul>
</nav>
<?php endif;?>
<div id="link_popup_<?= $store['wb_uuid'];?>" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="width: 1140px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Выберите товар из списка</h4>
			</div>
			<div class="modal-body common_body">
				<div class="row filters">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name">Название</label>
							<input type="text" name="product_filter_name" value="" placeholder="Название" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name">Артикул</label>
							<input type="text" name="product_filter_sku" value="" placeholder="Артикул" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name">Модель</label>
							<input type="text" name="product_filter_model" value="" placeholder="Артикул" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group" style="display: flex;flex-direction: column;">
							<label class="control-label" style="visibility: hidden;" for="input-name">Артикул</label>
							<input type="submit" onclick="searchProduct(event)" class="btn btn-primary" value="Поиск" placeholder="Артикул" class="form-control" /> 
						</div>
					</div>
				</div>
				<div class="row beofre">
					<div class="col-12 oc_product_table_wrapper">
						<?php include DIR_APPLICATION . implode(DIRECTORY_SEPARATOR, ['view', 'template', 'catalog', 'wildberries_list_unsorted_table_product.tpl']);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('.product_table a').on('click', function (event) {
	event.preventDefault();
	var pageItem = $(event.target).closest('.page-item');
	if (!pageItem.hasClass('disabled')) {
		var wrapper = pageItem.closest('.oc_product');
		var offset = pageItem.data('offset');
		wrapper.find('.page-item').removeClass('disabled');
		var filter = $(event.target).closest('.tab-pane').find('.filters-wb');
		var filter_name = filter.find('[name="wb_product_filter_name"]').val();
		var filter_compare_field = filter.find('[name="wb_product_filter_compare_field"]').val();
		var filter_nmId = filter.find('[name="wb_product_filter_nmId"]').val();
		$.ajax({
			url: "<?= htmlspecialchars_decode($paginate_product_url); ?>",
			method: "POST",
			data: { store_id: wb_store_id, offset: +offset == 0 ? offset : offset * <?= $product_limit;?>, filter_data: {filter_name: filter_name, filter_compare_field: filter_compare_field, filter_nmId: filter_nmId} },
			success: function(data) {
				$('table#' + wb_store_id).closest('.wb_product_table_wrapper').html(data);
				wrapper.find('[data-offset=' + offset + ']').addClass('disabled');
			}
		});
	}
});

</script>
<style>
.pagination-wrapper {
	width: 100%;
	display: flex;
	justify-content: center;
}
.beofre {
	margin: 0;
}
</style>
