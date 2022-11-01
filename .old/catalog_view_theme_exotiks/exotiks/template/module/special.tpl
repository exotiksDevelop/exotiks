<section class="fundraiser">
    <div class="container">

        <?foreach ($products as $product) {?>
        <h2 class="fundraiser__title"><a href="<?=$product['href']?>" title="<?=$heading_title?>"
                class="fundraiser__title-link">
                <?//=$heading_title?><?=$product['name']?></a></h2>
        <span class="fundraiser__block">
            <span class="fundraiser__block-sub sub-1">Новая<br>цена:</span>
            <span class="fundraiser__block-sub sub-2">
                <?
                    if ($product['price']) {
                        if (!$product['special']) {
                            echo $product['price'];
                        } else {
                            echo $product['special'];
                        }
                    }
                    ?>
            </span>
            <span class="fundraiser__block-sub sub-3">Старая цена<br><span><?=$product['price']?></span></span>
        </span>
        <br>
        <span class="fundraiser__block2">
            <span class="fundraiser__block2-sub sub-1">До конца<br>акции<br>осталось:</span>
            <span class="fundraiser__block2-countdown" data-countdown="<?php echo($product['specialTime'])?>">

            </span>
        </span>
        <br>
        <button type="button" onclick="cart.add('<?=$product['product_id']?>');" class="fundraiser__btn"><i
                class="fundraiser__btn-ico"></i><?=$button_cart?></button>
        <?}?>

    </div><!-- /.container -->
    <a href="<?=$product['href']?>" title="<?=$heading_title?>" class="fundraiser__title-link">
        <img src="<?=$product['thumb']?>" alt="<?=$product['name']?>" title="<?=$heading_title?>"
            class="fundraiser__right-img">
    </a>
</section>
<!-- /.fundraiser -->