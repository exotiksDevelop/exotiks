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
						<li><a href="<?php echo $url_order; ?>"><?php echo $order_ozon; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $product; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_export; ?>"><?php echo $text_export; ?></a></li>
				  </ul>
				</div>
				<?php echo $button_download_attr; ?>
				<button type="submit" form="form-ozon-seller" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<div class="log_process"></div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ozon-seller" class="form-horizontal">

					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-category" data-toggle="tab"><?php echo $tab_category; ?></a></li>
						<li><a href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
						<li><a href="#tab-manufacturer" data-toggle="tab"><?php echo $tab_manufacturer; ?></a></li>
						<li><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
						<li><a href="#tab-price" data-toggle="tab"><?php echo $tab_price; ?></a></li>
						<li><a href="#tab-warehouse" data-toggle="tab"><?php echo $tab_warehouse; ?></a></li>
						<li><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
						<li><a href="#tab-moysklad" data-toggle="tab"><?php echo $tab_moysklad; ?></a></li>
						<li><a href="#tab-fbo" data-toggle="tab"><?php echo $tab_fbo; ?></a></li>
						<li><a href="#tab-cron" data-toggle="tab"><?php echo $tab_cron; ?></a></li>
						<li><a href="#tab-about" data-toggle="tab"><?php echo $tab_about; ?></a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-client"><?php echo $entry_client_id; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_client_id" value="<?php echo $ozon_seller_client_id; ?>" id="input-client-id" class="form-control">
									<?php if ($error_client_id) { ?>
										<div class="text-danger"><?php echo $error_client_id; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-api-key"><?php echo $entry_api_key; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_api_key" value="<?php echo $ozon_seller_api_key; ?>" id="input_api_key" class="form-control">
									<?php if ($error_api_key) { ?>
										<div class="text-danger"><?php echo $error_api_key; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-cron-pass"><?php echo $cron_pass; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_cron_pass" value="<?php echo $ozon_seller_cron_pass; ?>" class="form-control">
									<?php if ($error_cron_pass) { ?>
										<div class="text-danger"><?php echo $error_cron_pass; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_offer_id; ?><span data-toggle="tooltip" title="<?php echo $help_entry_offer_id; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_entry_offer_id" class="form-control">
										<?php foreach ($relations as $key => $relation) { ?>
											<?php if ($ozon_seller_entry_offer_id == $key) { ?>
												<option value="<?php echo $key; ?>" selected="selected"><?php echo $relation; ?></option>
											<?php } else { ?>
												<option value="<?php echo $key; ?>"><?php echo $relation; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-weight-class"><?php echo $entry_g; ?></label>
								<div class="col-sm-4">
									<select name="ozon_seller_weight" id="input-weight-class" class="form-control">
										<?php foreach ($weight_classes as $weight_class) { ?>
		                	<?php if ($weight_class['weight_class_id'] == $ozon_seller_weight) { ?>
			            		<option value="<?php echo $ozon_seller_weight; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
			            	<?php } else { ?>
			            		<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
											<?php } ?>
                    <?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-length-class"><?php echo $entry_sm; ?></label>
								<div class="col-sm-4">
									<select name="ozon_seller_length" id="input-length-class" class="form-control">
                    <?php foreach ($length_classes as $length_class) { ?>
                    	<?php if ($length_class['length_class_id'] == $ozon_seller_length) { ?>
                    		<option selected="selected" value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                    	<?php } else { ?>
                    		<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                    	<?php } ?>
                    <?php } ?>
                </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_act; ?><span data-toggle="tooltip" title="<?php echo $help_act; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_act" class="form-control">
										<?php if ($ozon_seller_act) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-4">
									<select name="ozon_seller_status" id="input-status" class="form-control">
										<?php if ($ozon_seller_status) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="panel-heading">
									<h3 class="panel-title"><i class="fa fa-file-text-o"></i> <?php echo $text_log; ?></h3>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<textarea wrap="off" rows="15" readonly class="form-control"><?php echo $log; ?></textarea>
									<a href='<?php echo $clear; ?>' data-toggle="tooltip" class="btn btn-danger btn-lg btn-block"><i class="fa fa-eraser"></i></a>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-category">

							<div class="form-group">
								<div class="table-responsive" style="padding:0 10px;">
									<table id="category" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td style="width:31%;"><?php echo $category_shop; ?></td>
												<td><?php echo $category_ozon; ?><span data-toggle="tooltip" title="<?php echo $help_default_size_ozon; ?>"></span></td>
												<td></td>
											</tr>
										</thead>
										<tbody>
              			<?php $category_row = 0; ?>
              			<?php if ($ozon_seller_category) { ?>
											<?php foreach ($ozon_seller_category as $category_in) { ?>
												<tr id="category_row<?php echo $category_row; ?>">
													<td>
														<div class="alert alert-info">
															<?php foreach ($categories as $category) { ?>
																<?php echo ($category_in['shop'] == $category['category_id'] ? '(id ' . $category_in['shop'] . ') ' . $category['name'] : false); ?>
															<?php } ?>
														</div>
														<input type="hidden" name="ozon_seller_category[<?php echo $category_row; ?>][shop]" value="<?php echo $category_in['shop']; ?>" />

														<?php if (!empty($dictionarys_ozon)) { ?>
															<div class="input-group">
															<span class="input-group-addon"><?php echo $text_type; ?></span>
															<select name="ozon_seller_category[<?php echo $category_row; ?>][type]" class="form-control">
																<option value="0"></option>
															<?php foreach ($dictionarys_ozon as $dictionary_ozon) { ?>
																<?php if ($dictionary_ozon['category_id'] == $category_in['ozon']) { ?>
																	<?php if (isset($category_in['type']) && $category_in['type'] == $dictionary_ozon['attribute_value_id']) { ?>
																		<option selected="selected" value="<?php echo $category_in['type']; ?>"><?php echo $dictionary_ozon['text']; ?></option>
																	<?php } else { ?>
																		<option value="<?php echo $dictionary_ozon['attribute_value_id']; ?>"><?php echo $dictionary_ozon['text']; ?></option>
																	<?php } ?>
																<?php } ?>
															<?php } ?>
																<option value="attr" <?php echo (isset($category_in['type']) && $category_in['type'] == 'attr') ? 'selected="selected"' : ''; ?>><?php echo $text_type_to_attr; ?></option>
															</select>
															</div>
															<?php if (isset($category_in['type']) && $category_in['type'] == 'attr') { ?>
																<div style="margin-top:5px;">
																	<button class="btn btn-primary btn-sm btn-block modal-type" type="button" value="<?php echo $category_in['ozon']; ?>"><?php echo $button_type_to_attr; ?></button>
																</div>
															<?php } ?>
														<?php } ?>
													</td>

													<td>
														<div class="alert alert-info">
															<?php foreach ($ozon_categories as $ozon_category) { ?>
																<?php echo ($category_in['ozon'] == $ozon_category['ozon_category_id'] ? '(id ' . $category_in['ozon'] . ') ' .$ozon_category['title'] : false); ?>
															<?php } ?>
														</div>
														<input type="hidden" name="ozon_seller_category[<?php echo $category_row; ?>][ozon]" value="<?php echo $category_in['ozon']; ?>" />
														<div class="row">
														<div class="col-sm-2" style="min-width:140px;">
															<div class="input-group">
															<span class="input-group-addon"><?php echo $text_length; ?></span>
															<input class="form-control" type="text" name="ozon_seller_category[<?php echo $category_row; ?>][length]" value="<?php echo $category_in['length']; ?>" />
															</div>
														</div>
														<div class="col-sm-2" style="padding-left:0px;min-width:140px;">
															<div class="input-group">
															<span class="input-group-addon"><?php echo $text_width; ?></span>
															<input class="form-control" type="text" name="ozon_seller_category[<?php echo $category_row; ?>][width]" value="<?php echo $category_in['width']; ?>" />
															</div>
														</div>
														<div class="col-sm-2" style="padding-left:0px;min-width:140px;">
															<div class="input-group">
															<span class="input-group-addon"><?php echo $text_height; ?></span>
															<input class="form-control" type="text" name="ozon_seller_category[<?php echo $category_row; ?>][height]" value="<?php echo $category_in['height']; ?>" />
															</div>
														</div>
														<div class="col-sm-3" style="padding-left:0px;">
															<div class="input-group">
															<span class="input-group-addon"><?php echo $text_weight; ?></span>
															<input class="form-control" type="text" name="ozon_seller_category[<?php echo $category_row; ?>][weight]" value="<?php echo $category_in['weight']; ?>" style="width:60px;" />
															</div>
														</div>
														<div class="col-sm-1">
															<input type="checkbox" class="form-control" data-toggle="tooltip" title="<?php echo $text_stop_export; ?>" name="ozon_seller_category[<?php echo $category_row; ?>][stop]" <?php if(isset($category_in['stop'])) echo 'checked'; ?> />
														</div>
														</div>
													</td>
													<td>
														<button type="button" onclick="$('#category_row<?php echo $category_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
													</td>
												</tr>
                 				<?php $category_row++; ?>
                			<?php } ?>
              			<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="2"></td>
												<td class="text-left"><button type="button" id="addCategory" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $get_category_ozon; ?><span data-toggle="tooltip" title="<?php echo $help_get_category_ozony; ?>"></span></label>
								<button type="button" class="btn btn-primary btn-sm" id="download_category"><i class="fa fa-download"></i> <?php echo $button_download; ?></button>
							</div>
						</div>

						<div class="tab-pane" id="tab-attribute">

							<?php if (!empty($attributes_ozon_description)) { ?>

								<div class="form-group">
									<?php if (!empty($ozon_categories)) { ?>
										<div class="col-sm-8">
											<select id="table-attribute-filters" class="form-control">
												<?php foreach ($ozon_categories as $filter_category) { ?>
													<option value="<?php echo $filter_category['ozon_category_id']; ?>"><?php echo $filter_category['title']; ?></option>
												<?php } ?>
											</select>
										</div>
										<button type="button" class="btn btn-primary button-attribute-filter"><?php echo $button_filter_att; ?></button>
									<?php } ?>
								</div>

								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td style="display:none;"></td>
												<td><?php echo $att_ozon; ?></td>
												<td><?php echo $att_shop; ?></td>
												<td><?php echo $dictionary; ?></td>
											</tr>
										</thead>
										<tbody id="table-attribute">
										<?php foreach ($attributes_ozon_description as $description) { ?>
											<tr>
												<td style="display:none;">
													<?php foreach ($attributes_ozon as $attribute_ozon) {
														if ($description['ozon_attribute_id'] == $attribute_ozon['ozon_attribute_id']) {
															echo $attribute_ozon['ozon_category_id'] . ' ';
														}
													} ?>
												</td>
												<td>
													<label data-required="<?php echo $description['ozon_attribute_id']; ?>" class="control-label"><?php echo $description['ozon_attribute_name']; ?>
													<?php if ($description['ozon_attribute_description']) { ?>
														<span data-toggle="tooltip" title="<?php echo $description['ozon_attribute_description']; ?>"></span>
													<?php } ?>
													</label>
												</td>
												<td>
													<?php foreach ($attributes_shop as $attribut_shop) {
														if (isset($ozon_seller_attribute[$description['ozon_attribute_id']]) && $ozon_seller_attribute[$description['ozon_attribute_id']] == $attribut_shop['attribute_id']) {

															$attribut_shop_name = $attribut_shop['name'];
															$attribut_shop_id = $attribut_shop['attribute_id'];

															break;

														} else {
															$attribut_shop_name = '';
															$attribut_shop_id = '';
														}
													} ?>

													<input type="text" class="form-control attr-search" data-id="<?php echo $description['ozon_attribute_id']; ?>" value="<?php echo $attribut_shop_name; ?>" />

													<input type="hidden" name="ozon_seller_attribute[<?php echo $description['ozon_attribute_id']; ?>]" value="<?php echo $attribut_shop_id; ?>" />
												</td>
												<td>
													<?php if ($description['ozon_dictionary_id']) { ?>
														<button type="button" class="btn btn-primary btn-block modal-dictionary-show" value="<?php echo $description['ozon_attribute_id']; ?>"><?php echo $dictionary; ?></button>
													<?php } ?>
												</td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="alert alert-info"><?php echo $help_attribute; ?></div>
								<button type="button" class="btn btn-danger btn-sm btn-block reload-attribute"><?php echo $text_reload_attribute; ?></button>
							<?php } ?>
						</div>

						<div class="tab-pane" id="tab-manufacturer">

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $manufacturer_stop; ?></label>
								<div class="col-sm-6">
									<div class="well well-sm" style="height:350px; overflow:auto;">
										<?php foreach ($manufacturers as $manufacturer) { ?>
											<div class="checkbox">
												<?php if (in_array($manufacturer['manufacturer_id'], $ozon_seller_manufacturer_stop)) { ?>
													<label><input type="checkbox" name="ozon_seller_manufacturer_stop[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" /></label>
												<?php } else { ?>
													<label><input type="checkbox" name="ozon_seller_manufacturer_stop[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /></label>
												<?php } ?>
												<?php echo $manufacturer['name']; ?>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-10">
									<div class="col-sm-6" style="padding-left:0">
										<select class="form-control" id="manufacturer-select">
											<?php foreach ($ozon_categories as $ozon_categorie) { ?>
												<option value="<?php echo $ozon_categorie['ozon_category_id']; ?>"><?php echo $ozon_categorie['title']; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-sm-4">
										<button type="button" id="manufacturer-download" class="btn btn-primary" target="_blank"><?php echo $manufacturer_download; ?></button>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-10">
									<button type="button" id="manufacturer-set" class="btn btn-primary"><?php echo $manufacturer_compare; ?></button>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab-setting">

							<div class="form-group">
								<label class="col-sm-3 control-label" for="test-export"><?php echo $text_test; ?></label>
								<div class="col-sm-2">
									<select name="ozon_seller_test_export" id="test-export" class="form-control">
										<?php if ($ozon_seller_test_export) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_limit; ?><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"></span></label>
								<div class="col-sm-2">
									<input class="form-control" type="text" name="ozon_seller_limit" value="<?php echo $ozon_seller_limit; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_min_price; ?></label>
								<div class="col-sm-2">
									<input class="form-control" type="text" name="ozon_seller_min_price" value="<?php echo $ozon_seller_min_price; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_description; ?></label>
								<div class="col-sm-8">
									<label class="radio-inline"><input type="radio" name="ozon_seller_description" value="card" <?php if($ozon_seller_description == 'card') echo 'checked'; ?> /> <?php echo $text_card; ?></label>
									<label class="radio-inline"><input type="radio" name="ozon_seller_description" value="my" <?php if($ozon_seller_description == 'my') echo 'checked'; ?> /> <?php echo $text_my; ?></label>
									<label class="radio-inline"><input type="radio" name="ozon_seller_description" value="no" <?php if($ozon_seller_description == 'no') echo 'checked'; ?> /> <?php echo $text_no_export; ?></label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_my_text; ?></label>
								<div class="col-sm-9">
									<textarea rows="6" class="form-control" name="ozon_seller_my_description"><?php echo $ozon_seller_my_description; ?></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_atrribute; ?></label>
								<div class="col-sm-9">
									<input type="checkbox" class="form-control" name="ozon_seller_attribute_description" <?php if($ozon_seller_attribute_description) echo 'checked'; ?> />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_export_stock_null; ?></label>
								<div class="col-sm-9">
									<input type="checkbox" class="form-control" name="ozon_seller_export_stock_null" <?php if($ozon_seller_export_stock_null) echo 'checked'; ?> />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_export_category; ?></label>
								<div class="col-sm-9">
									<input type="checkbox" class="form-control" name="ozon_seller_export_category" <?php if($ozon_seller_export_category) echo 'checked'; ?> />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_product_blacklist; ?><span data-toggle="tooltip" title="<?php echo $help_product_blacklist; ?>"></span></label>
								<div class="col-sm-8">
									<input type="text" value="" id="product-blacklist" class="form-control"/>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-3">&nbsp;</div>
								<div class="col-sm-8">
									<div id="blacklist-product" class="well well-sm" style="height: 465px; overflow: auto;">
									<?php foreach ($blacklist as $product_bl) { ?>
									<div id="blacklist-product<?php echo $product_bl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_bl['name']; ?>
									<input type="hidden" name="ozon_seller_product_blacklist[]" value="<?php echo $product_bl['product_id']; ?>" />
									</div>
									<?php } ?>
									</div>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-price">

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_nds; ?><span data-toggle="tooltip" title="<?php echo $help_nds; ?>"></span></label>
								<div class="col-sm-2">
									<select name="ozon_seller_nds" class="form-control">
										<?php foreach($nalog_nds as $key => $nds) { ?>
											<option value="<?php echo $key; ?>" <?php echo ($ozon_seller_nds == $key) ? 'selected="selected"' : '';  ?>><?php echo $nds; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_fictitious_price; ?><span data-toggle="tooltip" title="<?php echo $help_fictitious_price; ?>"></span></label>
								<div class="col-sm-2">
									<input class="form-control" type="text" name="ozon_seller_fictitious_price" value="<?php echo $ozon_seller_fictitious_price; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_price; ?><span data-toggle="tooltip" title="<?php echo $help_price; ?>"></span></label>
								<div class="col-sm-2">
									<?php echo $text_highway; ?>
									<input class="form-control" type="text" name="ozon_seller_highway" value="<?php echo $ozon_seller_highway; ?>" />
								</div>
								<div class="col-sm-2">
									<?php echo $text_percent; ?>
									<input class="form-control" type="text" name="ozon_seller_percent" value="<?php echo $ozon_seller_percent; ?>" />
								</div>
								<div class="col-sm-2">
									<?php echo $text_ruble; ?>
									<input class="form-control" type="text" name="ozon_seller_ruble" value="<?php echo $ozon_seller_ruble; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_last_mile; ?><span data-toggle="tooltip" title="<?php echo $help_last_mile; ?>"></span></label>
								<div class="col-sm-2">
									<input type="checkbox" class="form-control" name="ozon_seller_last_mile" <?php if($ozon_seller_last_mile) echo 'checked'; ?> />
								</div>

								<div class="col-sm-2">
									<?php echo $text_min_last_mile; ?>
									<input class="form-control" type="text" name="ozon_seller_min_last_mile" value="<?php echo $ozon_seller_min_last_mile; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_price_fbo; ?><span data-toggle="tooltip" title="<?php echo $help_price_fbo; ?>"></span></label>
								<div class="col-sm-2">
									<?php echo $text_highway; ?>
									<input class="form-control" type="text" name="ozon_seller_highway_fbo" value="<?php echo $ozon_seller_highway_fbo; ?>" />
								</div>
								<div class="col-sm-2">
									<?php echo $text_percent; ?>
									<input class="form-control" type="text" name="ozon_seller_percent_fbo" value="<?php echo $ozon_seller_percent_fbo; ?>" />
								</div>
								<div class="col-sm-2">
									<?php echo $text_ruble; ?>
									<input class="form-control" type="text" name="ozon_seller_ruble_fbo" value="<?php echo $ozon_seller_ruble_fbo; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_last_mile_fbo; ?><span data-toggle="tooltip" title="<?php echo $help_last_mile_fbo; ?>"></span></label>
								<div class="col-sm-2">
									<input type="checkbox" class="form-control" name="ozon_seller_last_mile_fbo" <?php if($ozon_seller_last_mile_fbo) echo 'checked'; ?> />
								</div>

								<div class="col-sm-2">
									<?php echo $text_min_last_mile; ?>
									<input class="form-control" type="text" name="ozon_seller_min_last_mile_fbo" value="<?php echo $ozon_seller_min_last_mile_fbo; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_prices; ?><span data-toggle="tooltip" title="<?php echo $help_prices; ?>"></span></label>
								<div class="table-responsive col-sm-9">
									<table id="price" class="table table-bordered">
										<thead>
											<tr>
												<td><?php echo $text_oc; ?></td>
												<td><?php echo $text_value; ?></td>
												<td><?php echo $text_action; ?></td>
												<td><?php echo $text_rate; ?></td>
												<td></td>
											</tr>
										</thead>

										<tbody>
			              <?php $price_row = 0; ?>
										<?php if (!empty($ozon_seller_prices)) { ?>
											<?php foreach ($ozon_seller_prices as $prices) { ?>
												<tr id="price_row<?php echo $price_row; ?>">
												<td>
												<select class="form-control" name="ozon_seller_prices[<?php echo $price_row; ?>][els]">
													<?php foreach ($prices_elses as $key => $price_elses) { ?>
														<?php if ($prices['els'] == $key) { ?>
															<option value="<?php echo $key; ?>" selected="selected"><?php echo $price_elses; ?></option>
														<?php } else { ?>
															<option value="<?php echo $key; ?>"><?php echo $price_elses; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
												</td>
												<td>
												<input type="text" class="form-control" name="ozon_seller_prices[<?php echo $price_row; ?>][value]" value="<?php echo !empty($prices['value']) ? $prices['value'] : false; ?>" />
												</td>
												<td>
												<select class="form-control" name="ozon_seller_prices[<?php echo $price_row; ?>][action]">
													<?php foreach ($prices_action as $key => $price_action) { ?>
														<?php if ($prices['action'] == $key) { ?>
															<option value="<?php echo $key; ?>" selected="selected"><?php echo $price_action; ?></option>
														<?php } else { ?>
															<option value="<?php echo $key; ?>"><?php echo $price_action; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
												</td>
												<td>
												<input type="text" class="form-control" name="ozon_seller_prices[<?php echo $price_row; ?>][rate]" value="<?php echo !empty($prices['rate']) ? $prices['rate'] : false; ?>" />
												</td>
												<td class="text-left"><button type="button" onclick="$('#price_row<?php echo $price_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
												</td>
												</tr>
												<?php $price_row++; ?>
											<?php } ?>
										<?php } ?>

									</tbody>
									<tfoot>
										<tr>
											<td colspan="4"></td>
											<td class="text-left"><button type="button" id="addPrice" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
									</table>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_price_round; ?></label>
								<div class="col-sm-4">
									<select name="ozon_seller_price_round" class="form-control">
										<?php foreach($price_round as $key => $round_price) { ?>
											<option value="<?php echo $key; ?>" <?php echo ($ozon_seller_price_round == $key) ? 'selected="selected"' : '';  ?>><?php echo $round_price; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_no_price_update; ?></label>
								<div class="col-sm-8">
									<input type="text" value="" id="searh-no-price-update" class="form-control"/>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">&nbsp;</div>
								<div class="col-sm-8">
									<div id="list-no-price-update" class="well well-sm" style="height: 400px; overflow: auto;">
									<?php foreach ($products_npu as $product_npu) { ?>
									<div id="list-no-price-update<?php echo $product_npu['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_npu['name']; ?>
									<input type="hidden" name="ozon_seller_product_npu[]" value="<?php echo $product_npu['product_id']; ?>" />
									</div>
									<?php } ?>
									</div>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-warehouse">

							<div class="col-xs-2">
								<ul class="nav nav-pills nav-stacked">
									<?php if (!empty($ozon_seller_warehouses)) { ?>
										<?php $li_wareh = 0; ?>
										<?php foreach ($ozon_seller_warehouses as $warehouses) { ?>
											<?php if (!empty($ozon_seller_sklad)) { ?>
												<?php foreach ($ozon_seller_sklad as $key => $sklad) { ?>
													<?php if ($key == $warehouses) { ?>
														<?php $warehouses = $sklad['name']; ?>
													<?php } ?>
												<?php } ?>
											<?php } ?>
											<li <?php echo $li_wareh == 0 ? 'class="active"' : false; ?>><a href="#tab-sklad<?php echo $li_wareh; ?>" data-toggle="tab"><?php echo $warehouses; ?></a></li>
											<?php $li_wareh++; ?>
										<?php } ?>
									<?php } ?>

							    <li><a href="#tab-add-warehouse" data-toggle="tab"><i class="fa fa-plus-circle"></i>&nbsp;<?php echo $button_add; ?></a></li>
								</ul>
							</div>

							<div class="col-xs-9">
								<div class="tab-content">
									<?php if (!empty($ozon_seller_warehouses)) {
										$tab_wareh = 0;
										foreach ($ozon_seller_warehouses as $warehouses_t) {
											if (!empty($ozon_seller_sklad)) {
												foreach ($ozon_seller_sklad as $key => $sklad_t) {
													if ($key == $warehouses_t) {
														$sklad_t = $sklad_t;
														break;
													} else {
														$sklad_t = '';
													}
												}
											} ?>
											<div class="tab-pane <?php echo $tab_wareh == 0 ? 'active' : false; ?>" id="tab-sklad<?php echo $tab_wareh; ?>">

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_name; ?></label>
													<div class="col-sm-4">
														<input type="text" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][name]" value="<?php echo (!empty($sklad_t['name'])) ? $sklad_t['name'] : $warehouses_t; ?>">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_stocks_null; ?></label>
													<div class="col-sm-6">
														<input type="checkbox" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][null]" <?php echo (!empty($ozon_seller_sklad[$warehouses_t]['null'])) ? 'checked' : ''; ?> />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_stok_min; ?></label>
													<div class="col-sm-2">
														<input type="text" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][stock]" value="<?php echo (!empty($sklad_t['stock'])) ? $sklad_t['stock'] : ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_price_sklad; ?><span data-toggle="tooltip" title="<?php echo $text_sklad_info; ?>"></span></label>
													<div class="col-sm-2">
														<input type="text" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][price]" placeholder="<?php echo $text_ot; ?>" value="<?php echo (!empty($sklad_t['price'])) ? $sklad_t['price'] : ''; ?>">
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][price_do]" placeholder="<?php echo $text_do; ?>" value="<?php echo (!empty($sklad_t['price_do'])) ? $sklad_t['price_do'] : ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_weight_sklad; ?><span data-toggle="tooltip" title="<?php echo $text_sklad_info; ?>"></span></label>
													<div class="col-sm-2">
														<input type="text" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][weight]" placeholder="<?php echo $text_ot; ?>" value="<?php echo (!empty($sklad_t['weight'])) ? $sklad_t['weight'] : ''; ?>">
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][weight_do]" placeholder="<?php echo $text_do; ?>" value="<?php echo (!empty($sklad_t['weight_do'])) ? $sklad_t['weight_do'] : ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $manufacturer_stop; ?></label>
													<div class="col-sm-9">
														<div class="well well-sm" style="height:400px; overflow:auto;">
															<?php foreach ($manufacturers as $manufacturer) { ?>
																<div class="checkbox">
																	<?php if (!empty($ozon_seller_sklad[$warehouses_t]['manufacture']) && in_array($manufacturer['manufacturer_id'], $ozon_seller_sklad[$warehouses_t]['manufacture'])) { ?>
																		<label><input type="checkbox" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][manufacture][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" /></label>
																	<?php } else { ?>
																		<label><input type="checkbox" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][manufacture][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /></label>
																	<?php } ?>
																	<?php echo $manufacturer['name']; ?>
																</div>
															<?php } ?>
														</div>
														<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_white_list; ?></label>
													<div class="col-sm-9">
														<input type="text" value="" data-sklad-search-wl="<?php echo $warehouses_t; ?>" class="form-control sklad-search-white-list" />
													</div>
												</div>

												<div class="row">
													<div class="col-sm-3">&nbsp;</div>
													<div class="col-sm-9">
														<div data-sklad-white-list="<?php echo $warehouses_t; ?>" class="well well-sm" style="height: 400px; overflow: auto;">
														<?php foreach ($sklad_white_list as $product_swl) { ?>
														<?php if ($product_swl['sklad'] == $warehouses_t) { ?>
														<div id="white-skald-product<?php echo $product_swl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_swl['name']; ?>
														<input type="hidden" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][white_list][]" value="<?php echo $product_swl['product_id']; ?>" />
														</div>
														<?php } ?>
														<?php } ?>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_product_blacklist; ?></label>
													<div class="col-sm-9">
														<input type="text" value="" data-sklad-search-bl="<?php echo $warehouses_t; ?>" class="form-control sklad-search-black-list" />
													</div>
												</div>

												<div class="row">
													<div class="col-sm-3">&nbsp;</div>
													<div class="col-sm-9">
														<div data-sklad-black-list="<?php echo $warehouses_t; ?>" class="well well-sm" style="height: 400px; overflow: auto;">
														<?php foreach ($sklad_black_list as $product_sbl) { ?>
														<?php if ($product_sbl['sklad'] == $warehouses_t) { ?>
														<div id="black-skald-product<?php echo $product_sbl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_sbl['name']; ?>
														<input type="hidden" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][black_list][]" value="<?php echo $product_sbl['product_id']; ?>" />
														</div>
														<?php } ?>
														<?php } ?>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_blacklist_category; ?></label>
													<div class="col-sm-9">
														<div class="well well-sm" style="height: 400px; overflow: auto;">
														<?php foreach ($categories as $category) { ?>
														<div class="checkbox row-fluid">
															<?php if (isset($ozon_seller_sklad[$warehouses_t]['black_list_category']) && in_array($category['category_id'], $ozon_seller_sklad[$warehouses_t]['black_list_category'])) { ?>
															<label><input type="checkbox" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][black_list_category][]" value="<?php echo $category['category_id']; ?>" checked="checked" /></label>
															<?php echo $category['name']; ?>
															<?php } else { ?>
															<label><input type="checkbox" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][black_list_category][]" value="<?php echo $category['category_id']; ?>" /></label>
															<?php echo $category['name']; ?>
															<?php } ?>
														</div>
														<?php } ?>
													</div>
													<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_no_stock_update; ?></label>
													<div class="col-sm-9">
														<input type="text" value="" data-sklad-search-nu="<?php echo $warehouses_t; ?>" class="form-control sklad-search-no-update" />
													</div>
												</div>

												<div class="row">
													<div class="col-sm-3">&nbsp;</div>
													<div class="col-sm-9">
														<div data-sklad-no-update="<?php echo $warehouses_t; ?>" class="well well-sm" style="height: 400px; overflow: auto;">
														<?php foreach ($sklad_no_update as $product_nu) { ?>
														<?php if ($product_nu['sklad'] == $warehouses_t) { ?>
														<div id="skald-no-update<?php echo $warehouses_t . $product_nu['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_nu['name']; ?>
														<input type="hidden" name="ozon_seller_sklad[<?php echo $warehouses_t; ?>][no_update][]" value="<?php echo $product_nu['product_id']; ?>" />
														</div>
														<?php } ?>
														<?php } ?>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label"><?php echo $text_delete_sklad; ?></label>
													<div class="col-sm-6">
														<button type="button" class="btn btn-danger" onclick="$('#tab-sklad<?php echo $tab_wareh; ?>').detach();"><?php echo $button_remove; ?></button>
													</div>
												</div>

												<input type="hidden" name="ozon_seller_warehouses[] "value="<?php echo $warehouses_t; ?>" />
											</div>
											<?php $tab_wareh++; ?>
										<?php } ?>
									<?php } ?>

									<div class="tab-pane" id="tab-add-warehouse">
										<div class="form-group">
											<label class="col-sm-2 control-label"><?php echo $text_warehouse_ozon; ?></label>
											<div class="col-sm-6">
												<select name="ozon_seller_warehouses[]" class="form-control">
												</select>
											</div>
											<div class="col-sm-4">
												<button type="button" class="btn btn-primary get-sklad"><?php echo $text_download_sklad; ?></button>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab-order">

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_status_order_oc; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_order_oc" class="form-control">
										<?php if ($ozon_seller_status_order_oc) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_status_new; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_new" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $ozon_seller_status_new) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $awaiting_deliver; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_deliver" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $ozon_seller_status_deliver) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $cancelled; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_cancel" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $ozon_seller_status_cancel) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $delivering; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_shipping" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $ozon_seller_status_shipping) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $delivered; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_delevered" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $ozon_seller_status_delevered) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $returned; ?></label>
								<div class="col-sm-3">
									<select name="ozon_seller_status_return" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $ozon_seller_status_return) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_product_price_oc; ?></label>
								<div class="col-sm-3">
									<input type="checkbox" class="form-control" name="ozon_seller_product_price_oc" <?php if($ozon_seller_product_price_oc) echo 'checked'; ?> />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"> <?php echo $text_lastname; ?><span data-toggle="tooltip" title="<?php echo $help_lastname; ?>"></span></label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="ozon_seller_lastname" value="<?php echo $ozon_seller_lastname; ?>" />
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-moysklad">

							<div class="form-group">
								<label class="col-sm-3 control-label" for="ms-status"><?php echo $entry_chek_ms; ?><span data-toggle="tooltip" title="<?php echo $help_chek_ms; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_chek_ms" id="ms-status" class="form-control">
										<?php if ($ozon_seller_chek_ms) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-login-ms"><?php echo $entry_login_ms; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_login_ms" value="<?php echo $ozon_seller_login_ms; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-key-ms"><?php echo $entry_key_ms; ?></label>
								<div class="col-sm-4">
									<input type="password" name="ozon_seller_key_ms" value="<?php echo $ozon_seller_key_ms; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-3"></div>
								<div class="col-sm-4">
									<div class="alert alert-info" role="alert"><?php echo $help_id_ms; ?></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_organization; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_organization" value="<?php echo $ozon_seller_organization; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_agent; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_agent" value="<?php echo $ozon_seller_agent; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_project; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_project" value="<?php echo $ozon_seller_project; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_saleschannel; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_saleschannel_ms" value="<?php echo $ozon_seller_saleschannel_ms; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_store; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_store" value="<?php echo $ozon_seller_store; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $get_metadata_order; ?></label>
								<div class="col-sm-4">
									<a href="<?php echo $get_metadata; ?>" class="btn btn-primary" target="_blank"><?php echo $button_download; ?></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_new_order_ms; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_new_order_ms" value="<?php echo $ozon_seller_status_new_order_ms; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_awaiting_deliver; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_awaiting_deliver" value="<?php echo $ozon_seller_status_awaiting_deliver; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_print; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_print" value="<?php echo $ozon_seller_status_print; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_delivering; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_delivering" value="<?php echo $ozon_seller_status_delivering; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_cancelled; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_cancelled" value="<?php echo $ozon_seller_status_cancelled; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_returned; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_returned" value="<?php echo $ozon_seller_status_returned; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_status_delivered; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_status_delivered" value="<?php echo $ozon_seller_status_delivered; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $get_metadata_attributes; ?></label>
								<div class="col-sm-4">
									<a href="<?php echo $get_attributes; ?>" class="btn btn-primary" target="_blank"><?php echo $button_download; ?></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_attributes; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_sticker" value="<?php echo $ozon_seller_sticker; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_payment_date; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_payment_date" value="<?php echo $ozon_seller_payment_date; ?>" class="form-control">
								</div>
							</div>

							<!-- <div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $komission; ?><span data-toggle="tooltip" title="<?php echo $help_komission; ?>"></span></label>
								<div class="col-sm-10">
									<select name="ozon_seller_komission" class="form-control">
										<?php // if ($ozon_seller_komission) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php // } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php // } ?>
									</select>
								</div>
							</div> -->

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $payment_ms; ?></label>
								<div class="col-sm-4">
									<select name="ozon_seller_payment_ms" class="form-control">
										<?php if ($ozon_seller_payment_ms) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_connect_prod; ?></label>
								<div class="col-sm-2">
									<?php echo $text_oc; ?>
									<select name="ozon_seller_connect_prod_shop" class="form-control">
										<?php foreach ($connect_shop as $key => $connect_s) { ?>
											<option value="<?php echo $key; ?>" <?php if ($key == $ozon_seller_connect_prod_shop) echo 'selected="selected"'; ?>><?php echo $connect_s; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-2">
									<?php echo $tab_moysklad; ?>
									<select name="ozon_seller_connect_prod_ms" class="form-control">
										<?php foreach ($connect_ms as $key => $connect_m) { ?>
											<option value="<?php echo $key; ?>" <?php if ($key == $ozon_seller_connect_prod_ms) echo 'selected="selected"'; ?>><?php echo $connect_m; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $webhook; ?><span data-toggle="tooltip" title="<?php echo $help_webhook; ?>"></span></label>
								<div class="col-sm-4">
									<a href="<?php echo $webhook_create; ?>" class="btn btn-primary" target="_blank">Создать webhook</a>
									<a href="<?php echo $webhook_delete; ?>" class="btn btn-danger" target="_blank">Удалить webhook</a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_autoreturn; ?><span data-toggle="tooltip" title="<?php echo $help_autoreturn; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_autoreturn_fbs" class="form-control">
										<?php if ($ozon_seller_autoreturn_fbs) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $reestr_period; ?><span data-toggle="tooltip" title="<?php echo $help_reestr; ?>"></span></label>
								<div class="col-sm-2">
									<input data-id="startDate" class="form-control date" data-date-format="DD-MM-YYYY" />
								</div>
								<div class="col-sm-2">
									<input data-id="endDate" class="form-control date" data-date-format="DD-MM-YYYY" />
								</div>
								<button type="button" class="btn btn-primary date-send"><?php echo $button_download; ?></button>
							</div>
						</div>

						<div class="tab-pane" id="tab-fbo">

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_chek_fbo; ?><span data-toggle="tooltip" title="<?php echo $help_chek_fbo; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_chek_fbo" class="form-control">
										<?php if ($ozon_seller_chek_fbo) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_status_order_oc; ?></label>
								<div class="col-sm-4">
									<select name="ozon_seller_status_order_fbo_oc" class="form-control">
										<?php if ($ozon_seller_status_order_fbo_oc) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $entry_store_fbo; ?></label>
								<div class="col-sm-4">
									<input type="text" name="ozon_seller_store_fbo" value="<?php echo $ozon_seller_store_fbo; ?>" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $komission; ?><span data-toggle="tooltip" title="<?php echo $help_komission; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_komission_fbo" class="form-control">
										<?php if ($ozon_seller_komission_fbo) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_autoreturn; ?><span data-toggle="tooltip" title="<?php echo $help_autoreturn; ?>"></span></label>
								<div class="col-sm-4">
									<select name="ozon_seller_autoreturn" class="form-control">
										<?php if ($ozon_seller_autoreturn) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-cron">

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $cron_url_update; ?></label>
								<div class="col-sm-8 input-group">
									<input type="text" value="<?php echo $update_url; ?>" class="form-control" readonly="readonly" />
									<span class="input-group-btn"><a href="<?php echo $update_url; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_url_price; ?></label>
								<div class="col-sm-8 input-group">
									<input type="text" value="<?php echo $url_price; ?>" class="form-control" readonly="readonly" />
									<span class="input-group-btn"><a href="<?php echo $url_price; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $cron_url_order_in_ms; ?></label>
								<div class="col-sm-8 input-group">
									<input type="text" value="<?php echo $url_order_in_ms; ?>" class="form-control" readonly="readonly" />
									<span class="input-group-btn"><a href="<?php echo $url_order_in_ms; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $cron_url_order_fbo; ?></label>
								<div class="col-sm-8 input-group">
									<input type="text" value="<?php echo $url_order_fbo; ?>" class="form-control" readonly="readonly" />
									<span class="input-group-btn"><a href="<?php echo $url_order_fbo; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $cron_url_final_status; ?></label>
								<div class="col-sm-8 input-group">
									<input type="text" value="<?php echo $url_final_status; ?>" class="form-control" readonly="readonly" />
									<span class="input-group-btn"><a href="<?php echo $url_final_status; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $chek_order_list; ?></label>
								<div class="col-sm-8 input-group">
									<input type="text" value="<?php echo $url_chek_order_list; ?>" class="form-control" readonly="readonly" />
									<span class="input-group-btn"><a href="<?php echo $url_chek_order_list; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<div class="alert alert-info" role="alert"><?php echo $help_cron; ?></div>
								</div>
							</div>

						</div>

						<div class="tab-pane" id="tab-about">

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_instruction; ?></label>
								<div class="col-sm-4 input-group">
									<input type="text" value="<?php echo $text_instruction_url; ?>" class="form-control" readonly="readonly" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_author; ?></label>
								<div class="col-sm-4 input-group">
									<input type="text" value="<?php echo $author; ?>" class="form-control" readonly="readonly" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_download_update; ?></label>
								<div class="col-sm-4 input-group">
									<input type="text" value="<?php echo $url_update; ?>" class="form-control" readonly="readonly" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_author_email; ?></label>
								<div class="col-sm-4 input-group">
									<input type="text" value="<?php echo $author_email; ?>" class="form-control" readonly="readonly" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_doc_api_ozon; ?></label>
								<div class="col-sm-4 input-group">
									<input type="text" value="<?php echo $doc_api_ozon; ?>" class="form-control" readonly="readonly" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php echo $text_doc_api_ms; ?></label>
								<div class="col-sm-4 input-group">
									<input type="text" value="<?php echo $doc_api_ms; ?>" class="form-control" readonly="readonly" />
								</div>
							</div>

						</div>

					</div>
					<input type="hidden" name="ozon_seller_version" value="<?php echo $heading_title; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--
	var category_row = <?php echo $category_row; ?>;

	$(document).delegate('#addCategory', 'click', function addCategory() {
	  html =  '<tr id="category_row' + category_row + '">';
	  html += ' <td>';
	  html += '	<input class="form-control" type="text" name="shopcatsearch' + category_row + '" />';
		html += '	<input type="hidden" name="ozon_seller_category[' + category_row + '][shop]" value="0" />';
	  html += ' </td>';
	  html += ' <td>';
		html += '	<input class="form-control" type="text" name="ozoncatsearch' + category_row + '" />';
		html += '	<input type="hidden" name="ozon_seller_category[' + category_row + '][ozon]" value="0" />';
	  html += ' </td>';
	  html += ' <input type="hidden" name="ozon_seller_category[' + category_row + '][length]" value="10" /> <input type="hidden" name="ozon_seller_category[' + category_row + '][width]" value="10" /> <input type="hidden" name="ozon_seller_category[' + category_row + '][height]" value="10" /> <input type="hidden" name="ozon_seller_category[' + category_row + '][weight]" value="900" />';
	  html += ' <td class="text-left"><button type="button" onclick="$(\'#category_row' + category_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	  html += '</tr>';

    $('#category tbody').append(html);

  	// +++ Живой поиск категорий +++
  	var imputNameOzonView = 'ozoncatsearch' + category_row;
  	var imputNameOzonHidden = 'ozon_seller_category[' + category_row + '][ozon]';

		$('input[name=\'' + imputNameOzonView + '\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=module/ozon_seller/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['title'],
								value: item['ozon_category_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'' + imputNameOzonView + '\']').val(item['label']);
				$('input[name=\'' + imputNameOzonHidden + '\']').val(item['value']);
			}
		});

		var imputNameShopView = 'shopcatsearch' + category_row;
    var imputNameShopHidden = 'ozon_seller_category[' + category_row + '][shop]';

		$('input[name=\'' + imputNameShopView + '\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['category_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'' + imputNameShopView + '\']').val(item['label']);
				$('input[name=\'' + imputNameShopHidden + '\']').val(item['value']);
			}
		});

		$('.dropdown-menu').addClass('scrollable');
		$('.scrollable').css({'height':'auto','max-height':'30em','overflow-x':'hidden'});

		// --- Живой поиск категорий ---
	  category_row++;
	});

	$('#download_category').on('click', function() {
		$.ajax({
			url: '/index.php?route=module/ozon_seller/getcategoryozon&cron_pass=<?php echo $ozon_seller_cron_pass; ?>',
			success: function() {
				document.location.reload();
			}
		});
	});

	$('#manufacturer-download').on('click', function() {
		var select = $('#manufacturer-select').val();
		$.ajax({
			url: '<?php echo $url_manufacturer_download; ?>&category=' + select,
			beforeSend: function() {
				$('#manufacturer-download').button('loading');
			},
			success: function(html){
				$('#manufacturer-download').button('reset');
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

	$('.load-attribute').on('click', function() {
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

		$.ajax({
			url: '/index.php?route=module/ozon_seller/loadattributes&cron_pass=<?php echo $ozon_seller_cron_pass; ?>',
			beforeSend: function() {
				$('.load-attribute').button('loading');
			},
			success: function(data) {
				document.location.reload();
			}
		});
	});

	// Живой поиск атрибуов магазина
	$('.attr-search').focus(function() {
		ozonAttrId = $(this).attr('data-id');
	});

	$('.attr-search').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					json.unshift(
						{
							name: '-- очистить --',
							attribute_id: null
						}
					);
					json.push(
						{
							attribute_group: 'Поле Opencart',
							name: 'Артикул',
							attribute_id: 'sku'
						},
						{
							attribute_group: 'Поле Opencart',
							name: 'Модель',
							attribute_id: 'model'
						},
						{
							attribute_group: 'Поле Opencart',
							name: 'MPN',
							attribute_id: 'mpn'
						},
						{
							attribute_group: 'Поле Opencart',
							name: 'ISBN',
							attribute_id: 'isbn'
						},
						{
							attribute_group: 'Поле Opencart',
							name: 'EAN',
							attribute_id: 'ean'
						},
						{
							attribute_group: 'Поле Opencart',
							name: 'JAN',
							attribute_id: 'jan'
						},
						{
							attribute_group: 'Поле Opencart',
							name: 'UPC',
							attribute_id: 'upc'
						}
					);
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[data-id=\'' + window.ozonAttrId + '\']').val(item['label']);
			$('input[name=\'ozon_seller_attribute[' + window.ozonAttrId + ']\']').val(item['value']);
		}
	});

	// Таблица формируются в контроллере
	$(document).on('click', '.save-dictionary', function(e) {
		e.preventDefault();
 		var dictionary = 'dictionary-form';
		$.ajax({
			url: 'index.php?route=module/ozon_seller/savedictionary&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'html',
			data: $("#"+dictionary).serialize(),
			success: function(response) {
				$(".modal").modal("hide");
			}
		});
	});

	// Таблица формируются в контроллере
	$(document).on('click', '.save-type', function(e) {
		e.preventDefault();
 		var type = 'type-form';
		$.ajax({
			url: 'index.php?route=module/ozon_seller/savetype&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'html',
			data: $("#"+type).serialize(),
			success: function(response) {
				$(".modal").modal("hide");
			}
		});
	});

	// Таблица формируются в контроллере
	$(document).on('click', '.save-manufacturer', function(e) {
		e.preventDefault();
 		var manufacturers = 'manufacturer-form';
		$.ajax({
			url: 'index.php?route=module/ozon_seller/savemanufacturer&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'html',
			data: $("#"+manufacturers).serialize(),
			success: function(response) {
				$(".modal").modal("hide");
			}
		});
	});

	/* Вызываем модальное */
	$(function() {
		var myModal = new ModalApp.ModalProcess({ id: 'myModal'});
		myModal.init();

		$('.modal-dictionary-show').on('click', function(e) {
			e.preventDefault();
			var dictionary = $(this).attr('value');
			var shop_id = $('input[name=\'ozon_seller_attribute[' + dictionary + ']\']').attr('value');
			$.get('index.php?route=module/ozon_seller/modaldictionary&token=<?php echo $token; ?>&dictionary=' + dictionary + '&shop_id=' + shop_id,
				function(data) {
				var data = JSON.parse(data);
				myModal.changeTitle(data['title']);
				myModal.changeBody(data['body']);
				myModal.changeFooter(data['footer']);
				myModal.showModal();
			});
		});

		$('.modal-type').on('click', function(e) {
			e.preventDefault();
			var category_ozon = $(this).attr('value');
			$.get('index.php?route=module/ozon_seller/modaltype&token=<?php echo $token; ?>&category_ozon=' + category_ozon,
				function(data) {
				var data = JSON.parse(data);
				myModal.changeTitle(data['title']);
				myModal.changeBody(data['body']);
				myModal.changeFooter(data['footer']);
				myModal.showModal();
			});
		});

		$('#manufacturer-set').on('click', function(e) {
			e.preventDefault();
			$.get('index.php?route=module/ozon_seller/manufacturerset&token=<?php echo $token; ?>',
				function(data) {
				var data = JSON.parse(data);
				myModal.changeTitle(data['title']);
				myModal.changeBody(data['body']);
				myModal.changeFooter(data['footer']);
				myModal.showModal();
			});
		});
	});

	/* SCRIPT MODAL */
	var ModalApp = {};
	ModalApp.ModalProcess = function (parameters) {
	  this.id = parameters['id'] || 'modal';
	  this.selector = parameters['selector'] || '';
	  this.title = parameters['title'] || 'Заголовок модального окна';
	  this.body = parameters['body'] || 'Содержимое модального окна';
	  this.footer = parameters['footer'] || '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';
	  this.content = '<div id="'+this.id+'" class="modal fade" tabindex="-1" role="dialog">'+
	    '<div class="modal-dialog" role="document">'+
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

	/* Фильтр таблицы с атрибутами */
	$(document).ready(function(){
		$('.button-attribute-filter').on('click', function() {
			var value = $('#table-attribute-filters').val();
			//удалим все обязательные лейблы
			let element = document.getElementsByClassName('required');
		  while (element.length) {
		    element[0].parentNode.removeChild(element[0]);
		  }
			//сортируем список
			$('#table-attribute tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
			//получим обязательные атрибуты для категории
			$.ajax({
				url: 'index.php?route=module/ozon_seller/getattributerequired&token=<?php echo $token; ?>&category=' + value,
				success: function(data) {
					var attribute = $.parseJSON(data);
					$.each(attribute, function(key, id) {
						$.each(id, function(key, attribute_id) {
							$("label[data-required=\'" + attribute_id + "\']").append('<div class="required" style="margin-left:3px;display:inline;padding:0.2em 0.6em 0.3em;font-size:75%;font-weight:bold;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:0.25em;background-color:#e3503e;">обязательный для этой категории</div>');
						});
					});
				}
			});
		});
	});

	//обновить атрибуты
	$('.reload-attribute').on('click', function() {
		$.ajax({
			url: 'index.php?route=module/ozon_seller/truncateattribute&token=<?php echo $token; ?>',
			beforeSend: function() {
				$('.reload_attribute').button('loading');
				$('html, body').animate({scrollTop: '0px'}, 1500);
			},
			success: function() {
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

				$.ajax({
					url: '/index.php?route=module/ozon_seller/loadattributes&cron_pass=<?php echo $ozon_seller_cron_pass; ?>',
					success: function(data) {
						document.location.reload();
					}
				});
			}
		});
	});

	/* Реестр платежей */
	$('.date').datetimepicker({
		pickTime: false
	});

	$('.date-send').on('click', function() {
		var startDate = $('input[data-id=\'startDate\']').val();
		var endDate = $('input[data-id=\'endDate\']').val();
		$.ajax({
			data: {startDate,endDate},
			url: '/index.php?route=module/ozon_seller/reestr&cron_pass=<?php echo $ozon_seller_cron_pass; ?>',
			beforeSend: function() {
				$('.date-send').button('loading');
			},
			success: function(html) {
				alert(html);
				$('.date-send').button('reset');
			}
		});
	});

	//Черный список
	$('#product-blacklist').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		},
		select: function(item) {
			$('#blacklist-product' + item.value).val('');
			$('#blacklist-product' + item.value).remove();

			$('#blacklist-product').append('<div id="blacklist-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ozon_seller_product_blacklist[]" value="' + item['value'] + '" /></div>');

			$('input[name="ozon_seller_product_blacklist"]').val('');

			return false;
		}
	});

	$('#blacklist-product').on('click', 'i', function() {
	    $(this).parent().remove();
	});

	// Склады
	$('.get-sklad').on('click', function() {
		$.ajax({
			url: '<?php echo $url_warehouse; ?>',
			success: function(json) {
				if (json != 'error') {
					var warehouse = $.parseJSON(json);
					$.each(warehouse, function(key, value) {
						$('select[name="ozon_seller_warehouses[]"]').append('<option value="' + value['warehouse_id'] + '">' + value['name'] + ' (' + value['warehouse_id'] + ')</option>');
					});
				}
				$('.get-sklad').prop('disabled', true);
			}
		});
	});

	// Белый список складов
	sklad_id = '';
	$('.sklad-search-white-list').focus(function() {
		sklad_id = $(this).attr('data-sklad-search-wl');
	});

	$('.sklad-search-white-list').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		},
		select: function(item) {
			$('#white-skald-product' + item.value).val('');
			$('#white-skald-product' + item.value).remove();

			$('[data-sklad-white-list=\'' + sklad_id + '\']').append('<div id="white-skald-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ozon_seller_sklad[' + sklad_id + '][white_list][]" value="' + item['value'] + '" /></div>');

			$('.sklad-search-white-list').val('');

			return false;
		}
	});

	$('[data-sklad-white-list]').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Черный список складов
	$('.sklad-search-black-list').focus(function() {
		sklad_id = $(this).attr('data-sklad-search-bl');
	});

	$('.sklad-search-black-list').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		},
		select: function(item) {
			$('#black-skald-product' + item.value).val('');
			$('#black-skald-product' + item.value).remove();

			$('[data-sklad-black-list=\'' + sklad_id + '\']').append('<div id="black-skald-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ozon_seller_sklad[' + sklad_id + '][black_list][]" value="' + item['value'] + '" /></div>');

			$('.sklad-search-black-list').val('');

			return false;
		}
	});

	$('[data-sklad-black-list]').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Список не обновлять остатки
	$('.sklad-search-no-update').focus(function() {
		sklad_id = $(this).attr('data-sklad-search-nu');
	});

	$('.sklad-search-no-update').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		},
		select: function(item) {
			$('#skald-no-update' + sklad_id + item.value).val('');
			$('#skald-no-update' + sklad_id + item.value).remove();

			$('[data-sklad-no-update=\'' + sklad_id + '\']').append('<div id="skald-no-update' + sklad_id + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ozon_seller_sklad[' + sklad_id + '][no_update][]" value="' + item['value'] + '" /></div>');

			$('.sklad-search-no-update').val('');

			return false;
		}
	});

	$('[data-sklad-no-update]').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

	// Черный список цен
	$('#searh-no-price-update').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		},
		select: function(item) {
			$('#list-no-price-update' + item.value).val('');
			$('#list-no-price-update' + item.value).remove();

			$('#list-no-price-update').append('<div id="list-no-price-update' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ozon_seller_product_npu[]" value="' + item['value'] + '" /></div>');

			$('#searh-no-price-update').val('');

			return false;
		}
	});

	$('#list-no-price-update').on('click', 'i', function() {
	    $(this).parent().remove();
	});

	// Таблица наценок
	var price_row = <?php echo $price_row; ?>;
	$(document).delegate('#addPrice', 'click', function addCategory() {
    html =  '<tr id="price_row' + price_row + '">';
		html += '<td><select class="form-control" name="ozon_seller_prices['+ price_row +'][els]"><option value="price">Цена</option><option value="manufacturer_id">ID производителя</option><option value="category_id">ID категории</option><option value="product_id">ID товара</option></select></td>';
    html += '<td><input type="text" class="form-control" placeholder="1-100" name="ozon_seller_prices['+ price_row +'][value]" value="" /></td>';
		html += '<td><select class="form-control" name="ozon_seller_prices['+ price_row +'][action]"><option value="+">+</option><option value="-">-</option><option value="*">*</option></select></td>';
    html += '<td><input type="text" class="form-control" placeholder="1.2 или 50" name="ozon_seller_prices['+ price_row +'][rate]" value="" /></td>';
    html += '<td class="text-left"><button type="button" onclick="$(\'#price_row' + price_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#price tbody').append(html);
		price_row++;
	});

//--></script>
