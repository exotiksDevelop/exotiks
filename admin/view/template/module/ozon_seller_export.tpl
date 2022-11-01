<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<?php if (!empty($error)) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
			<?php if (!empty($success)) { ?>
				<div class="alert alert-success alert-dismissible" role="alert"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			<?php } ?>
			<div class="pull-right">
				<div class="btn-group">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $text_menu; ?>&nbsp;<span class="caret"></span></button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
						<li><a href="<?php echo $url_general; ?>"><?php echo $text_edit; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_order; ?>"><?php echo $order_ozon; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $product; ?></a></li>
				  </ul>
				</div>
				<?php echo $btn_export; ?>
			</div>
			<h1><?php echo $heading_title_export; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<select class="form-control category" style="margin-bottom:5px;">
			<?php foreach ($categorys as $category) { ?>
				<?php if ($filter_category == $category['category_id']) { ?>
					<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
				<?php } else { ?>
					<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
		<div class="table-responsive">
			<?php if (!empty($items)) { ?>
				<?php foreach ($items as $item) { ?>
					<div class="col-xs-12" style="background-color:#6696ed;font-size:1.8em;color:white;padding-bottom:5px;"><?php echo $item['category']; ?></div>
					<table class="table table-sm table-striped table-bordered">
						<thead style="position:sticky;top:0;background-color:#ededed61;">
							<tr>
								<?php foreach ($item['products'][0] as $key => $attribute_name) { ?>
									<td><?php echo $key; ?></td>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($item['products'] as $products) { ?>
								<tr>
								<?php foreach ($products as $product) { ?>
									<?php if ($product == 'error') { ?>
										<td style="background-color:#ff9a9a;"></td>
									<?php } elseif (empty($product)) { ?>
										<td style="background-color:#fdfdc1;"></td>
									<?php } else { ?>
										<td><?php echo $product; ?></td>
									<?php } ?>
								<?php } ?>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
			<?php } else { ?>
				<?php echo $text_no_products; ?>
			<?php } ?>
		</div>
	</div>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--
// Изменение категории
$('.category').change(function() {
	var category = $(".category option:selected").val();
	var url = '<?php echo $url_export; ?>' + encodeURIComponent(category);
	location = url;
});

// Кнопка экспорта
$('.btn-export').on('click', function() {
	var category = $('.category').val();
	$.ajax({
		url: '<?php echo $url_product_export; ?>' + category,
		beforeSend: function() {
			$('.btn-export').button('loading');
		},
		success: function(html) {
			$('.table-responsive').replaceWith('<div class="alert alert-info" role="alert">' + html + '</div>');
			$('.btn-export').button('reset');
			$('.btn-export').prop('disabled', true);
		}
	});
});
//--></script>
