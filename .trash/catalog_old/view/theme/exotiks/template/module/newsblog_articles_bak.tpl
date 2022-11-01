<? if ($heading_title) { ?>
<h3><?php echo $heading_title; ?></h3>
<? } ?>
<div class="row">
  <?php foreach ($articles as $article) { ?>
  <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <? if ($article['thumb']) { ?>
     <div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['title']; ?>" title="<?php echo $article['title']; ?>" class="img-responsive" /></a></div>
      <? } ?>
      <div class="caption">
        <h4><a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a></h4>
        <?php echo $article['description']; ?>
      </div>
      <div class="button-group">
		<button onclick="location.href = ('<?php echo $article['href']; ?>');" data-toggle="tooltip" title="<?php echo $text_more; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_more; ?></span></button>
		<button type="button" data-toggle="tooltip" title="<?php echo $article['posted']; ?>"><i class="fa fa-clock-o"></i></button>
		<button type="button" data-toggle="tooltip" title="<?php echo $article['viewed']; ?>"><i class="fa fa-eye"></i></button>
	  </div>
    </div>
  </div>
  <?php } ?>
</div>