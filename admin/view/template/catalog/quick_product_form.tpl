<link href="view/stylesheet/quick_product_edit.css" type="text/css" rel="stylesheet" />
<div class="modal fade" id="quickeditproduct" role="dialog">
   <div class="modal-dialog" style="width:859px;">
     <div class="modal-content">
        <div class="modal-header hide">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
				<form id="quickproducts" class="form-horizontal">
					<div class="page-header">
						<div class="container-fluid">
							<div class="pull-right">
								<a onclick="$('#quickeditproduct').modal('hide');" class="btn btn-danger"><?php echo $text_close; ?></a>
								<a rel="saveonly" id="saveproducts-saveonly" class="saveproducts btn btn-success"><?php echo $text_save; ?></a>
								<a rel="saveandclose" id="saveproducts-saveandclose"  class="saveproducts btn btn-success"><?php echo $text_closesave; ?></a>
							</div>
							<h3><?php echo $text_form; ?></h3>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="accordion" id="accordion2">
							<?php if(!empty($quick_product_edit_tabs['general']['status'])) { ?>
							<?php if(count($quick_product_edit_tabs['general']) > 1) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_general" data-toggle="collapse" data-parent="#accordion2" href="#tab-general">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_general; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-general" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<ul class="nav nav-tabs" id="language">
												<?php foreach ($languages as $key => $language) { ?>
												<li class="<?php if($key==0){ echo 'active'; }; ?>"><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
												<?php } ?>
											</ul>
											<div class="tab-content">
												<?php foreach ($languages as $key => $language) { ?>
												<div  class="tab-pane <?php if($key==0){ echo 'active'; }; ?>" id="language<?php echo $language['language_id']; ?>">
													<?php if(!empty($quick_product_edit_tabs['general']['name'])) { ?>
													<div class="form-group required">
														<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
														<div class="col-sm-10">
															<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
														</div>
													</div>
													<?php } ?>
													<?php if(!empty($quick_product_edit_tabs['general']['description'])) { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
														<div class="col-sm-10">
															<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
														</div>
													</div>
													<?php } ?>
													<?php if(!empty($quick_product_edit_tabs['general']['meta_title'])) { ?>
													<div class="form-group required">
														<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
														<div class="col-sm-10">
															<input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
														</div>
													</div>
													<?php } ?>
													<?php if(!empty($quick_product_edit_tabs['general']['meta_description'])) { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
														<div class="col-sm-10">
															<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
														</div>
													</div>
													<?php } ?>
													<?php if(!empty($quick_product_edit_tabs['general']['meta_keyword'])) { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
														<div class="col-sm-10">
															<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
														</div>
													</div>
													<?php } ?>
													<?php if(!empty($quick_product_edit_tabs['general']['tag'])) { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
														<div class="col-sm-10">
															<input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
														</div>
													</div>
													<?php } ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['data']['status'])) { ?>
							<?php if(count($quick_product_edit_tabs['data']) > 1) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_data" data-toggle="collapse" data-parent="#accordion2" href="#tab-data">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_data; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-data" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<?php if(!empty($quick_product_edit_tabs['data']['model'])) { ?>
											<div class="form-group required">
												<div class="col-lg-12">
													<label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
													<input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
												</div>
											 </div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['image'])) { ?>
											 <div class="form-group" style="margin-top:10px; margin-bottom:10px;">
												<div class="col-lg-12">
													<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
														<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
												</div>
											 </div>
											 <?php } ?>
											 <?php if(!empty($quick_product_edit_tabs['data']['sku'])) { ?>
											 <div class="form-group">
													<div class="col-lg-12">
														<label class="control-label" for="input-sku"><span data-toggle="tooltip" title="<?php echo $help_sku; ?>"><?php echo $entry_sku; ?></span></label>
															<input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
													</div>
											 </div>
											 <?php } ?>
											 <?php if(!empty($quick_product_edit_tabs['data']['upc'])) { ?>
											 <div class="form-group">
													<div class="col-lg-12">
														<label class="control-label" for="input-upc"><span data-toggle="tooltip" title="<?php echo $help_upc; ?>"><?php echo $entry_upc; ?></span></label>
														<input type="text" name="upc" value="<?php echo $upc; ?>" placeholder="<?php echo $entry_upc; ?>" id="input-upc" class="form-control" />
													</div>
											 </div>
											 <?php } ?>
											 <?php if(!empty($quick_product_edit_tabs['data']['ean'])) { ?>
											<div class="form-group">
												<div class="col-lg-12">
													<label class="control-label" for="input-ean"><span data-toggle="tooltip" title="<?php echo $help_ean; ?>"><?php echo $entry_ean; ?></span></label>
													<input type="text" name="ean" value="<?php echo $ean; ?>" placeholder="<?php echo $entry_ean; ?>" id="input-ean" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['jan'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
												<label class="control-label" for="input-jan"><span data-toggle="tooltip" title="<?php echo $help_jan; ?>"><?php echo $entry_jan; ?></span></label>
												<input type="text" name="jan" value="<?php echo $jan; ?>" placeholder="<?php echo $entry_jan; ?>" id="input-jan" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['isbn'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
												<label class="control-label" for="input-isbn"><span data-toggle="tooltip" title="<?php echo $help_isbn; ?>"><?php echo $entry_isbn; ?></span></label>
												<input type="text" name="isbn" value="<?php echo $isbn; ?>" placeholder="<?php echo $entry_isbn; ?>" id="input-isbn" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['mpn'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-mpn"><span data-toggle="tooltip" title="<?php echo $help_mpn; ?>"><?php echo $entry_mpn; ?></span></label>
													<input type="text" name="mpn" value="<?php echo $mpn; ?>" placeholder="<?php echo $entry_mpn; ?>" id="input-mpn" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['location'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-location"><?php echo $entry_location; ?></label>
													<input type="text" name="location" value="<?php echo $location; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['price'])) { ?>
										 <div class="form-group">
											<div class="col-sm-12">	
												<label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
												<input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
											</div>
										 </div>
										 <?php } ?>
										 <?php if(!empty($quick_product_edit_tabs['data']['tax_class'])) { ?>
										 <div class="form-group">
												<div class="col-sm-12">
												<label class="control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
													<select name="tax_class_id" id="input-tax-class" class="form-control">
														<option value="0"><?php echo $text_none; ?></option>
														<?php foreach ($tax_classes as $tax_class) { ?>
														<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
														<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
														<?php } ?>
														<?php } ?>
													</select>
												</div>
											</div>
											<?php } ?>
										 <?php if(!empty($quick_product_edit_tabs['data']['quantity'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
													<input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['minimum_quantity'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
												<label class="control-label" for="input-minimum"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
												<input type="text" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['subtract_stock'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-subtract"><?php echo $entry_subtract; ?></label>
													<select name="subtract" id="input-subtract" class="form-control">
														<?php if ($subtract) { ?>
														<option value="1" selected="selected"><?php echo $text_yes; ?></option>
														<option value="0"><?php echo $text_no; ?></option>
														<?php } else { ?>
														<option value="1"><?php echo $text_yes; ?></option>
														<option value="0" selected="selected"><?php echo $text_no; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['out_of_stock'])) { ?>
											<div class="form-group">
												 <div class="col-sm-12">
													<label class="control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
													<select name="stock_status_id" id="input-stock-status" class="form-control">
															<?php foreach ($stock_statuses as $stock_status) { ?>
															<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['shipping'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label"><?php echo $entry_shipping; ?></label>
														<label class="radio-inline">
															<?php if ($shipping) { ?>
															<input type="radio" name="shipping" value="1" checked="checked" />
															<?php echo $text_yes; ?>
															<?php } else { ?>
															<input type="radio" name="shipping" value="1" />
															<?php echo $text_yes; ?>
															<?php } ?>
														</label>
														<label class="radio-inline">
															<?php if (!$shipping) { ?>
															<input type="radio" name="shipping" value="0" checked="checked" />
															<?php echo $text_no; ?>
															<?php } else { ?>
															<input type="radio" name="shipping" value="0" />
															<?php echo $text_no; ?>
															<?php } ?>
														</label>
												 </div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['keyword'])) { ?>
											<div class="form-group">
													<div class="col-sm-12">
													<label class="control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
														<input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
													</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['date_available'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">              
													<label class="control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
														<div class="input-group date">
															<input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
															<span class="input-group-btn">
															<button  class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
															</span></div>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['dimension'])) { ?>
											<div class="form-group">
													<div class="col-sm-12">
													<label class="control-label" for="input-length"><?php echo $entry_dimension; ?></label>
														<div class="row">
															<div class="col-sm-4">
																<input type="text" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
															</div>
															<div class="col-sm-4">
																<input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
															</div>
															<div class="col-sm-4">
																<input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
															</div>
														</div>
													</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['length'])) { ?>
											<div class="form-group">
													<div class="col-sm-12">
													<label class="control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
														<select name="length_class_id" id="input-length-class" class="form-control">
															<?php foreach ($length_classes as $length_class) { ?>
															<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
															<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['weight'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-weight"><?php echo $entry_weight; ?></label>
													<input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['weight_class'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
													<select name="weight_class_id" id="input-weight-class" class="form-control">
														<?php foreach ($weight_classes as $weight_class) { ?>
														<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
														<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
														<?php } ?>
														<?php } ?>
													</select>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['product_status'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
													<select name="status" id="input-status" class="form-control">
														<?php if ($status) { ?>
														<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php } else { ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['data']['sort_order'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
													<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
												</div>
											</div>
											<?php } ?>
											</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['links']['status'])) { ?>
							<?php if(count($quick_product_edit_tabs['links']) > 1) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_links" data-toggle="collapse" data-parent="#accordion2" href="#tab-link">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_links; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-link" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<?php if(!empty($quick_product_edit_tabs['links']['manufacturer'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label>
													<input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
													<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['links']['category'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
													<input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
													<div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
														<?php foreach ($product_categories as $product_category) { ?>
														<div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
															<input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['links']['filter'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-filter"><span data-toggle="tooltip" title="<?php echo $help_filter; ?>"><?php echo $entry_filter; ?></span></label>
													<input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
													<div id="product-filter" class="well well-sm" style="height: 150px; overflow: auto;">
														<?php foreach ($product_filters as $product_filter) { ?>
														<div id="product-filter<?php echo $product_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_filter['name']; ?>
															<input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['links']['store'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label"><?php echo $entry_store; ?></label>
													<div class="well well-sm" style="height: 150px; overflow: auto;">
														<div class="checkbox">
															<label>
																<?php if (in_array(0, $product_store)) { ?>
																<input type="checkbox" name="product_store[]" value="0" checked="checked" />
																<?php echo $text_default; ?>
																<?php } else { ?>
																<input type="checkbox" name="product_store[]" value="0" />
																<?php echo $text_default; ?>
																<?php } ?>
															</label>
														</div>
														<?php foreach ($stores as $store) { ?>
														<div class="checkbox">
															<label>
																<?php if (in_array($store['store_id'], $product_store)) { ?>
																<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
																<?php echo $store['name']; ?>
																<?php } else { ?>
																<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
																<?php echo $store['name']; ?>
																<?php } ?>
															</label>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['links']['download'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-download"><span data-toggle="tooltip" title="<?php echo $help_download; ?>"><?php echo $entry_download; ?></span></label>
													<input type="text" name="download" value="" placeholder="<?php echo $entry_download; ?>" id="input-download" class="form-control" />
													<div id="product-download" class="well well-sm" style="height: 150px; overflow: auto;">
														<?php foreach ($product_downloads as $product_download) { ?>
														<div id="product-download<?php echo $product_download['download_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_download['name']; ?>
															<input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<?php } ?>
											<?php if(!empty($quick_product_edit_tabs['links']['product_related'])) { ?>
											<div class="form-group">
												<div class="col-sm-12">
													<label class="control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
													<input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
													<div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
														<?php foreach ($product_relateds as $product_related) { ?>
														<div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
															<input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['attribute']['status'])) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_attribute" data-toggle="collapse" data-parent="#accordion2" href="#tab-attribute">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_attribute; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-attribute" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<div class="table-responsive">
												<table id="attribute" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<td class="text-left"><?php echo $entry_attribute; ?></td>
															<td class="text-left"><?php echo $entry_text; ?></td>
															<td></td>
														</tr>
													</thead>
													<tbody>
														<?php $attribute_row = 0; ?>
														<?php foreach ($product_attributes as $product_attribute) { ?>
														<tr id="attribute-row<?php echo $attribute_row; ?>">
															<td class="text-left" style="width: 40%;"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
																<input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
															<td class="text-left"><?php foreach ($languages as $language) { ?>
																<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
																	<textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
																</div>
																<?php } ?></td>
															<td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
														</tr>
														<?php $attribute_row++; ?>
														<?php } ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="2"></td>
															<td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['option']['status'])) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_option" data-toggle="collapse" data-parent="#accordion2" href="#tab-option">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_option; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-option" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
												<div class="row">
													<div class="col-sm-2">
														<ul class="nav nav-pills nav-stacked" id="option">
															<?php $option_row = 0; ?>
															<?php foreach ($product_options as $key => $product_option) { ?>
															<li class="<?php  if($key==0){ echo 'active'; } ?>"><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
															<?php $option_row++; ?>
															<?php } ?>
															<li>
																<input type="text" name="option" value="" placeholder="<?php echo $entry_option; ?>" id="input-option" class="form-control" />
															</li>
														</ul>
													</div>
													<div class="col-sm-10">
														<div class="tab-content">
															<?php $option_row = 0; ?>
															<?php $option_value_row = 0; ?>
															<?php foreach ($product_options as $key=> $product_option) { ?>
															<div class="tab-pane <?php  if($key==0){ echo 'active'; } ?>" id="tab-option<?php echo $option_row; ?>">
																<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
																<input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
																<input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
																<input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
																<div class="form-group">
																	<label class="col-sm-2 control-label" for="input-required<?php echo $option_row; ?>"><?php echo $entry_required; ?></label>
																	<div class="col-sm-10">
																		<select name="product_option[<?php echo $option_row; ?>][required]" id="input-required<?php echo $option_row; ?>" class="form-control">
																			<?php if ($product_option['required']) { ?>
																			<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																			<option value="0"><?php echo $text_no; ?></option>
																			<?php } else { ?>
																			<option value="1"><?php echo $text_yes; ?></option>
																			<option value="0" selected="selected"><?php echo $text_no; ?></option>
																			<?php } ?>
																		</select>
																	</div>
																</div>
																<?php if ($product_option['type'] == 'text') { ?>
																<div class="form-group">
																	<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
																	<div class="col-sm-10">
																		<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control" />
																	</div>
																</div>
																<?php } ?>
																<?php if ($product_option['type'] == 'textarea') { ?>
																<div class="form-group">
																	<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
																	<div class="col-sm-10">
																		<textarea name="product_option[<?php echo $option_row; ?>][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control"><?php echo $product_option['value']; ?></textarea>
																	</div>
																</div>
																<?php } ?>
																<?php if ($product_option['type'] == 'file') { ?>
																<div class="form-group" style="display: none;">
																	<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
																	<div class="col-sm-10">
																		<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control" />
																	</div>
																</div>
																<?php } ?>
																<?php if ($product_option['type'] == 'date') { ?>
																<div class="form-group">
																	<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
																	<div class="col-sm-3">
																		<div class="input-group date">
																			<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value<?php echo $option_row; ?>" class="form-control" />
																			<span class="input-group-btn">
																			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																			</span></div>
																	</div>
																</div>
																<?php } ?>
																<?php if ($product_option['type'] == 'time') { ?>
																<div class="form-group">
																	<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
																	<div class="col-sm-10">
																		<div class="input-group time">
																			<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control" />
																			<span class="input-group-btn">
																			<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																			</span></div>
																	</div>
																</div>
																<?php } ?>
																<?php if ($product_option['type'] == 'datetime') { ?>
																<div class="form-group">
																	<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
																	<div class="col-sm-10">
																		<div class="input-group datetime">
																			<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control" />
																			<span class="input-group-btn">
																			<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																			</span></div>
																	</div>
																</div>
																<?php } ?>
																<?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
																<div class="table-responsive">
																	<table id="option-value<?php echo $option_row; ?>" class="table table-striped table-bordered table-hover">
																		<thead>
																			<tr>
																				<td class="text-left"><?php echo $entry_option_value; ?></td>
																				<td class="text-right"><?php echo $entry_quantity; ?></td>
																				<td class="text-left"><?php echo $entry_subtract; ?></td>
																				<td class="text-right"><?php echo $entry_price; ?></td>
																				<td class="text-right"><?php echo $entry_option_points; ?></td>
																				<td class="text-right"><?php echo $entry_weight; ?></td>
																				<td></td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
																			<tr id="option-value-row<?php echo $option_value_row; ?>">
																				<td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]" class="form-control">
																						<?php if (isset($option_values[$product_option['option_id']])) { ?>
																						<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																						<?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
																						<option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
																						<?php } else { ?>
																						<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
																						<?php } ?>
																						<?php } ?>
																						<?php } ?>
																					</select>
																					<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
																				<td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
																				<td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" class="form-control">
																						<?php if ($product_option_value['subtract']) { ?>
																						<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																						<option value="0"><?php echo $text_no; ?></option>
																						<?php } else { ?>
																						<option value="1"><?php echo $text_yes; ?></option>
																						<option value="0" selected="selected"><?php echo $text_no; ?></option>
																						<?php } ?>
																					</select></td>
																				<td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" class="form-control">
																						<?php if ($product_option_value['price_prefix'] == '+') { ?>
																						<option value="+" selected="selected">+</option>
																						<?php } else { ?>
																						<option value="+">+</option>
																						<?php } ?>
																						<?php if ($product_option_value['price_prefix'] == '-') { ?>
																						<option value="-" selected="selected">-</option>
																						<?php } else { ?>
																						<option value="-">-</option>
																						<?php } ?>
																					</select>
																					<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
																				<td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" class="form-control">
																						<?php if ($product_option_value['points_prefix'] == '+') { ?>
																						<option value="+" selected="selected">+</option>
																						<?php } else { ?>
																						<option value="+">+</option>
																						<?php } ?>
																						<?php if ($product_option_value['points_prefix'] == '-') { ?>
																						<option value="-" selected="selected">-</option>
																						<?php } else { ?>
																						<option value="-">-</option>
																						<?php } ?>
																					</select>
																					<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>
																				<td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" class="form-control">
																						<?php if ($product_option_value['weight_prefix'] == '+') { ?>
																						<option value="+" selected="selected">+</option>
																						<?php } else { ?>
																						<option value="+">+</option>
																						<?php } ?>
																						<?php if ($product_option_value['weight_prefix'] == '-') { ?>
																						<option value="-" selected="selected">-</option>
																						<?php } else { ?>
																						<option value="-">-</option>
																						<?php } ?>
																					</select>
																					<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>
																				<td class="text-left"><button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row<?php echo $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
																			</tr>
																			<?php $option_value_row++; ?>
																			<?php } ?>
																		</tbody>
																		<tfoot>
																			<tr>
																				<td colspan="6"></td>
																				<td class="text-left"><button type="button" onclick="addOptionValue('<?php echo $option_row; ?>');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
																			</tr>
																		</tfoot>
																	</table>
																</div>
																<select id="option-values<?php echo $option_row; ?>" style="display: none;">
																	<?php if (isset($option_values[$product_option['option_id']])) { ?>
																	<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																	<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
																	<?php } ?>
																	<?php } ?>
																</select>
																<?php } ?>
															</div>
															<?php $option_row++; ?>
															<?php } ?>
														</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['discount']['status'])) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_discount" data-toggle="collapse" data-parent="#accordion2" href="#tab-discounts">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_discount; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-discounts" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<div class="table-responsive">
												<table id="discount" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<td class="text-left"><?php echo $entry_customer_group; ?></td>
															<td class="text-right"><?php echo $entry_quantity; ?></td>
															<td class="text-right"><?php echo $entry_priority; ?></td>
															<td class="text-right"><?php echo $entry_price; ?></td>
															<td class="text-left"><?php echo $entry_date_start; ?></td>
															<td class="text-left"><?php echo $entry_date_end; ?></td>
															<td></td>
														</tr>
													</thead>
													<tbody>
														<?php $discount_row = 0; ?>
														<?php foreach ($product_discounts as $product_discount) { ?>
														<tr id="discount-row<?php echo $discount_row; ?>">
															<td class="text-left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control">
																	<?php foreach ($customer_groups as $customer_group) { ?>
																	<?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
																	<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
																	<?php } else { ?>
																	<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
																	<?php } ?>
																	<?php } ?>
																</select></td>
															<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
															<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
															<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
															<td class="text-left" style="width: 20%;"><div class="input-group date">
																	<input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
																	<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																	</span></div></td>
															<td class="text-left" style="width: 20%;"><div class="input-group date">
																	<input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
																	<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																	</span></div></td>
															<td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
														</tr>
														<?php $discount_row++; ?>
														<?php } ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="6"></td>
															<td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_discount_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['special']['status'])) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_special" data-toggle="collapse" data-parent="#accordion2" href="#tab-special">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_special; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-special" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<div class="table-responsive">
												<table id="special" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<td class="text-left"><?php echo $entry_customer_group; ?></td>
															<td class="text-right"><?php echo $entry_priority; ?></td>
															<td class="text-right"><?php echo $entry_price; ?></td>
															<td class="text-left"><?php echo $entry_date_start; ?></td>
															<td class="text-left"><?php echo $entry_date_end; ?></td>
															<td></td>
														</tr>
													</thead>
													<tbody>
														<?php $special_row = 0; ?>
														<?php foreach ($product_specials as $product_special) { ?>
														<tr id="special-row<?php echo $special_row; ?>">
															<td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control">
																	<?php foreach ($customer_groups as $customer_group) { ?>
																	<?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
																	<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
																	<?php } else { ?>
																	<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
																	<?php } ?>
																	<?php } ?>
																</select></td>
															<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
															<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
															<td class="text-left" style="width: 20%;"><div class="input-group date">
																	<input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
																	<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																	</span></div></td>
															<td class="text-left" style="width: 20%;"><div class="input-group date">
																	<input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
																	<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																	</span></div></td>
															<td class="text-left"><button type="button" onclick="$('#special-row<?php echo $special_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
														</tr>
														<?php $special_row++; ?>
														<?php } ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="5"></td>
															<td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="<?php echo $button_special_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['image']['status'])) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_image" data-toggle="collapse" data-parent="#accordion2" href="#tab-image">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_image; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-image" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<div class="table-responsive">
												<table id="images" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<td class="text-left"><?php echo $entry_image; ?></td>
															<td class="text-right"><?php echo $entry_sort_order; ?></td>
															<td></td>
														</tr>
													</thead>
													<tbody>
														<?php $image_row = 0; ?>
														<?php foreach ($product_images as $product_image) { ?>
														<tr id="image-row<?php echo $image_row; ?>">
															<td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
															<td class="text-right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
															<td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
														</tr>
														<?php $image_row++; ?>
														<?php } ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="2"></td>
															<td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php if(!empty($quick_product_edit_tabs['rewards']['status'])) { ?>
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed tab_rewards" data-toggle="collapse" data-parent="#accordion2" href="#tab-rewards">
										<div class="panel-heading">
										<h3 class="panel-title"><?php echo $tab_image; ?></h3>
										</div>
									</a>
								</div>
								<div id="tab-rewards" class="accordion-body collapse">
									<div class="accordion-inner">
										<div class="panel-body">
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
												<div class="col-sm-10">
													<input type="text" name="points" value="<?php echo $points; ?>" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
												</div>
											</div>
											<div class="table-responsive">
												<table class="table table-bordered table-hover">
													<thead>
														<tr>
															<td class="text-left"><?php echo $entry_customer_group; ?></td>
															<td class="text-right"><?php echo $entry_reward; ?></td>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($customer_groups as $customer_group) { ?>
														<tr>
															<td class="text-left"><?php echo $customer_group['name']; ?></td>
															<td class="text-right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" class="form-control" /></td>
														</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</form>
        </div>
      </div>
   </div>
</div>
<script type="text/javascript"><!--
$('.saveproducts').on('click',function(){
	$('#quickproducts .note-editable').each(function() {
		$(this).parent().siblings('textarea').html($(this).html());
		$(this).parent().siblings('textarea').val($(this).html());
	});

	var rel = $(this).attr('rel');
	
	$.ajax({
		<?php if(!empty($product_id)) { ?>
		url: 'index.php?route=catalog/quickproduct/validateForm&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>',
		<?php } else { ?>
		url: 'index.php?route=catalog/quickproduct/validateForm&token=<?php echo $token; ?>',
		<?php } ?>
		type: 'post',
		dataType: 'json',
		data: $('#quickproducts input[type=\'text\'], #quickproducts input[type=\'hidden\'], #quickproducts input[type=\'radio\']:checked, #quickproducts input[type=\'checkbox\']:checked, #quickproducts select, #quickproducts textarea'),
		beforeSend: function() {
			$('#saveproducts-'+ rel).button('loading');
		},
		complete: function() {
			$('#saveproducts-'+ rel).button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			
			if(json['warning']) {
				$('#quickproducts .page-header').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> '+ json['warning'] +' <button type="button" class="close" data-dismiss="alert">&times;</button> </div>');
			}
			
			
			if(json['success'] && rel=='saveonly') {
				$('#quickproducts .page-header').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +' <button type="button" class="close" data-dismiss="alert">&times;</button> </div>');
			}
			
			if(json['success'] && rel=='saveandclose') {
				$('#quickeditproduct').modal('hide');
				
				$('.container-fluid .panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +' <button type="button" class="close" data-dismiss="alert">&times;</button> </div>');
			}
			
			if(json['success']) {
				loadProductLists();
			}
		}
	});
});
</script>
<script type="text/javascript"><!--
function loadProductLists() {
	$.ajax({
		type: 'GET',
		url: '<?php echo $list_action; ?>',
		beforeSend: function() {
		},
		complete: function(data) {
			$('#form-product table').html($("#form-product table", data.responseText).html());
			$('#form-product').siblings('.row').html($("#form-product", data.responseText).siblings('.row').html());
			
		}
	});
}
</script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 200});
<?php } ?>

var attribute_row = '<?php echo (!empty($attribute_row) ?  $attribute_row : 0); ?>';

function addAttribute() {
    html  = '<tr id="attribute-row' + attribute_row + '">';
	html += '  <td class="text-left" style="width: 20%;"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '  <td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
    <?php } ?>
	html += '  </td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

	$('#attribute tbody').append(html);

	attributeautocomplete(attribute_row);

	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
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
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
		}
	});
}

$('#attribute tbody tr').each(function(index, element) {
	attributeautocomplete(index);
});

$('#quickeditproduct').modal('show');

// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					manufacturer_id: 0,
					name: '<?php echo $text_none; ?>'
				});

				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'manufacturer\']').val(item['label']);
		$('input[name=\'manufacturer_id\']').val(item['value']);
	}
});

// Category
$('input[name=\'category\']').autocomplete({
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
		$('input[name=\'category\']').val('');

		$('#product-category' + item['value']).remove();

		$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
	}
});

$('#product-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Filter
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');

		$('#product-filter' + item['value']).remove();

		$('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
	}
});

$('#product-filter').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Downloads
$('input[name=\'download\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['download_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'download\']').val('');

		$('#product-download' + item['value']).remove();

		$('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');
	}
});

$('#product-download').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
		$('input[name=\'related\']').val('');

		$('#product-related' + item['value']).remove();

		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
	}
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
</script>
  <script type="text/javascript"><!--
var option_row = '<?php echo (!empty($option_row ) ?  $option_row  : 0); ?>';
$('input[name=\'option\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item['category'],
						label: item['name'],
						value: item['option_id'],
						type: item['type'],
						option_value: item['option_value']
					}
				}));
			}
		});
	},
	'select': function(item) {
		html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
		html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

		html += '	<div class="form-group">';
		html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
		html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
		html += '	      <option value="1"><?php echo $text_yes; ?></option>';
		html += '	      <option value="0"><?php echo $text_no; ?></option>';
		html += '	  </select></div>';
		html += '	</div>';

		if (item['type'] == 'text') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
			html += '	</div>';
		}

		if (item['type'] == 'textarea') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control"></textarea></div>';
			html += '	</div>';
		}

		if (item['type'] == 'file') {
			html += '	<div class="form-group" style="display: none;">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
			html += '	</div>';
		}

		if (item['type'] == 'date') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
			html += '	</div>';
		}

		if (item['type'] == 'time') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
			html += '	</div>';
		}

		if (item['type'] == 'datetime') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
			html += '	</div>';
		}

		if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
			html += '<div class="table-responsive">';
			html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="text-right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="text-left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="text-right"><?php echo $entry_price; ?></td>';
			html += '        <td class="text-right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="text-right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '  	 <tbody>';
			html += '    </tbody>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';

            html += '  <select id="option-values' + option_row + '" style="display: none;">';

            for (i = 0; i < item['option_value'].length; i++) {
				html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
            }

            html += '  </select>';
			html += '</div>';
		}

		$('#tab-option .tab-content').append(html);

		$('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#option a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');

		$('#option a[href=\'#tab-option' + option_row + '\']').tab('show');

		$('.date').datetimepicker({
			pickTime: false
		});

		$('.time').datetimepicker({
			pickDate: false
		});

		$('.datetime').datetimepicker({
			pickDate: true,
			pickTime: true
		});

		option_row++;
	}
});
//--></script>
  <script type="text/javascript"><!--
var option_value_row = '<?php echo (!empty($option_value_row ) ?  $option_value_row  : 0); ?>';

function addOptionValue(option_row) {
	html  = '<tr id="option-value-row' + option_value_row + '">';
	html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
	html += $('#option-values' + option_row).html();
	html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
	html += '    <option value="1"><?php echo $text_yes; ?></option>';
	html += '    <option value="0"><?php echo $text_no; ?></option>';
	html += '  </select></td>';
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#option-value' + option_row + ' tbody').append(html);
        $('[rel=tooltip]').tooltip();

	option_value_row++;
}
//--></script>
  <script type="text/javascript"><!--
var discount_row = '<?php echo (!empty($discount_row ) ?  $discount_row  : 0); ?>';

function addDiscount() {
	html  = '<tr id="discount-row' + discount_row + '">';
    html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#discount tbody').append(html);

	$('.date').datetimepicker({
		pickTime: false
	});

	discount_row++;
}
//--></script>
  <script type="text/javascript"><!--
var special_row = '<?php echo (!empty($special_row ) ?  $special_row  : 0); ?>';

function addSpecial() {
	html  = '<tr id="special-row' + special_row + '">';
    html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';
    html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#special tbody').append(html);

	$('.date').datetimepicker({
		pickTime: false
	});

	special_row++;
}
//--></script>
  <script type="text/javascript"><!--
var image_row = '<?php echo (!empty($image_row ) ?  $image_row  : 0); ?>';

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#images tbody').append(html);

	image_row++;
}
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<script type="text/javascript"><!--
setTimeout(function(){ 
$('#language a:first').tab('show');
$('#option a:first').tab('show');
}, 1000);
//--></script>
<script type="text/javascript"><!--
var $myGroup = $('#accordion2');
$myGroup.on('show.bs.collapse','.collapse', function() {
	$myGroup.find('.collapse.in').collapse('hide');
});
//--></script>
<script type="text/javascript"><!--
// FOR SETTING MODAL SCROLL 
setInterval(function(){ 
	if($('#quickeditproduct').hasClass('in') && !$('body').hasClass('modal-open')) {
		$('body').addClass('modal-open');
		$('body').css("padding-right", "17px");
	}
}, 1000);
//--></script>
<?php if($quick_product_edit_open) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	setTimeout(function(){
		$('.<?php echo $quick_product_edit_open; ?>').trigger('click');
	}, 500);
});
//--></script>
<?php } ?>