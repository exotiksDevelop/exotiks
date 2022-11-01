<div class="block_tovarov">
    <h2><?php echo $heading_title; ?></h2>
    <div class="block_tovarov_1">
            <div class="block_tovarov_2">
                <?php foreach ($products as $product) { ?>
                <div class="block_tovarov_item">
                    <div class="img">
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
                    </div>
                        <h3><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
                    <div class="cena">
                        <?php if ($product['price']) { ?>
                        
                        <?php if (!$product['special']) { ?>
                        
                        <span><?php echo $product['price']; ?></span>
                        <?php } else { ?>
                            <span><?php echo $product['special']; ?></span>
                        <?php } ?>								
                    </div>
                    <?php } ?>
                    <div class="kupit">
                        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><?php echo $button_cart; ?></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    
</div>