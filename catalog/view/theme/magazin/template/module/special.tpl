<div class="col-md-3  col-sm-6 col-xs-6">
    <div class="akciya_title">
        <h2><?php echo $heading_title; ?></h2>
        <span>Успейте заказать <br/> по выгодной цене</span>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-6">
    <div class="akciya_img_cena">
    <?php foreach ($products as $product) { ?>
    <div class="akciya_img">
        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
    </div>
    <div class="akciya_cena">
        <h2><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>    
        <?php if ($product['price']) { ?> 
              
              <?php if (!$product['special']) { ?>
              
              <?php echo $product['price']; ?>
              <?php } else { ?>
                <s><?php echo $product['price']; ?></s><br/>
                <span><?php echo $product['special']; ?></span>          
              <?php } ?> 
            <?php } ?>
        
       
       </div>
    </div>
</div>
<div class="col-md-3  col-sm-6 col-xs-6">
			<div class="schetchik">
			
        <?php if ($product['specialTime']) { ?>
        <div data-countdown="<?php echo($product['specialTime']); ?>" class="countdown"></div>
        <?php }?>

			</div> 
</div>
<div class="col-md-2  col-sm-6 col-xs-6">
			<div class="kupit">
				<button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><?php echo $button_cart; ?></button>
			</div>
</div>
            
<?php } ?>