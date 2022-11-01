<div class="panel panel-default">
	<div class="panel-heading">
	    <h3 class="panel-title"><img src="<?php echo isset($image)?$image:'';?>" style="margin-top: -3px;" alt="">&nbsp;<strong><?php echo isset($item_name)?$item_name:''; ?></strong></h3>
	</div>
   	<div class="panel-body">
	    <div class="tabbable">
	        <div class="tab-navigation form-inline">
	            <ul class="nav nav-tabs mainMenuTabs">
	                <li class="active"><a href="#tab_gift_settings" data-toggle="tab"><i class="fa fa-gift"></i>&nbsp;<?php echo $gift_options;?> </a></li>
	                <li><a href="#tab_gift_description" data-toggle="tab"><i class="fa fa-comment-o"></i>&nbsp;<?php echo $gift_description;?></a></li>
	            </ul>
	        </div>
	  	</div>
		<div class="tab-content">
	   	 	<div class="tab-pane active" id="tab_gift_settings">
				<table class="giftForm table">
					<tr>
						<td><?php echo $valid_from; ?></td>
						<td>
							<div class="input-group">
                      			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      		<input type="text" class="form-control datetimepicker" name="startDate" value="<?php echo isset($gift['start_date'])?date('Y-m-d H:i', $gift['start_date']):''; ?>" placeholder="<?php echo $valid_from; ?>..."/>
							</div>
							<input type="hidden" id="itemParams" gift-id="<?php echo $gift_id; ?>" item-id="<?php echo isset($item_id)?$item_id:''; ?>" condition-type="<?php echo isset($gift['condition_type'])?$gift['condition_type']:0;?>" />
						</td>
					</tr>
					<tr>
						<td><?php echo $valid_to;?></td>
						<td>
                        	<div class="input-group">
                      			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      			<input type="text" class="form-control datetimepicker" name="endDate" value="<?php echo isset($gift['end_date'])?date('Y-m-d H:i', $gift['end_date']):''; ?>" placeholder="<?php echo $valid_to;?>..."/>
							</div>
						</td>	
					</tr>
					<tr>
						<td><?php echo $entry_sort_order; ?></td>
						<td>
                       		<input class="form-control" name="sort_order" type="number" style="width:100px;" value="<?php echo isset($gift['sort_order'])?$gift['sort_order']:'0'; ?>" placeholder="<?php echo $entry_sort_order; ?>"/>
						</td>	
					</tr>
					<tr>
						<td><?php echo $entry_customer_group; ?></td>
						<td class="customer_group_checkbox">
                            <div class="checkbox" style="margin:10px 0px">
                                <label>
									<input id="0" type="checkbox" name="customer_group[0]" <?php echo isset($customer_group) && (!empty($customer_group) && in_array(0, $customer_group)) ? 'checked="checked"' : ''; ?>/> Гость
                                </label>
                             </div>
                             <?php foreach($customerGroups as $customerGroup) { ?>      
                                <div class="checkbox" style="margin:10px 0px">
                             	   <label>
                                		<input id="<?php echo $customerGroup['customer_group_id']?>" type="checkbox" name="customer_group[<?php echo $customerGroup['customer_group_id']?>]" <?php echo isset($customer_group) && (!empty($customer_group) && in_array($customerGroup['customer_group_id'], $customer_group)) ? 'checked="checked"' : ''; ?>/> <?php echo $customerGroup['name'] ?>
                                	</label>
                                </div>
                             <?php } ?>
						</td>	
					</tr>
					<tr>
						<td class="col-md-4"><?php echo $get_gift_when;?></td>
						<td class="col-md-8">
							<div class="form-group">
								<select name="selectCondition" class="form-control">
									<option value="1" <?php if(isset($gift['condition_type']) && $gift['condition_type'] == '1'){ ?>selected="selected"<?php } ?>>1.&nbsp;<?php echo $condition_total;?></option>
									<option value="2" <?php if(isset($gift['condition_type']) && $gift['condition_type'] == '2'){ ?>selected="selected"<?php } ?>>2.&nbsp;<?php echo $condition_certain;?></option>
									<option value="3" <?php if(isset($gift['condition_type']) && $gift['condition_type'] == '3'){ ?>selected="selected"<?php } ?>>3.&nbsp;<?php echo $condition_some;?></option>
									<option value="4" <?php if(isset($gift['condition_type']) && $gift['condition_type'] == '4'){ ?>selected="selected"<?php } ?>>4.&nbsp;<?php echo $condition_category;?></option>
									<option value="5" <?php if(isset($gift['condition_type']) && $gift['condition_type'] == '5'){ ?>selected="selected"<?php } ?>>5.&nbsp;<?php echo $condition_manufacturer;?></option>								
                                </select>
								<input type="hidden" name="gift-parameters" gift-id="<?php echo $gift_id;?>" />
							</div>
						</td>
					</tr>
				
					<tr class="option-widget" id="total_amount" style="display:none;">
						<td>
							<?php echo $total_amount; ?>
						</td>	
                        <td>
                            <input name="total_amount" style="width:40%; display:inline-block;" type="number" class="form-control" value="<?php echo isset($total)?$total:'0'; ?>"/>&nbsp;&nbsp;&nbsp;и&nbsp;&nbsp;&nbsp;
                            <input name="total_amount_max" style="width:40%; display:inline-block;"  type="number" class="form-control" value="<?php echo isset($total_max)?$total_max:'1000000000'; ?>"/>
                        </td>
                    </tr>
                    
                    <tr class="option-widget" id="total_subtotal" style="display:none;">
						<td>
							<?php echo $text_total_subtotal; ?></br>
                            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_total_subtotal_help; ?></span>
						</td>	
                       <td>
                           <select name="select_total" class="form-control">
									<option value="total" <?php if(!isset($select_total_subtotal) || isset($select_total_subtotal) && $select_total_subtotal == 'total'){ ?>selected="selected"<?php } ?>><?php echo $text_total;?></option>
									<option value="subtotal" <?php if(isset($select_total_subtotal) && $select_total_subtotal == 'subtotal'){ ?>selected="selected"<?php } ?>><?php echo $text_subtotal;?></option>
                            </select>
                        </td>
                    </tr>
					
					<tr style="display:none" id="some-product-selector" class="option-widget" style="position: absolute;">
				      	<td><?php echo $entry_product;?></td>
				    	<td>
				         	<div>
                            	<div id="product_help"><?php echo $some_product_help; ?></div>
				         		<input type="text" name="product" class="form-control" style="z-index: 2000;" placeholder="Вводите название товара.." /><br>
                                <label style="margin-left:15px" for="some_product_quantity">Кол-во:</label>
                            	<input class="form-control" id="some_product_quantity" name="some_product_quantity" type="number" style="width:80px;" value="<?php echo isset($some_product_quantity)?$some_product_quantity:'1'; ?>" placeholder="Кол-во"/>
				         	</div>
				          	<div id="some-gift-product" class="well well-sm scrollbox">
				            	<?php if(isset($some)) foreach ($some as $product) { ?>
				            		<div id="<?php echo $product['product_id']; ?>"> <i class="fa fa-minus-circle"></i><?php echo $product['name']; ?><input type="hidden" name="gift[product][]" value="<?php echo $product['product_id']; ?>" /></div>
				            	<?php } ?>
				            </div>
				     	</td>
				    </tr> 
                    
                    
				    <tr style="display:none" id="certain-product-selector" class="option-widget">
				      	<td><?php echo $entry_product;?></td>
				    	<td>
				         	<div>
                                <div id="product_help"><?php echo $certain_product_help; ?></div>
                            	<input type="text" name="product" style="z-index: 2000;" class="form-control" placeholder="Вводите название товара.." /><br>
                           		<label style="margin-left:15px" for="certain_product_quantity">Кол-во:</label>
                            	<input class="form-control" id="certain_product_quantity" name="certain_product_quantity" type="number" style="width:80px;" value="<?php echo isset($certain_product_quantity)?$certain_product_quantity:'1'; ?>" placeholder="Кол-во"/>
                            </div>
				          	<div id="certain-gift-product" class="well well-sm scrollbox">
				            	<?php if(isset($certain)) foreach ($certain as $product) { ?>
				            		<div id="<?php echo $product['product_id']; ?>"> <i class="fa fa-minus-circle"></i><?php echo $product['name']; ?><input type="hidden" value="<?php echo $product['product_id']; ?>" /></div>
				            	<?php } ?>
				            </div>
				     	</td>
				    </tr>
				    
				    <tr style="display:none" id="category-selector" class="option-widget">
				      	<td>
				      		<?php echo $entry_category; ?>
				      	</td>
				      	<td>
				      		<div><input type="text" name="category" class="form-control" style="z-index: 2000;" placeholder="Вводите название категории.." /></div>
				          	<div id="gift-category" class="well well-sm scrollbox">
				          		<?php  if(isset($categories)) foreach ($categories as $category) { ?>
				           	 		<div id="<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i><?php echo $category['name']; ?><input type="hidden" value="<?php echo $category['category_id']; ?>" /></div>
				            	<?php } ?>
				          	</div>
				      	</td>
				    </tr>
                    
                     <tr style="display:none" id="manufacturer-selector" class="option-widget">
				      	<td>
				      		<?php echo $entry_manufacturer; ?>
				      	</td>
				      	<td>
				      		<div><input type="text" name="manufacturer" class="form-control" style="z-index: 2000;" placeholder="Вводите название производителя.." /></div>
				          	<div id="gift-manufacturer" class="well well-sm scrollbox">
				          		<?php  if(isset($manufacturers)) foreach ($manufacturers as $manufacturer) { ?>
				           	 		<div id="<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i><?php echo $manufacturer['name']; ?><input type="hidden" value="<?php echo $manufacturer['manufacturer_id']; ?>" /></div>
				            	<?php } ?>
				          	</div>
				      	</td>
				    </tr>
                    
				</table>
			</div>
			<!--Gift description -->
			<div class="tab-pane" id="tab_gift_description">
				<div class="tabbable">
			      	<div class="tab-navigation">
			        	<ul class="nav nav-tabs notificationMessageTabs" style="margin-top:10px;">
			          		<?php $class="active"; foreach ($languages as $lang) : ?>
			               		<li class="<?php echo $class; $class='';?>"><a href='#description_lang_<?php echo $lang["code"]; ?>' data-toggle="tab"><img src="<?php echo $lang['flag_url']; ?>" title="<?php echo $lang['name']; ?>" /></a></li>
			          		<?php endforeach; ?> 
			        	</ul>
			      	</div><!-- /.tab-navigation --> 
			      	      	<div class="tab-content">
						<?php $class="active"; foreach ($languages as $lang) :  if(isset($gift['description'])){
							$description = unserialize(base64_decode($gift['description']));
                            
						}?>
			               <div class="tab-pane <?php echo $class; $class='';?>" id='description_lang_<?php echo $lang["code"]; ?>'>		
			                  <div class="col-md-12">
			                    	<textarea id="desc_<?php echo $lang['code']?>" class="form-control"><?php echo isset($description['desc_' . $lang["code"]])?$description['desc_' . $lang["code"]]:''; ?></textarea>	
			                  </div>
			               </div>
			          	<?php endforeach; ?> 
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div> 
<a id="cancelCondition" class="btn btn-warning btn-small" data-dismiss="modal"><?php echo $button_cancel;?></a>&nbsp;
<a id="saveCondition" class="btn btn-success btn-small" gift-id="<?php echo $gift_id;?>"  onclick="saveItem($(this));" ><?php echo $button_save;?></a></td>
<span class="error hidden">&nbsp;&nbsp;<i class='fa fa-warning'></i></span>
<script>
$(document).ready(function() { 
	$('.datetimepicker').datetimepicker({ pickTime: true });
});
</script>
<script type="text/javascript"> 

</script>