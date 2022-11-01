<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<p><?php echo $text_shipping_method; ?></p>
<?php foreach ($shipping_methods as $shipping_method) { ?>
<p><strong><?php echo $shipping_method['title']; ?></strong></p>
<?php echo $shipping_method['more_data']; ?>
<?php if (!$shipping_method['error']) { ?>
<?php foreach ($shipping_method['quote'] as $quote) { ?>
<div class="radio">
  <label>
    <?php if (empty($quote['dummy']) && ($quote['code'] == $code || !$code)) { ?>
    <?php $code = $quote['code']; ?>
    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> checked="checked" />
    <?php } else { ?>
    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> />
    <?php } ?>
    
          <?php if (!empty($quote['image'])) { ?>
          <img src="<?php echo $quote['image'] ?>" <?php echo !empty($quote['image_style']) ? 'style="'.$quote['image_style'].'"' : '' ?>>
          <?php } ?>
          
			<?php /* start russianpost2 */ ?>
			
				<?php if( !empty($quote['dpd_html']) ) { ?>
					<style>.simplecheckout-methods-table td.code { vertical-align: top; }</style>
					<?php echo $quote['dpd_title_short']; ?><?php echo $quote['text']; ?>
					<?php echo $quote['dpd_html']; ?>
					
				<?php } elseif( !empty($quote['html_image']) ) { ?>
					<?php echo $quote['html_image']; ?> - <?php echo $quote['text']; ?> 
				<?php } else { ?> 
						
			<?php /* start russianpost2 */ ?>
			  <?php if( !empty($quote['dpd_html']) ) { ?>
				<?php echo $quote['dpd_title_short']; ?> - <span id="russianpost2_cost_text<?php 
					echo str_replace("russianpost2.pvz", "", $quote['code']); ?>"><?php echo $quote['text']; ?></span>
				<?php echo $quote['dpd_html']; ?>
			  <?php } elseif( !empty($quote['html_image']) ) { ?>
				<?php echo $quote['html_image']; ?> - <?php echo $quote['text']; ?>
			  <?php } else { ?>
				<?php echo $quote['title']; ?> - <?php echo $quote['text']; ?>
			  <?php } ?>
		    <?php /* end russianpost2 */ ?>
			
				<?php } ?> 
				
		    <?php /* end russianpost2 */ ?>
			
  <?php if (isset($quote['description'])) { ?>
  <tr>
    <td></td>
    <td><?php echo $quote['description']; ?></td>
    <td style="text-align: right;"></td>
  </tr>
  <?php } ?>
            <?php if (isset($quote['bb_html'])) { ?>
                        <br><?php echo $quote['bb_html']; ?>
            <?php } ?>
            </label>
</div>

          <?php if (!empty($quote['description'])) { ?>
          <div>
            <label for="<?php echo $quote['code'] ?>">
              <?php echo $quote['description'] ?>
            </label>
          </div>
          <?php } ?>
        
<?php } ?>
<?php } else { ?>
<div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
<?php } ?>
<?php } ?>
<?php } ?>
<p><strong><?php echo $text_comments; ?></strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
</p>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" data-loading-text="<?php echo $text_loading; ?>" class="button" />
  </div>
</div>
