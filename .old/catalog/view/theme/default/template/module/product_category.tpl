<?php foreach ($products as $k => $product) { ?>

<h3><?php echo $product[0]['title']; ?></h3>
<div class="row product-layout">
  <?php foreach ($product as $produk) { ?>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $produk['href']; ?>"><img src="<?php echo $produk['thumb']; ?>" alt="<?php echo $produk['name']; ?>" title="<?php echo $produk['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $produk['href']; ?>"><?php echo $produk['name']; ?></a></h4>
        <p><?php echo $produk['description']; ?></p>
        <?php if ($produk['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($produk['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($produk['price']) { ?>
        <p class="price">
          <?php if (!$produk['special']) { ?>
          <?php echo $produk['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $produk['special']; ?></span> <span class="price-old"><?php echo $produk['price']; ?></span>
          <?php } ?>
          <?php if ($produk['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $produk['tax']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $produk['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $produk['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $produk['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<?php } ?>