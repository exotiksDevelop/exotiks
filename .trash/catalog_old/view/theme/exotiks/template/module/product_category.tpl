<?php foreach ($products as $k => $product) { ?>

<div class="block_tovarov">
    <h2 style="width: 238px;font-size: 17px;"><?php echo $product[0]['title']; ?></h2>
    <div class="block_tovarov_1">
            <div class="block_tovarov_2">
                <?php foreach ($product as $produk) { ?>
                <div class="block_tovarov_item">
                    <div class="img">
                        <a href="<?php echo $produk['href']; ?>"><img src="<?php echo $produk['thumb']; ?>" alt="<?php echo $produk['name']; ?>" title="<?php echo $produk['name']; ?>" class="img-responsive" /></a>
                    </div>
                        <h3><a href="<?php echo $produk['href']; ?>"><?php echo $produk['name']; ?></a></h3>
                    <div class="cena">
                        <?php if ($produk['price']) { ?>
                        <?php if (!$produk['special']) { ?>
                        <span><?php echo $produk['price']; ?></span>
                        <?php } else { ?>
                            <span><?php echo $produk['special']; ?></span>
                        <?php } ?>								
                    </div>
                    <?php } ?>
                    <div class="kupit">
                        <button type="button" onclick="cart.add('<?php echo $produk['product_id']; ?>');"><?php echo $button_cart; ?></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    
</div>
<?php } ?>