<h3><?php echo $heading_title; ?></h3>
<div class="row" style="opacity: 1; display: block;margin-bottom: 0px;">
    <?php foreach ($categories as $category) { ?>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="product-thumb transition">
                <div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a></div>
                <div class="caption" style="min-height: 50px">
                    <h5><a style="text-decoration: none" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h5>
                </div> 
            </div>
        </div>
    <?php } ?>
</div>