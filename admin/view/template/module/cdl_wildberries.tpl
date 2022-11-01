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
						<li><a href="<?php echo $url_orders_wb; ?>"><?php echo $text_orders_wb; ?></a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo $url_product; ?>"><?php echo $text_product; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_attributes; ?>"><?php echo $text_attributes; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_rima; ?>"><?php echo $text_rima; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_supplies; ?>" target="_blank"><?php echo $text_supplies; ?></a></li>
						<li role="separator" class="divider"></li>
				    <li><a href="<?php echo $url_statistics; ?>" target="_blank"><?php echo $text_statistics; ?></a></li>
				  </ul>
				</div>
        <button type="submit" form="form-cdl-wildberries" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>

			<h1><?php echo $heading_title; ?></h1>

			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
				</div>
				<div class="panel-body">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cdl-wildberries" class="form-horizontal">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
              <li><a href="#tab-category" data-toggle="tab"><?php echo $tab_category; ?></a></li>
              <li><a href="#tab-manufacturer" data-toggle="tab"><?php echo $tab_manufacturer; ?></a></li>
							<li><a href="#tab-export" data-toggle="tab"><?php echo $tab_export; ?></a></li>
							<li><a href="#tab-price" data-toggle="tab"><?php echo $tab_price; ?></a></li>
							<li><a href="#tab-warehouses" data-toggle="tab"><?php echo $tab_warehouses; ?></a></li>
							<li><a href="#tab-ms" data-toggle="tab"><?php echo $tab_ms; ?></a></li>
							<li><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
							<li><a href="#tab-url" data-toggle="tab"><?php echo $tab_url; ?></a></li>
							<li><a href="#tab-about" data-toggle="tab"><?php echo $tab_about; ?></a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="tab-general">

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $text_general_token; ?></label>
									<div class="col-sm-8">
										<input type="text" name="cdl_wildberries_general_token" class="form-control" value="<?php echo $cdl_wildberries_general_token; ?>" />
									</div>
									<div class="col-sm-2">
										<button type="button" class="btn btn-info check-token"><?php echo $text_check; ?></button>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $text_api_statistics_key; ?></label>
									<div class="col-sm-6">
										<input type="text" name="cdl_wildberries_api_statistics_key" class="form-control" value="<?php echo $cdl_wildberries_api_statistics_key; ?>" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $text_pass; ?></label>
									<div class="col-sm-3">
										<input type="text" name="cdl_wildberries_pass" class="form-control" value="<?php echo $cdl_wildberries_pass; ?>" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $text_pass_supplies; ?></label>
									<div class="col-sm-3">
										<input type="text" name="cdl_wildberries_pass_supplies" class="form-control" value="<?php echo $cdl_wildberries_pass_supplies; ?>" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $text_relations; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_relations" class="form-control">
                      <?php foreach ($relations as $key => $relation) { ?>
                        <?php if ($cdl_wildberries_relations == $key) { ?>
                          <option value="<?php echo $key; ?>" selected="selected"><?php echo $relation; ?></option>
                        <?php } else { ?>
                          <option value="<?php echo $key; ?>"><?php echo $relation; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
									</div>
								</div>

                <div class="form-group">
  								<label class="col-sm-2 control-label"><?php echo $entry_g; ?></label>
  								<div class="col-sm-3">
  									<select name="cdl_wildberries_weight" class="form-control">
  										<?php foreach ($weight_classes as $weight_class) { ?>
  			                <?php if ($weight_class['weight_class_id'] == $cdl_wildberries_weight) { ?>
  				            		<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
  				            	<?php } else { ?>
  				            		<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
  											<?php } ?>
  					          <?php } ?>
  									</select>
  								</div>
  							</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_sm; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_length" class="form-control">
	                    <?php foreach ($length_classes as $length_class) { ?>
	                    	<?php if ($length_class['length_class_id'] == $cdl_wildberries_length) { ?>
	                    		<option selected="selected" value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
	                    	<?php } else { ?>
	                    		<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
	                    	<?php } ?>
	                    <?php } ?>
	                </select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $text_card_version; ?></label>
									<div class="col-sm-3">
										<input type="checkbox" name="cdl_wildberries_card_version" <?php if($cdl_wildberries_card_version) echo 'checked'; ?> />
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
  									<a href='<?php echo $clear; ?>' class="btn btn-danger btn-lg btn-block"><i class="fa fa-eraser"></i></a>
  								</div>
  							</div>
              </div>

              <div class="tab-pane" id="tab-category">
                <div class="form-group">
  								<div class="table-responsive" style="padding:0 10px;">
  									<table id="category" class="table table-striped table-bordered table-hover">
  										<thead>
  											<tr>
  												<td style="width:380px;"><?php echo $category_shop; ?><div class="pull-right"><button type="button" class="btn btn-primary btn-sm no-category-save" style="padding-left:5px;padding-right:5px;padding-top:0px;padding-bottom:0px;" data-toggle="tooltip" title="<?php echo $btn_no_category_save; ?>"><i class="fa fa-bars"></i></button></div></td>
  												<td><?php echo $category_wb; ?><span data-toggle="tooltip" title="<?php echo $help_default_size; ?>"></span></td>
  												<td><button type="button" id="addCategory" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>
  											</tr>
  										</thead>

  										<tbody>
  			              <?php $category_row = 0; ?>
                        <?php if ($cdl_wildberries_category) { ?>
													<?php foreach ($cdl_wildberries_category as $category) { ?>
														<tr id="category_row<?php echo $category_row; ?>">
															<td>
															<?php foreach ($categories as $oc_category) { ?>
															<?php if ($category['shop'] == $oc_category['category_id']) { ?>
															<div class="alert alert-info">
																<?php echo '(id ' . $category['shop'] . ') ' . $oc_category['name']; ?>
															</div>
															<?php } ?>
															<?php } ?>

															<input type="hidden" name="cdl_wildberries_category[<?php echo $category_row; ?>][shop]" value="<?php echo $category['shop']; ?>" />

															<div class="input-group">
															<span class="input-group-addon lock" data-lock="<?php echo $category_row; ?>" data-toggle="tooltip" title="<?php echo $text_filter; ?>"><i class="fa fa-lock fa-lg"></i></span>
															<select class="form-control filter-exp" data-filter-select="<?php echo $category_row; ?>" name="cdl_wildberries_category[<?php echo $category_row; ?>][filter_select]" style="pointer-events:none;opacity:0.5;">
																<?php foreach ($export_filters as $key => $export_filter) { ?>
																	<?php if (isset($category['filter_select']) && $key == $category['filter_select']) { ?>
						                    		<option selected="selected" value="<?php echo $key; ?>"><?php echo $export_filter; ?></option>
						                    	<?php } else { ?>
						                    		<option value="<?php echo $key; ?>"><?php echo $export_filter; ?></option>
						                    	<?php } ?>
						                    <?php } ?>
															</select>
															<div class="filter-add-<?php echo $category_row; ?>">
															<?php if ($category['filter_select'] == 'attr') { ?>
																<input type="text" name="cdl_wildberries_category[<?php echo $category_row; ?>][attr-name]" class="attr-search" data-filter-search="<?php echo $category_row; ?>" value="<?php echo $category['attr-name']; ?>" style="width:50%;pointer-events:none;opacity:0.5;" /><input type="text" name="cdl_wildberries_category[<?php echo $category_row; ?>][filter-value]" value="<?php echo $category['filter-value']; ?>" style="width:50%;pointer-events:none;opacity:0.5;">
																<input type="hidden" name="cdl_wildberries_category[<?php echo $category_row; ?>][filter-attr-id]" value="<?php echo $category['filter-attr-id']; ?>" />
															<?php } ?>
															</div>
															</div>
															</td>

															<td>
																<div class="alert alert-info"><?php echo $category['wb']; ?></div>
																<input type="hidden" name="cdl_wildberries_category[<?php echo $category_row; ?>][wb]" value="<?php echo $category['wb']; ?>" />
																<div class="col-sm-2">
																	<div class="input-group">
																	<span class="input-group-addon"><?php echo $text_length; ?></span>
																	<input class="form-control" type="text" name="cdl_wildberries_category[<?php echo $category_row; ?>][length]" value="<?php echo $category['length']; ?>" style="min-width:45px;" />
																	</div>
																</div>
																<div class="col-sm-2">
																	<div class="input-group">
																	<span class="input-group-addon"><?php echo $text_width; ?></span>
																	<input class="form-control" type="text" name="cdl_wildberries_category[<?php echo $category_row; ?>][width]" value="<?php echo $category['width']; ?>" style="min-width:45px;" />
																	</div>
																</div>
																<div class="col-sm-2">
																	<div class="input-group">
																	<span class="input-group-addon"><?php echo $text_height; ?></span>
																	<input class="form-control" type="text" name="cdl_wildberries_category[<?php echo $category_row; ?>][height]" value="<?php echo $category['height']; ?>" style="min-width:45px;" />
																	</div>
																</div>
																<div class="col-sm-3">
																	<div class="input-group">
																	<span class="input-group-addon"><?php echo $text_weight; ?></span>
																	<input class="form-control" type="text" name="cdl_wildberries_category[<?php echo $category_row; ?>][weight]" value="<?php echo $category['weight']; ?>" />
																	</div>
																</div>
																<div class="col-sm-1">
																	<?php echo $text_stop_export; ?>
																	<input type="checkbox" class="form-control" name="cdl_wildberries_category[<?php echo $category_row; ?>][stop]" <?php if(isset($category['stop'])) echo 'checked'; ?> />
																</div>
															</td>
															<td>
																<button type="button" onclick="$('#category_row<?php echo $category_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button>
															</td>
														</tr>
														<?php $category_row++; ?>
													<?php } ?>
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"></td>
                          <td class="text-left"><button type="button" id="addCategory" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>

                <div class="form-group">
  								<label class="col-sm-2 control-label"><?php echo $download_category; ?><span data-toggle="tooltip" title="<?php echo $help_download_category; ?>"></span></label>
  								<button type="button" class="btn btn-primary btn-sm download-category"><i class="fa fa-download"></i> <?php echo $button_download; ?></button>
									<div class="pull-right" style="margin-right:19px;"><button type="button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary btn-sm save-duble"><i class="fa fa-save"></i></button></div>
  							</div>

              </div>

              <div class="tab-pane" id="tab-manufacturer">
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_brendlist; ?></label>
									<div class="col-sm-6">
										<a href="<?php echo $url_brendlist; ?>" class="btn btn-primary" target="_blank"><?php echo $text_go_wb; ?></a>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_manufacturer; ?></label>
									<div class="col-sm-6">
										<button type="button" class="btn btn-primary manufacturer-set"><?php echo $text_manufacturer; ?></button>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $manufacturer_stop; ?></label>
									<div class="col-sm-6">
										<div class="well well-sm" style="height:500px; overflow:auto;">
											<?php foreach ($manufacturers as $manufacturer) { ?>
												<div class="checkbox">
													<?php if (in_array($manufacturer['manufacturer_id'], $cdl_wildberries_manufacturer_stop)) { ?>
														<label><input type="checkbox" name="cdl_wildberries_manufacturer_stop[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" /></label>
													<?php } else { ?>
														<label><input type="checkbox" name="cdl_wildberries_manufacturer_stop[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /></label>
													<?php } ?>
													<?php echo $manufacturer['name']; ?>
												</div>
											<?php } ?>
										</div>
										<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
									</div>
								</div>
              </div>

              <div class="tab-pane" id="tab-export">

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_test; ?></label>
									<div class="col-sm-2">
										<select name="cdl_wildberries_test_export" class="form-control">
											<?php if ($cdl_wildberries_test_export) { ?>
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
									<label class="col-sm-3 control-label"><?php echo $text_log; ?><span data-toggle="tooltip" title="<?php echo $help_log; ?>"></span></label>
									<div class="col-sm-2">
										<select name="cdl_wildberries_log" class="form-control">
											<?php if ($cdl_wildberries_log) { ?>
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
									<label class="col-sm-3 control-label"><?php echo $text_price_range; ?></label>
									<div class="col-sm-2">
										<input class="form-control" type="text" name="cdl_wildberries_price_export_ot" value="<?php echo $cdl_wildberries_price_export_ot; ?>" placeholder="от" />
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" name="cdl_wildberries_price_export_do" value="<?php echo $cdl_wildberries_price_export_do; ?>" placeholder="до" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_barcode; ?><span data-toggle="tooltip" title="<?php echo $help_barcode; ?>"></span></label>
									<div class="col-sm-2">
										<select name="cdl_wildberries_barcode" class="form-control">
											<?php foreach ($input_barcodes as $key => $input_barcode) { ?>
											<?php if ($key == $cdl_wildberries_barcode) { ?>
												<option value="<?php echo $key; ?>" selected="selected"><?php echo $input_barcode; ?></option>
											<?php } else { ?>
												<option value="<?php echo $key; ?>"><?php echo $input_barcode; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_delimiter; ?><span data-toggle="tooltip" title="<?php echo $help_delimiter; ?>"></span></label>
									<div class="col-sm-2">
										<input class="form-control" type="text" name="cdl_wildberries_delimiter" value="<?php echo $cdl_wildberries_delimiter; ?>" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_name_crop; ?></label>
									<div class="col-sm-2">
										<input type="checkbox" class="form-control" name="cdl_wildberries_name_crop" <?php echo $cdl_wildberries_name_crop ? 'checked' : false; ?> />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_description; ?></label>
									<div class="col-sm-2">
										<input type="checkbox" class="form-control" name="cdl_wildberries_description" <?php echo $cdl_wildberries_description ? 'checked' : false; ?> />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_attributes_description; ?></label>
									<div class="col-sm-2">
										<input type="checkbox" class="form-control" name="cdl_wildberries_attributes_description" <?php echo $cdl_wildberries_attributes_description ? 'checked' : false; ?> />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_export_stock_null; ?></label>
									<div class="col-sm-9">
										<input type="checkbox" class="form-control" name="cdl_wildberries_export_stock_null" <?php if($cdl_wildberries_export_stock_null) echo 'checked'; ?> />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_export_category; ?></label>
									<div class="col-sm-9">
										<input type="checkbox" class="form-control" name="cdl_wildberries_export_category" <?php if($cdl_wildberries_export_category) echo 'checked'; ?> />
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
										<div id="blacklist-product" class="well well-sm" style="height: 400px; overflow: auto;">
										<?php foreach ($blacklist as $product_bl) { ?>
										<div id="blacklist-product<?php echo $product_bl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_bl['name']; ?>
										<input type="hidden" name="cdl_wildberries_blacklist[]" value="<?php echo $product_bl['product_id']; ?>" />
										</div>
										<?php } ?>
										</div>
									</div>
								</div>

              </div>

							<div class="tab-pane" id="tab-price">

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_category_price; ?></label>
									<div class="col-sm-4">
										<button type="button" class="btn btn-primary category-price"><?php echo $text_category_wb; ?></button>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_percentage; ?></label>
									<div class="table-responsive col-sm-8">
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
											<?php if (!empty($cdl_wildberries_prices)) { ?>
												<?php foreach ($cdl_wildberries_prices as $prices) { ?>
													<tr id="price_row<?php echo $price_row; ?>">
													<td>
													<select class="form-control" name="cdl_wildberries_prices[<?php echo $price_row; ?>][els]">
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
													<input type="text" class="form-control" name="cdl_wildberries_prices[<?php echo $price_row; ?>][value]" value="<?php echo !empty($prices['value']) ? $prices['value'] : false; ?>" />
													</td>
													<td>
													<select class="form-control" name="cdl_wildberries_prices[<?php echo $price_row; ?>][action]">
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
													<input type="text" class="form-control" name="cdl_wildberries_prices[<?php echo $price_row; ?>][rate]" value="<?php echo !empty($prices['rate']) ? $prices['rate'] : false; ?>" />
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
										<select name="cdl_wildberries_price_round" class="form-control">
											<?php foreach($price_round as $key => $round_price) { ?>
												<option value="<?php echo $key; ?>" <?php echo ($cdl_wildberries_price_round == $key) ? 'selected="selected"' : '';  ?>><?php echo $round_price; ?></option>
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
										<input type="hidden" name="cdl_wildberries_product_npu[]" value="<?php echo $product_npu['product_id']; ?>" />
										</div>
										<?php } ?>
										</div>
									</div>
								</div>

              </div>

							<div class="tab-pane" id="tab-warehouses">

								<div class="col-xs-2">
									<ul class="nav nav-pills nav-stacked">
										<?php $li_wareh = 0; ?>
										<?php if (!empty($cdl_wildberries_warehouses)) { ?>
											<?php foreach ($cdl_wildberries_warehouses as $warehous) { ?>
												<?php if (!empty($warehous['name'])) { ?>
													<?php $li_wareh_name = $warehous['name']; ?>
												<?php } else { ?>
													<?php $li_wareh_name = $warehous['sklad_id']; ?>
												<?php } ?>
												<li <?php echo $li_wareh == 0 ? ' class="active"' : 'false'; ?>><a href="#tab-sklad<?php echo $li_wareh; ?>" data-toggle="tab"><?php echo $li_wareh_name; ?></a></li>
												<?php $li_wareh++; ?>
											<?php } ?>
										<?php } ?>
										<li><a href="#tab-add-warehouse" data-toggle="tab"><i class="fa fa-plus-circle"></i>&nbsp;<?php echo $button_add; ?></a></li>
									</ul>
								</div>

								<div class="col-xs-10">
									<div class="tab-content">
										<?php $tab_wareh = 0; ?>
										<?php if (!empty($cdl_wildberries_warehouses)) { ?>
											<?php foreach ($cdl_wildberries_warehouses as $warehous_tab) { ?>
												<div class="tab-pane <?php echo $tab_wareh == 0 ? ' active' : 'false'; ?>" id="tab-sklad<?php echo $tab_wareh; ?>">

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_name; ?></label>
														<div class="col-sm-4">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][name]" value="<?php echo empty($warehous_tab['name']) ? $warehous_tab['sklad_id'] : $warehous_tab['name']; ?>" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_time; ?></label>
														<div class="col-sm-4">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][time]" value="<?php echo empty($warehous_tab['time']) ? '' : $warehous_tab['time']; ?>" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_work_time; ?></label>
														<div class="col-sm-6">
															<?php foreach ($works_day as $key => $work_day) { ?>
																<div class="col-sm-1" style="padding-left:0px;">
																<?php echo $work_day; ?>
																<input type="checkbox" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][day][<?php echo $key; ?>]" <?php echo empty($warehous_tab['day'][$key]) ? '' : 'checked'; ?> />
																</div>
															<?php } ?>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_stocks_null; ?></label>
														<div class="col-sm-2">
															<input type="checkbox" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][null]" <?php echo empty($warehous_tab['null']) ? '' : 'checked'; ?> />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_stok_min; ?></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][stock]" value="<?php echo empty($warehous_tab['stock']) ? '' : $warehous_tab['stock']; ?>" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_price_sklad; ?><span data-toggle="tooltip" title="<?php echo $text_sklad_info; ?>"></span></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][price]" placeholder="<?php echo $text_ot; ?>" value="<?php echo empty($warehous_tab['price']) ? '' : $warehous_tab['price']; ?>" />
														</div>
														<div class="col-sm-2">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][price_do]" placeholder="<?php echo $text_do; ?>" value="<?php echo empty($warehous_tab['price_do']) ? '' : $warehous_tab['price_do']; ?>" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_weight_sklad; ?><span data-toggle="tooltip" title="<?php echo $text_sklad_info; ?>"></span></label>
														<div class="col-sm-2">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][weight]" placeholder="<?php echo $text_ot; ?>" value="<?php echo empty($warehous_tab['weight']) ? '' : $warehous_tab['weight']; ?>" />
														</div>
														<div class="col-sm-2">
															<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][weight_do]" placeholder="<?php echo $text_do; ?>" value="<?php echo empty($warehous_tab['weight_do']) ? '' : $warehous_tab['weight_do']; ?>" />
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $manufacturer_stop; ?></label>
														<div class="col-sm-9">
															<div class="well well-sm" style="height:400px; overflow:auto;">
																<?php foreach ($manufacturers as $manufacturer) { ?>
																	<div class="checkbox">
																		<?php if (!empty($warehous_tab['manufacture']) && in_array($manufacturer['manufacturer_id'], $warehous_tab['manufacture'])) { ?>
																			<label><input type="checkbox" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][manufacture][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" /></label>
																		<?php } else { ?>
																			<label><input type="checkbox" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][manufacture][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" /></label>
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
															<input type="text" value="" data-sklad-search-wl="<?php echo $tab_wareh; ?>" class="form-control sklad-search-white-list" />
														</div>
													</div>

													<div class="row">
														<div class="col-sm-3">&nbsp;</div>
														<div class="col-sm-9">
															<div data-sklad-white-list="<?php echo $tab_wareh; ?>" class="well well-sm" style="height: 400px; overflow: auto;">
															<?php foreach ($sklad_white_list as $product_swl) { ?>
															<?php if ($product_swl['sklad'] == $warehous_tab['sklad_id']) { ?>
															<div id="white-skald-product<?php echo $product_swl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_swl['name']; ?>
															<input type="hidden" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][white_list][]" value="<?php echo $product_swl['product_id']; ?>" />
															</div>
															<?php } ?>
															<?php } ?>
															</div>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_product_blacklist; ?></label>
														<div class="col-sm-9">
															<input type="text" value="" data-sklad-search-bl="<?php echo $tab_wareh; ?>" class="form-control sklad-search-black-list" />
														</div>
													</div>

													<div class="row">
														<div class="col-sm-3">&nbsp;</div>
														<div class="col-sm-9">
															<div data-sklad-black-list="<?php echo $tab_wareh; ?>" class="well well-sm" style="height: 400px; overflow: auto;">
															<?php foreach ($sklad_black_list as $product_sbl) { ?>
															<?php if ($product_sbl['sklad'] == $warehous_tab['sklad_id']) { ?>
															<div id="black-skald-product<?php echo $product_sbl['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_sbl['name']; ?>
															<input type="hidden" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][black_list][]" value="<?php echo $product_sbl['product_id']; ?>" />
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
																<?php if (isset($warehous_tab['black_list_category']) && in_array($category['category_id'], $warehous_tab['black_list_category'])) { ?>
																<label><input type="checkbox" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][black_list_category][]" value="<?php echo $category['category_id']; ?>" checked="checked" /></label>
																<?php echo $category['name']; ?>
																<?php } else { ?>
																<label><input type="checkbox" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][black_list_category][]" value="<?php echo $category['category_id']; ?>" /></label>
																<?php echo $category['name']; ?>
																<?php } ?>
															</div>
															<?php } ?>
														</div>
														<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_sklad_attribute; ?></label>
														<div class="table-responsive col-sm-8">
															<table id="sklad-attr<?php echo $tab_wareh; ?>" class="table table-bordered">
																<thead>
																	<tr>
																		<td><?php echo $text_name; ?></td>
																		<td><?php echo $text_val; ?></td>
																		<td></td>
																	</tr>
																</thead>
																<tbody>
																	<?php $attr_sklad_row = 0; ?>
																	<?php if (!empty($warehous_tab['attribute'])) { ?>
																		<?php foreach ($warehous_tab['attribute'] as $sklad_attr) { ?>
																			<tr class="sklad-attr-row<?php echo $attr_sklad_row; ?>">
																				<td>
																					<input type="text" class="form-control attr-search-sklad" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][attribute][<?php echo $attr_sklad_row; ?>][name]" value="<?php echo $sklad_attr['name']; ?>" data-sklad-attr-row="<?php echo $attr_sklad_row; ?>" data-sklad-tab-wareh1="<?php echo $tab_wareh; ?>" placeholder="<?php echo $text_start_typing; ?>" value="<?php echo $sklad_attr['name']; ?>" />

																					<input type="hidden" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][attribute][<?php echo $attr_sklad_row; ?>][id]" value="<?php echo $sklad_attr['id']; ?>" />
																				</td>
																				<td>
																					<input type="text" class="form-control" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][attribute][<?php echo $attr_sklad_row; ?>][value]" value="<?php echo $sklad_attr['value']; ?>" />
																				</td>
																				<td><button type="button" onclick="$('.sklad-attr-row<?php echo $attr_sklad_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
																				</td>
																			</tr>
																			<?php $attr_sklad_row++; ?>
																		<?php } ?>
																	<?php } ?>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="2"></td>
																		<td class="text-left"><button type="button" id="addSkladAttr" class="btn btn-primary" data-sklad-tab-wareh="<?php echo $tab_wareh; ?>"><i class="fa fa-plus-circle"></i></button></td>
																	</tr>
																</tfoot>
															</table>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 control-label"><?php echo $text_delete_sklad; ?></label>
														<div class="col-sm-6">
															<button type="button" class="btn btn-danger" onclick="$('#tab-sklad<?php echo $tab_wareh; ?>').detach();"><?php echo $button_remove; ?></button>
														</div>
													</div>

													<input type="hidden" name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][sklad_id]" value="<?php echo $warehous_tab['sklad_id']; ?>" />

												</div>
												<?php $tab_wareh++; ?>
											<?php } ?>
										<?php } ?>
										<div class="tab-pane" id="tab-add-warehouse">
											<div class="form-group">
												<label class="col-sm-2 control-label"><?php echo $text_entry_warehouse; ?></label>
												<div class="col-sm-6">
													<select name="cdl_wildberries_warehouses[<?php echo $tab_wareh; ?>][sklad_id]" class="form-control">
													</select>
												</div>
												<div class="col-sm-4">
													<button type="button" data-wareh="<?php echo $tab_wareh; ?>" class="btn btn-primary get-sklad"><?php echo $text_reload_input; ?></button>
													<button type="button" data-wareh="<?php echo $tab_wareh; ?>" class="btn btn-danger clear-select-warehouse"><?php echo $text_dont_add; ?></button>
												</div>
											</div>
										</div>
									</div>
								</div>

              </div>

							<div class="tab-pane" id="tab-ms">

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_ms_status; ?></label>
									<div class="col-sm-2">
										<select name="cdl_wildberries_ms_status" class="form-control">
											<?php if ($cdl_wildberries_ms_status) { ?>
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
									<label class="col-sm-3 control-label"><?php echo $text_login_ms; ?></label>
									<div class="col-sm-2">
										<input type="text" name="cdl_wildberries_login_ms" value="<?php echo $cdl_wildberries_login_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_key_ms; ?></label>
									<div class="col-sm-2">
										<input type="password" name="cdl_wildberries_key_ms" value="<?php echo $cdl_wildberries_key_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-3"></div>
									<div class="col-sm-9">
										<div class="alert alert-info" role="alert"><?php echo $help_id_ms; ?></div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_organization_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_organization_ms" value="<?php echo $cdl_wildberries_organization_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_agent_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_agent_ms" value="<?php echo $cdl_wildberries_agent_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_project_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_project_ms" value="<?php echo $cdl_wildberries_project_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_store_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_store_ms" value="<?php echo $cdl_wildberries_store_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_return_store_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_return_store_ms" value="<?php echo $cdl_wildberries_return_store_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_auto_return; ?></label>
									<div class="col-sm-2">
										<input type="checkbox" class="form-control" name="cdl_wildberries_auto_return" <?php echo empty($cdl_wildberries_auto_return) ? '' : 'checked'; ?> />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_order_ms; ?></label>
									<div class="col-sm-4">
										<a href="<?php echo $get_metadata; ?>" class="btn btn-primary" target="_blank"><?php echo $button_download; ?></a>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_new_order_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_new_order_ms" value="<?php echo $cdl_wildberries_status_new_order_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_packing; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_packing_ms" value="<?php echo $cdl_wildberries_status_packing_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_print; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_print_ms" value="<?php echo $cdl_wildberries_status_print_ms; ?>" class="form-control">
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_delivering; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_delivering_ms" value="<?php echo $cdl_wildberries_status_delivering_ms; ?>" class="form-control">
									</div>
								</div> -->

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_cancelled; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_cancelled_ms" value="<?php echo $cdl_wildberries_status_cancelled_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_return; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_return_ms" value="<?php echo $cdl_wildberries_status_return_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_delivered; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_status_delivered_ms" value="<?php echo $cdl_wildberries_status_delivered_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_metadata_attributes; ?></label>
									<div class="col-sm-4">
										<a href="<?php echo $url_get_input_id; ?>" class="btn btn-primary" target="_blank"><?php echo $button_download; ?></a>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_sticker_id_ms; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_sticker_id_ms" value="<?php echo $cdl_wildberries_sticker_id_ms; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_payment_date; ?></label>
									<div class="col-sm-4">
										<input type="text" name="cdl_wildberries_payment_date" value="<?php echo $cdl_wildberries_payment_date; ?>" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_connect_prod; ?></label>
									<div class="col-sm-4">
										<?php echo $text_oc; ?>
										<select name="cdl_wildberries_connect_prod_shop" class="form-control">
											<?php foreach ($connect_shop as $key => $connect_s) { ?>
												<option value="<?php echo $key; ?>" <?php if ($key == $cdl_wildberries_connect_prod_shop) echo 'selected="selected"'; ?>><?php echo $connect_s; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-sm-4">
										<?php echo $text_moysklad; ?>
										<select name="cdl_wildberries_connect_prod_ms" class="form-control">
											<?php foreach ($connect_ms as $key => $connect_m) { ?>
												<option value="<?php echo $key; ?>" <?php if ($key == $cdl_wildberries_connect_prod_ms) echo 'selected="selected"'; ?>><?php echo $connect_m; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_webhook; ?><span data-toggle="tooltip" title="<?php echo $help_webhook; ?>"></span></label>
									<div class="col-sm-4">
										<a href="<?php echo $url_webhook_create; ?>" class="btn btn-primary" target="_blank"><?php echo $text_webhook_create; ?></a>
										<a href="<?php echo $url_webhook_delete; ?>" class="btn btn-danger" target="_blank"><?php echo $text_webhook_delete; ?></a>
									</div>
								</div>

              </div>

							<div class="tab-pane" id="tab-order">

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_status_order_oc; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_order_oc" class="form-control">
											<?php if ($cdl_wildberries_order_oc) { ?>
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
									<label class="col-sm-3 control-label"><?php echo $new; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_status_new_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_status_new_oc) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $awaiting_packaging; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_awaiting_packaging_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_awaiting_packaging_oc) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $packaging; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_packaging_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_packaging_oc) { ?>
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
										<select name="cdl_wildberries_cancelled_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_cancelled_oc) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $return; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_return_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_return_oc) { ?>
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
										<select name="cdl_wildberries_delevered_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_delevered_oc) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $status_payment; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_payment_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_payment_oc) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $status_discrepancy; ?></label>
									<div class="col-sm-3">
										<select name="cdl_wildberries_discrepancy_oc" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $cdl_wildberries_discrepancy_oc) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_email_order; ?></label>
									<div class="col-sm-3">
										<input type="text" name="cdl_wildberries_email_order" value="<?php echo $cdl_wildberries_email_order; ?>" class="form-control" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_firstname_customer; ?></label>
									<div class="col-sm-3">
										<input type="text" name="cdl_wildberries_name_customer" value="<?php echo $cdl_wildberries_name_customer; ?>" class="form-control" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_lastname_customer; ?><span data-toggle="tooltip" title="<?php echo $help_lastname; ?>"></span></label>
									<div class="col-sm-3">
										<input type="text" name="cdl_wildberries_lastname_customer" value="<?php echo $cdl_wildberries_lastname_customer; ?>" class="form-control" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_product_price_oc; ?></label>
									<div class="col-sm-9">
										<input type="checkbox" class="form-control" name="cdl_wildberries_product_price_oc" <?php if($cdl_wildberries_product_price_oc) echo 'checked'; ?> />
									</div>
								</div>

              </div>

							<div class="tab-pane" id="tab-url">

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_url_price; ?></label>
									<div class="col-sm-8 input-group">
										<input type="text" value="<?php echo $url_price; ?>" class="form-control" readonly="readonly" />
										<span class="input-group-btn"><a href="<?php echo $url_price; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_url_stock; ?></label>
									<div class="col-sm-8 input-group">
										<input type="text" value="<?php echo $url_stock; ?>" class="form-control" readonly="readonly" />
										<span class="input-group-btn"><a href="<?php echo $url_stock; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_url_orders; ?></label>
									<div class="col-sm-8 input-group">
										<input type="text" value="<?php echo $url_orders; ?>" class="form-control" readonly="readonly" />
										<span class="input-group-btn"><a href="<?php echo $url_orders; ?>" class="btn btn-success" target="_blank"><i class="fa fa-arrow-right"></i></a></span>
									</div>
								</div>

              </div>

							<div class="tab-pane" id="tab-about">

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_author; ?></label>
									<div class="col-sm-4 input-group">
										<input type="text" value="<?php echo $author; ?>" class="form-control" readonly="readonly" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_author_email; ?></label>
									<div class="col-sm-4 input-group">
										<input type="text" value="<?php echo $author_email; ?>" class="form-control" readonly="readonly" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_update_cdl; ?></label>
									<div class="col-sm-4 input-group">
										<input type="text" value="<?php echo $text_url_update_cdl; ?>" class="form-control" readonly="readonly" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_doc_api_wb; ?></label>
									<div class="col-sm-4 input-group">
										<input type="text" value="<?php echo $doc_api_wb; ?>" class="form-control" readonly="readonly" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><?php echo $text_doc_api_ms; ?></label>
									<div class="col-sm-4 input-group">
										<input type="text" value="<?php echo $doc_api_ms; ?>" class="form-control" readonly="readonly" />
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<?php echo $help_soglasie; ?>
									</div>
								</div>
              </div>
            </div>
						<input type="hidden" name="cdl_wildberries_version" value="<?php echo $heading_title; ?>" />
					</form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--
var category_row = <?php echo $category_row; ?>;
$(document).delegate('#addCategory', 'click', function addCategory() {
	html =  '<tr id="category_row' + category_row + '"><td>';
  html += '<input class="form-control" type="text" name="shopcatsearch' + category_row + '" placeholder="Категория магазина" />';
  html += '<input type="hidden" name="cdl_wildberries_category[' + category_row + '][shop]" value="0" />';
	html += '<input type="hidden" name="cdl_wildberries_category[' + category_row + '][filter_select]" value="all" />';
  html += '</td><td>';
  html += '<input class="form-control" type="text" name="wbcatsearch' + category_row + '" placeholder="Категория WB" style="margin-bottom:10px;" />';
  html += '<input type="hidden" name="cdl_wildberries_category[' + category_row + '][wb]" value="0" />';
	html += '<div class="col-sm-2"><div class="input-group"><span class="input-group-addon"><?php echo $text_length; ?></span><input type="text" class="form-control" name="cdl_wildberries_category[' + category_row + '][length]" value="10" /></div></div>';
	html += '<div class="col-sm-2"><div class="input-group"><span class="input-group-addon"><?php echo $text_width; ?></span><input type="text" class="form-control" name="cdl_wildberries_category[' + category_row + '][width]" value="5" /></div></div>';
	html += '<div class="col-sm-2"><div class="input-group"><span class="input-group-addon"><?php echo $text_height; ?></span><input type="text" class="form-control" name="cdl_wildberries_category[' + category_row + '][height]" value="5" /></div></div>';
	html += '<div class="col-sm-3"><div class="input-group"><span class="input-group-addon"><?php echo $text_weight; ?></span><input type="text" class="form-control" name="cdl_wildberries_category[' + category_row + '][weight]" value="200" /></div></div>';
  html += '</td><td class="text-left"><button type="button" onclick="$(\'#category_row' + category_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td></tr>';

  $('#category tbody').append(html);

	$('html, body').animate({
    scrollTop: $('#category_row' + category_row).offset().top
  }, 1000);

  // +++ Живой поиск категорий WB +++
  var imputNamewbView = 'wbcatsearch' + category_row;
  var imputNamewbHidden = 'cdl_wildberries_category[' + category_row + '][wb]';

  $('input[name=\'' + imputNamewbView + '\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=module/cdl_wildberries/autocompletecategorywb&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['title'],
              value: item['sub_category']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'' + imputNamewbView + '\']').val(item['label']);
      $('input[name=\'' + imputNamewbHidden + '\']').val(item['value']);
    }
  });

  var imputNameShopView = 'shopcatsearch' + category_row;
  var imputNameShopHidden = 'cdl_wildberries_category[' + category_row + '][shop]';

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
  // --- Живой поиск категорий WB ---
  category_row++;
});

$('.download-category').on('click', function() {
  $.ajax({
    url: '<?php echo $url_download_category; ?>',
		type: 'post',
    data: 'pass=<?php echo $cdl_wildberries_pass; ?>',
		beforeSend: function() {
			$('.download-category').button('loading');
		},
    success: function(html) {
      document.location.reload();
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
	// Вызываем модальное цен категорий
	$('.category-price').on('click', function(e) {
		e.preventDefault();
		$.get('index.php?route=module/cdl_wildberries/categoryrate&token=<?php echo $token; ?>',
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
		'<div class="modal-dialog" role="document" style="width:80%;">'+
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

// Фильтр экспорта товаров для категории
$('.input-group-addon').click(function() {
	row = $(this).attr('data-lock');
	if ($(this).attr('class') == 'input-group-addon lock') {
		$(this).attr('class', 'input-group-addon');
		$(this).find($(".fa")).removeClass('fa-lock').addClass('fa-unlock');
		$('[data-filter-select=\'' + row + '\']').css({'pointer-events':'auto','opacity':'unset'});
		$('[data-filter-search=\'' + row + '\']').css({'pointer-events':'auto','opacity':'unset'});
		$('input[name=\'cdl_wildberries_category[' + row + '][filter-value]\']').css({'pointer-events':'auto','opacity':'unset'});
	} else {
		$(this).attr('class', 'input-group-addon lock');
		$(this).find($(".fa")).removeClass('fa-unlock').addClass('fa-lock');
		$('[data-filter-select=\'' + row + '\']').css({'pointer-events':'none','opacity':'0.5'});
		$('[data-filter-search=\'' + row + '\']').css({'pointer-events':'none','opacity':'0.5'});
		$('input[name=\'cdl_wildberries_category[' + row + '][filter-value]\']').css({'pointer-events':'none','opacity':'0.5'});
	}
});

$('.filter-exp').change(function() {
	if ($(this).val() == 'attr') {
		$('.filter-add-' + row).append('<input type="text" name="cdl_wildberries_category[' + row + '][attr-name]" class="attr-search" data-filter-search="' + row + '" value="" placeholder="Начните ввод" style="width:50%;" /><input type="text" name="cdl_wildberries_category[' + row + '][filter-value]" value="" placeholder="Значение" style="width:50%;" /><input type="hidden" name="cdl_wildberries_category[' + row + '][filter-attr-id]" value="" />');
	}
	if ($(this).val() == 'all') {
		$('.filter-add-' + row).empty();
	}
});

// Живой поиск атрибуов магазина
$(document).on('click', '.attr-search', function() {
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
	    $('[data-filter-search=\'' + window.row + '\']').val(item['label']);
	    $('input[name=\'cdl_wildberries_category[' + window.row + '][filter-attr-id]\']').val(item['value']);
	  }
	})

	$('.dropdown-menu').addClass('scrollable');
	$('.dropdown-menu').css({'left':'auto','right':'0', 'width':'300px'});
	$('.scrollable').css({'height':'auto','max-height':'15em','overflow-x':'hidden'});
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

		$('#blacklist-product').append('<div id="blacklist-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="cdl_wildberries_blacklist[]" value="' + item['value'] + '" /></div>');

		$('input[name="cdl_wildberries_blacklist"]').val('');

		return false;
	}
});

$('#blacklist-product').on('click', 'i', function() {
		$(this).parent().remove();
});

// Склады
$('.get-sklad').on('click', function() {
	var row_wareh = $(this).attr('data-wareh');
	$.ajax({
		url: '<?php echo $url_get_warehouse; ?>',
		type: 'post',
		data: 'pass=<?php echo $cdl_wildberries_pass; ?>',
		success: function(json) {
			if (json != 'error') {
				var warehouse = $.parseJSON(json);
				$.each(warehouse, function(key, value) {
					$('select[name="cdl_wildberries_warehouses[' + row_wareh + '][sklad_id]"]').append('<option value="' + value['id'] + '">' + value['name'] + ' (' + value['id'] + ')</option>');
				});
				$('.get-sklad').prop('disabled', true);
				$('.clear-select-warehouse').prop('disabled', false);
			}
		}
	});
});

$('.clear-select-warehouse').on('click', function() {
	var row_wareh = $(this).attr('data-wareh');
	$('select[name="cdl_wildberries_warehouses[' + row_wareh + '][sklad_id]"] option').remove();
	$('.clear-select-warehouse').prop('disabled', true);
	$('.get-sklad').prop('disabled', false);
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

		$('[data-sklad-white-list=\'' + sklad_id + '\']').append('<div id="white-skald-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="cdl_wildberries_warehouses[' + sklad_id + '][white_list][]" value="' + item['value'] + '" /></div>');
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

		$('[data-sklad-black-list=\'' + sklad_id + '\']').append('<div id="black-skald-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="cdl_wildberries_warehouses[' + sklad_id + '][black_list][]" value="' + item['value'] + '" /></div>');
		$('.sklad-search-black-list').val('');
		return false;
	}
});

$('[data-sklad-black-list]').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Таблица наценок
var price_row = <?php echo $price_row; ?>;
$(document).delegate('#addPrice', 'click', function addCategory() {
	html =  '<tr id="price_row' + price_row + '">';
	html += '<td><select class="form-control" name="cdl_wildberries_prices['+ price_row +'][els]"><option value="price">Цена</option><option value="manufacturer_id">ID производителя</option><option value="category_id">ID категории</option><option value="product_id">ID товара</option></select></td>';
	html += '<td><input type="text" class="form-control" placeholder="1-100" name="cdl_wildberries_prices['+ price_row +'][value]" value="" /></td>';
	html += '<td><select class="form-control" name="cdl_wildberries_prices['+ price_row +'][action]"><option value="+">+</option><option value="-">-</option><option value="*">*</option></select></td>';
	html += '<td><input type="text" class="form-control" placeholder="1.2 или 50" name="cdl_wildberries_prices['+ price_row +'][rate]" value="" /></td>';
	html += '<td class="text-left"><button type="button" onclick="$(\'#price_row' + price_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#price tbody').append(html);
	price_row++;
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
		$('#list-no-price-update').append('<div id="list-no-price-update' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="cdl_wildberries_product_npu[]" value="' + item['value'] + '" /></div>');
		$('#searh-no-price-update').val('');
		return false;
	}
});

$('#list-no-price-update').on('click', 'i', function() {
	$(this).parent().remove();
});

$('.save-duble').on('click', function() {
  $('button[type="submit"]').trigger('click');
});

// Список не сопоставленных категорий магазина
$('.no-category-save').on('click', function() {
	$.ajax({
		url: '<?php echo $url_no_category_save; ?>',
		success: function(html) {
			alert(html)
		}
	});
});

// Добавить поля настройки атрибутов для склада
var attrRows = '<?php echo $attr_sklad_row; ?>';
$(document).delegate('#addSkladAttr', 'click', function addSkladAttr() {
	var wareh = $(this).attr('data-sklad-tab-wareh');
	html = '<tr class="sklad-attr-row' + attrRows + '">';
	html += '<td><input type="text" class="form-control attr-search-sklad" name="cdl_wildberries_warehouses[' + wareh + '][attribute][' + attrRows + '][name]" data-sklad-attr-row="' + attrRows + '" data-sklad-tab-wareh1="' + wareh + '" placeholder="<?php echo $text_start_typing; ?>" value="" /></td>';
	html += '<input type="hidden" name="cdl_wildberries_warehouses[' + wareh + '][attribute][' + attrRows + '][id]" value="" />';
	html += '<td><input type="text" class="form-control" name="cdl_wildberries_warehouses[' + wareh + '][attribute][' + attrRows + '][value]" value="" /></td>';
	html += '<td><button type="button" onclick="$(\'.sklad-attr-row' + attrRows + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td></tr>';

	$('#sklad-attr' + wareh + ' tbody').append(html);
	attrRows++;
});

// Живой поиск атрибуов магазина
$(document).on('click', '.attr-search-sklad', function() {
	var attrRow = $(this).attr('data-sklad-attr-row');
	var wareh1 = $(this).attr('data-sklad-tab-wareh1');
	$('.attr-search-sklad').autocomplete({
	  'source': function(request, response) {
	    $.ajax({
	      url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
	      dataType: 'json',
	      success: function(json) {
	        // json.push(
	        //   {
	        //     attribute_group: 'Поле Opencart',
	        //     name: 'MPN',
	        //     attribute_id: 'mpn'
	        //   },
	        //   {
	        //     attribute_group: 'Поле Opencart',
	        //     name: 'ISBN',
	        //     attribute_id: 'isbn'
	        //   },
	        //   {
	        //     attribute_group: 'Поле Opencart',
	        //     name: 'JAN',
	        //     attribute_id: 'jan'
	        //   }
	        // );
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
	    $('[data-sklad-attr-row=\'' + attrRow + '\']').val(item['label']);
	    $('input[name=\'cdl_wildberries_warehouses[' + wareh1 + '][attribute][' + attrRow + '][id]').val(item['value']);
	  }
	})

	$('.dropdown-menu').addClass('scrollable');
	$('.dropdown-menu').css({'left':'auto','right':'0', 'width':'300px'});
	$('.scrollable').css({'height':'auto','max-height':'15em','overflow-x':'hidden'});
});

// Проверить токен api
$(document).on('click', '.check-token', function() {
	var token = $('input[name="cdl_wildberries_general_token"]').val();
	$.ajax({
		url: '<?php echo $url_check_token; ?>',
		headers: {
        'Authorization': token,
        'Accept':'application/json',
				'X-Requested-With':'XMLHttpRequest'
    },
		dataType: 'json',
		beforeSend: function() {
			$('.check-token').button('loading');
		},
		complete: function() {
			$('.check-token').button('reset');
		},
		success: function(json) {
			if (json !== '') {
				$('.container-fluid').prepend('<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> Соединение установлено</div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			$('.container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> Ошибка соединения</div>');
		}
	});
});
//--></script>
