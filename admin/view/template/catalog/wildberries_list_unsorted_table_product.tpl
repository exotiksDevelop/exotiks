<table class="table oc_product_table table-striped">
	<thead>
		<tr>
			<th scope="col">Изображение</th>
			<th scope="col">Название</th>
			<th scope="col">Артикул</th>
			<th scope="col">Модель</th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
		<?php $ocProductData = $oc_products[$store['wb_uuid']]['items'];?>
		<?php $ocProductTotal = $oc_products[$store['wb_uuid']]['total'];?>
		<?php foreach($ocProductData as $ocproduct):?>
			<tr>
				<td>
					<img src="<?= $ocproduct['image'];?>" />
				</td>
				<td><?= $ocproduct['name'];?></td>
				<td><?= $ocproduct['sku'];?></td>
				<td><?= $ocproduct['model'];?></td>
				<td style="display: flex;justify-content: center;">
					<button type="button" onclick="handleSlick('<?= $ocproduct['product_id'];?>')" data-id="" class="btn btn-light">Назначить товар</button>
				</td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php $tot = ceil($oc_products[$store['wb_uuid']]['total'] / $oc_product_limit);?>
<?php if ($tot > 1):?>
<?php $oc_offset_cursor = $oc_offset == 0 ? 0 : $oc_offset / $oc_product_limit;?>
<?php $visible = [0, 1, 2, $oc_offset_cursor - 1, $oc_offset_cursor, $oc_offset_cursor + 1, $tot - 3, $tot - 2, $tot -1]?>
	<nav class="pagination-wrapper" data-total="<?= $tot;?>">
		<ul class="pagination oc_product">
			<li class="page-item <?= $oc_offset_cursor == 0 ? 'disabled' : '' ;?>" data-offset="0">
				<a class="page-link" href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">Previous</span>
				</a>
			</li>
			<?php for($i = 0; $i < $tot; $i++):?>
				<li <?= !in_array($i, $visible) ? 'style="display: none;"' : '' ?> class="page-item <?= $i == $oc_offset_cursor ? 'disabled' : '';?>" data-offset="<?= $i;?>"><a class="page-link" href="#"><?= $i + 1;?></a></li>
			<?php endfor;?>
			<li class="page-item <?= $i - 1 == $oc_offset_cursor ? 'disabled' : '' ;?>" data-offset="<?= $i - 1;?>">
				<a class="page-link" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
				</a>
			</li>
		</ul>
	</nav>
<?php endif;?>
<script>
$('.oc_product a').on('click', function (event) {
	event.preventDefault();
	var pageItem = $(event.target).closest('.page-item');
	if (!pageItem.hasClass('disabled')) {
		var wrapper = pageItem.closest('.oc_product');
		var offset = pageItem.data('offset');
		wrapper.find('.page-item').removeClass('disabled');
		var filter = $(event.target).closest('.common_body').find('.filters');
		var filter_name = filter.find('[name="product_filter_name"]').val();
		var filter_sku = filter.find('[name="product_filter_sku"]').val();
		var filter_model = filter.find('[name="product_filter_model"]').val();
		$.ajax({
			url: "<?= htmlspecialchars_decode($paginate_url); ?>",
			method: "POST",
			data: { store_id: wb_store_id, offset: +offset == 0 ? offset : offset * <?= $oc_product_limit;?>, filter_data: {filter_name: filter_name, filter_sku: filter_sku, filter_model: filter_model} },
			success: function(data) {
				wrapper.closest('.oc_product_table_wrapper').html(data);
				wrapper.find('[data-offset=' + offset + ']').addClass('disabled');
			}
		});
	}
});
</script>
