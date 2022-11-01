<?php if ( ($data['giftTeaser']['Enabled'] != 'no') ) { ?>
<div class="gift_option">
<h2><?php echo $text_option_heading; ?></h2>  
    
    <?php if ($gift) { ?>
    	<form method="post" id="GiftTeaserOptionsForm">
               		
           		<?php if ($gift['thumb']) { ?>
					<div class="options_image"><a href="<?php echo $gift['href']; ?>"><img src="<?php echo $gift['thumb']; ?>" alt="<?php echo $gift['name']; ?>" /></a></div>
				<?php } ?>
               
					<div class="gift_options_product_field"><a href="<?php echo $gift['href']; ?>"><?php echo $gift['name']; ?></a>
                    
					<?php if ($gift['options']) { ?>
                 		<div class="gift_options">
                            <?php foreach ($gift['options'] as $option) { ?>
                                <?php if ($option['type'] == 'select') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <select class="form-control" name="option[<?php echo $option['product_option_id']; ?>]">
                                            <option value=""><?php echo $text_select; ?></option>
                                            <?php foreach ($option['option_value'] as $option_value) { ?>
                                                <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'radio') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                                            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                            </label>
                                            <br />
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'checkbox') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                            <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                                            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                            </label>
                                            <br />
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'image') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <table class="option-image">
                                        <?php foreach ($option['option_value'] as $option_value) { ?>
                                            <tr>
                                                <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
                                                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
                                                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                                </label></td>
                                            </tr>
                                        <?php } ?>
                                        </table>
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'text') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'textarea') { ?>
                                    <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="16" rows="5"><?php echo $option['option_value']; ?></textarea>
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'file') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
                                        <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'date') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'datetime') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
                                    </div>
                                <?php } ?>
                                <?php if ($option['type'] == 'time') { ?>
                                    <div id="bundle_option-<?php echo $option['product_option_id']; ?>" class="option">
                                        <?php if ($option['required']) { ?>
                                            <span class="required">*</span>
                                        <?php } ?>
                                        <b><?php echo $option['name']; ?>:</b><br />
                                        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
                                    </div>
                                <?php } ?>
                            <?php } ?>
                 		</div>
                  <?php } ?>
			</div>
        <input id="gift_id" type="hidden" name="product_id" value="<?php echo $gift['product_id']; ?>" />
        
        <div style="clear:both"></div> </form><br />
       
       	<div class="gift_options_footer"><div class="gift_options_continue">
        <a id="add_gift_with_option" class="btn btn-primary"><?php echo $Continue; ?></a></div></div>

		<script>
		$('#add_gift_with_option').bind('click', function() { 
			$.ajax({
				url: 'index.php?route=checkout/cart/GiftAdd',
				type: 'post',
				data: $('.gift_option input[type=\'text\'], .gift_option input[type=\'hidden\'], .gift_option input[type=\'radio\']:checked, .gift_option input[type=\'checkbox\']:checked, .gift_option select, .gift_option textarea'),
				dataType: 'json',
				success: function(json) {
					$('.success, .warning, .attention, information, .error').remove();
					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
							}
						}
						
						if (json['error']['profile']) {
							$('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
						}
					} 
					
					if (json['success']) {
						$.fancybox.close();
						$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
							
						$('.success').fadeIn('slow');
							
						$('html, body').animate({ scrollTop: 0 }, 'slow'); 
					}	
				}
			});
		});
        </script>
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

   <?php } ?>
</div>
<?php } ?>