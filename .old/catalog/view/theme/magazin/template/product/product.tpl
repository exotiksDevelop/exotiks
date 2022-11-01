<?php echo $header; ?>
<div class="container" id="product_page">
  
  <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?php echo $column_left; ?>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
                <div id="content">
                    <a class="knokanazad" onclick="javascript:history.back();">
                        <span class="strelkanazad"></span>
                        <span class="nazad">назад</span></a>
                    <?php echo $content_top; ?>
                  <div class="row product-box">
                    
                        <div class="krasni col-sm-6"></div>
                        <div class="zeleni col-sm-6"></div>
                      
                                      
                    <div class="col-sm-7">
                    <!--1-->
                        <h1 class="product_h1"><?php echo $heading_title; ?></h1>
                      <?php if ($thumb || $images) { ?>
                      <div class="thumbnails">
                        <?php if ($thumb) { ?>
                        <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" class="img-responsive" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                        <?php } ?>
                        <?php if ($images) { ?><br>
                        <?php foreach ($images as $image) { ?>
                        <span class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></span>
                        <?php } ?>
                        <?php } ?>
                      </div>
                      <?php } ?>
                      <!--1-->
                      
                        </div>
                    <div class="col-sm-5">
                      <?php if ($price) { ?>
                      <div class="label-cena">Цена</div>
                      <ul class="list-unstyled">
                        <?php if (!$special) { ?>
                        <li>
                          <h2><?php echo $price; ?></h2>
                        </li>
                        <?php } else { ?>
                        <li><span style="text-decoration: line-through;"><?php echo $price; ?></span></li>
                        <li>
                        
                          <h2><?php echo $special; ?></h2>
                        </li>
                        <?php } ?>
                        <?php if ($tax) { ?>
                        <li><?php echo $text_tax; ?> <?php echo $tax; ?></li>
                        <?php } ?>
                        <?php if ($points) { ?>
                        <li><?php echo $text_points; ?> <?php echo $points; ?></li>
                        <?php } ?>
                        <?php if ($discounts) { ?>
                        <li>
                          <hr>
                        </li>
                        <?php foreach ($discounts as $discount) { ?>
                        <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
                        <?php } ?>
                        <?php } ?>
                      </ul>
                      <?php } ?>
                      
                      
                      <!--1-->
                      <?php if ($specialTime) { ?>
            				<div data-countdown="<?php echo($specialTime); ?>" class="countdown-product"></div>
            				<?php }
            				else {
            						$data['specialTime'] = false;
            						}?>
                      <!--1-->
                       <ul class="list-unstyled">
                        <?php if ($manufacturer) { ?>
                        <li><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
                        <?php } ?>
                        
                        <?php if ($reward) { ?>
                        <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
                        <?php } ?>
                        <li><b><?php echo $text_stock; ?>:</b> <?php echo $stock; ?></li>
                      </ul>
                      

                      <div id="product">
                      
                        <?php if ($options) { ?>
                        <hr>
                        <h3><?php echo $text_option; ?></h3>
                        <?php foreach ($options as $option) { ?>
                        <?php if ($option['type'] == 'select') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                            <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                            <?php if ($option_value['price']) { ?>
                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                            <?php } ?>
                            </option>
                            <?php } ?>
                          </select>
                        </div>
                        <?php } ?>
                        

                        <?php if ($option['type'] == 'radio') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label"><?php echo $option['name']; ?></label>
                          <div id="input-option<?php echo $option['product_option_id']; ?>">
                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                            <div class="radio">
                              <label>
                                <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                <?php echo $option_value['name']; ?>
                                <?php if ($option_value['price']) { ?>
                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                <?php } ?>
                              </label>
                            </div>
                            
                            <?php } ?>
                            
                          </div>
                        </div>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'checkbox') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label"><?php echo $option['name']; ?></label>
                          <div id="input-option<?php echo $option['product_option_id']; ?>">
                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                <?php echo $option_value['name']; ?>
                                <?php if ($option_value['price']) { ?>
                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                <?php } ?>
                              </label>
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'image') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label"><?php echo $option['name']; ?></label>
                          <div id="input-option<?php echo $option['product_option_id']; ?>">
                            <?php foreach ($option['product_option_value'] as $option_value) { ?>
                            <div class="radio">
                              <label>
                                <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> <?php echo $option_value['name']; ?>
                                <?php if ($option_value['price']) { ?>
                                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                <?php } ?>
                              </label>
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'text') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'textarea') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'file') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label"><?php echo $option['name']; ?></label>
                          <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                          <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'date') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <div class="input-group date">
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                        <?php } ?>
                        

                        <?php if ($option['type'] == 'datetime') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <div class="input-group datetime">
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                        <?php } ?>
                        <?php if ($option['type'] == 'time') { ?>
                        <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                          <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                          <div class="input-group time">
                            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                        <?php if ($recurrings) { ?>
                        <hr>
                        <h3><?php echo $text_payment_recurring ?></h3>
                        <div class="form-group required">
                          <select name="recurring_id" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($recurrings as $recurring) { ?>
                            <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                            <?php } ?>
                          </select>
                          <div class="help-block" id="recurring-description"></div>
                        </div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="control-label" for="input-quantity"><?php echo $entry_qty; ?></label>
                          <input type="number" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
                          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                          <br />
                          <div class="kupitbtn"><span class="korzinaicon"></span><button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_cart; ?></button></div>
                        </div>
                        <?php if ($minimum > 1) { ?>
                        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
                        <?php } ?>
                      </div>
                      <?php if ($review_status) { ?>
                      <div class="rating">
                        <p>
                          <?php for ($i = 1; $i <= 5; $i++) { ?>
                          <?php if ($rating < $i) { ?>
                          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                          <?php } else { ?>
                          <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                          <?php } ?>
                          <?php } ?>
                          <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
                        <hr>
                        <!-- AddThis Button BEGIN -->
                        <div class="addthis_inline_share_toolbox"></div>
                        <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script><!-- Go to www.addthis.com/dashboard to customize your tools -->
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-570c75b682d7176b"></script>
                        <!-- AddThis Button END -->
                      </div>
                      <?php } ?>
                    </div>
                    
                    

                        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                        <script src="//yastatic.net/share2/share.js"></script>
                        <div class="soc-btns"><div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,lj,whatsapp,telegram"></div></div>
                    
                    <div class="krasni col-sm-6"></div>
                        <div class="zeleni col-sm-6"></div>
                  </div>
                    
                    
                    
                    <div class="col-sm-12">
                      
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
                        <?php if ($attribute_groups) { ?>
                        <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
                        <?php } ?>
                        <?php if ($review_status) { ?>
                        <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
                        <?php } ?>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
                        <?php if ($attribute_groups) { ?>
                        <div class="tab-pane" id="tab-specification">
                          <table class="table table-bordered">
                            <?php foreach ($attribute_groups as $attribute_group) { ?>
                            <thead>
                              <tr>
                                <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                              <tr>
                                <td><?php echo $attribute['name']; ?></td>
                                <td><?php echo $attribute['text']; ?></td>
                              </tr>
                              <?php } ?>
                            </tbody>
                            <?php } ?>
                          </table>
                        </div>
                        <?php } ?>
                        <?php if ($review_status) { ?>
                        <div class="tab-pane" id="tab-review">
                          <form class="form-horizontal" id="form-review">
                            <div id="review"></div>
                            <h2><?php echo $text_write; ?></h2>
                            <?php if ($review_guest) { ?>
                            <div class="form-group required">
                              <div class="col-sm-12">
                                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <input type="text" name="name" value="" id="input-name" class="form-control" />
                              </div>
                            </div>
                            <div class="form-group required">
                              <div class="col-sm-12">
                                <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                                <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                                <div class="help-block"><?php echo $text_note; ?></div>
                              </div>
                            </div>
                            <div class="form-group required">
                              <div class="col-sm-12">
                                <label class="control-label"><?php echo $entry_rating; ?></label>
                                &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                                <input type="radio" name="rating" value="1" />
                                &nbsp;
                                <input type="radio" name="rating" value="2" />
                                &nbsp;
                                <input type="radio" name="rating" value="3" />
                                &nbsp;
                                <input type="radio" name="rating" value="4" />
                                &nbsp;
                                <input type="radio" name="rating" value="5" />
                                &nbsp;<?php echo $entry_good; ?></div>
                            </div>
                            <?php echo $captcha; ?>
                            <div class="buttons clearfix">
                              <div class="pull-right">
                                <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                              </div>
                            </div>
                            <?php } else { ?>
                            <?php echo $text_login; ?>
                            <?php } ?>
                          </form>
                        </div>
                        <?php } ?>
                      </div>
                      <div class="krasni col-sm-6"></div>
                        <div class="zeleni col-sm-6"></div>
                    </div>
                    
                    
                  <?php if ($products) { ?>
                  
                  <div class="col-sm-12 row related">
                    <h3><?php echo $text_related; ?></h3>
                    <?php $i = 0; ?>
                    <?php foreach ($products as $product) { ?>
                    
                    <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
                    
                    <div class="<?php echo $class; ?>">
                      <div class="product-thumb transition">
                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                        <div class="caption">
                          <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                          
                          <?php if ($product['rating']) { ?>
                          <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <?php if ($product['rating'] < $i) { ?>
                            <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                            <?php } else { ?>
                            <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                            <?php } ?>
                            <?php } ?>
                          </div>
                          <?php } ?>
                          <?php if ($product['price']) { ?>
                          <p class="price">
                         
                            <?php if (!$product['special']) { ?>
                            
                            <?php echo $product['price']; ?>
                            <?php } else { ?>
                            <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                            <?php } ?>
                            <?php if ($product['tax']) { ?>
                            <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                            <?php } ?>
                          </p>
                          <?php } ?>
                        </div>
                        <div class="button-group kupit">
                          <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>
                         </div>
                      </div>
                    </div>
                    <?php if (($column_left && $column_right) && ($i % 2 == 0)) { ?>
                    <div class="clearfix visible-md visible-sm"></div>
                    <?php } elseif (($column_left || $column_right) && ($i % 3 == 0)) { ?>
                    <div class="clearfix visible-md"></div>
                    <?php } elseif ($i % 4 == 0) { ?>
                    <div class="clearfix visible-md"></div>
                    <?php } ?>
                    <?php $i++; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                  <div style="clear:both;"></div>
                  <?php echo $content_bottom; ?></div>
            </div>
            <script type="text/javascript"><!--
            $('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
            	$.ajax({
            		url: 'index.php?route=product/product/getRecurringDescription',
            		type: 'post',
            		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
            		dataType: 'json',
            		beforeSend: function() {
            			$('#recurring-description').html('');
            		},
            		success: function(json) {
            			$('.alert, .text-danger').remove();
            
            			if (json['success']) {
            				$('#recurring-description').html(json['success']);
            			}
            		}
            	});
            });
            //--></script>
            <script type="text/javascript"><!--
            $('#button-cart').on('click', function() {
            	$.ajax({
            		url: 'index.php?route=checkout/cart/add',
            		type: 'post',
            		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            		dataType: 'json',
            		beforeSend: function() {
            			$('#button-cart').button('loading');
            		},
            		complete: function() {
            			$('#button-cart').button('reset');
            		},
            		success: function(json) {
            			$('.alert, .text-danger').remove();
            			$('.form-group').removeClass('has-error');
            
            			if (json['error']) {
            				if (json['error']['option']) {
            					for (i in json['error']['option']) {
            						var element = $('#input-option' + i.replace('_', '-'));
            
            						if (element.parent().hasClass('input-group')) {
            							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
            						} else {
            							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
            						}
            					}
            				}
            
            				if (json['error']['recurring']) {
            					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
            				}
            
            				// Highlight any found errors
            				$('.text-danger').parent().addClass('has-error');
            			}
            
            			if (json['success']) {
            				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            
            				$('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);
            
            				$('html, body').animate({ scrollTop: 0 }, 'slow');
            
            				$('#cart > ul').load('index.php?route=common/cart/info ul li');
            			}
            		},
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
            	});
            });
            //--></script>
            <script type="text/javascript"><!--
            $('.date').datetimepicker({
            	pickTime: false
            });
            
            $('.datetime').datetimepicker({
            	pickDate: true,
            	pickTime: true
            });
            
            $('.time').datetimepicker({
            	pickDate: false
            });
            
            $('button[id^=\'button-upload\']').on('click', function() {
            	var node = this;
            
            	$('#form-upload').remove();
            
            	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
            
            	$('#form-upload input[name=\'file\']').trigger('click');
            
            	if (typeof timer != 'undefined') {
                	clearInterval(timer);
            	}
            
            	timer = setInterval(function() {
            		if ($('#form-upload input[name=\'file\']').val() != '') {
            			clearInterval(timer);
            
            			$.ajax({
            				url: 'index.php?route=tool/upload',
            				type: 'post',
            				dataType: 'json',
            				data: new FormData($('#form-upload')[0]),
            				cache: false,
            				contentType: false,
            				processData: false,
            				beforeSend: function() {
            					$(node).button('loading');
            				},
            				complete: function() {
            					$(node).button('reset');
            				},
            				success: function(json) {
            					$('.text-danger').remove();
            
            					if (json['error']) {
            						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
            					}
            
            					if (json['success']) {
            						alert(json['success']);
            
            						$(node).parent().find('input').attr('value', json['code']);
            					}
            				},
            				error: function(xhr, ajaxOptions, thrownError) {
            					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            				}
            			});
            		}
            	}, 500);
            });
            //--></script>
            <script type="text/javascript"><!--
            $('#review').delegate('.pagination a', 'click', function(e) {
                e.preventDefault();
            
                $('#review').fadeOut('slow');
            
                $('#review').load(this.href);
            
                $('#review').fadeIn('slow');
            });
            
            $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');
            
            $('#button-review').on('click', function() {
            	$.ajax({
            		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
            		type: 'post',
            		dataType: 'json',
            		data: $("#form-review").serialize(),
            		beforeSend: function() {
            			$('#button-review').button('loading');
            		},
            		complete: function() {
            			$('#button-review').button('reset');
            		},
            		success: function(json) {
            			$('.alert-success, .alert-danger').remove();
            
            			if (json['error']) {
            				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
            			}
            
            			if (json['success']) {
            				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
            
            				$('input[name=\'name\']').val('');
            				$('textarea[name=\'text\']').val('');
            				$('input[name=\'rating\']:checked').prop('checked', false);
            			}
            		}
            	});
            });

$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script>
<?php echo $footer; ?>
