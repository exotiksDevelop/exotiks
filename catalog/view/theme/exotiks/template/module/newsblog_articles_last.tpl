<!-- newsblog articles last -->
<section id="newsblog_articles_last" class="newsblog-articles articles-last">
<div class="container">
<? if ($heading_title) { ?>
<h3 class="heading-title"><?php echo $heading_title; ?></h3>
<? } ?>
<div class="row">
  <?php foreach ($articles as $article) { ?>
  <div class="product-layout blog-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="blog-item-container">
		<div class="product-thumb transition">
		  <? if ($article['thumb']) { ?>
		 <!--<div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['title']; ?>" title="<?php echo $article['title']; ?>" class="img-responsive" /></a></div>-->
		  <? } ?>
		  <div class="caption blog-art">
			<h4><a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a></h4>
			<p><?php echo $article['preview']; ?></p>
			<?php //echo $article['description']; ?>
		  </div>
		  <!--<div class="button-group">
			<button onclick="location.href = ('<?php echo $article['href']; ?>');" data-toggle="tooltip" title="<?php echo $text_more; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_more; ?></span></button>
			<button type="button" data-toggle="tooltip" title="<?php echo $article['posted']; ?>"><i class="fa fa-clock-o"></i></button>
			<button type="button" data-toggle="tooltip" title="<?php echo $article['viewed']; ?>"><i class="fa fa-eye"></i></button>
		  </div>-->
		</div>
	</div>
  </div>
  <?php } ?>
</div>
</div>
</section>
<div><p>&nbsp;</p></div>
<script>
	var _articles_last_view = false;
    function articles_last_view() {
	  if (isInViewport($('#newsblog_articles_last')[0]) && !_articles_last_view) {
		$('#newsblog_articles_last').find('.blog-item').find('.blog-art').find('img').each(function() {
			$(this).attr('src', $(this).attr('src').replace('--lazy-img', ''));
		});
		_articles_last_view = true;
	  }
    }
    $(window).on('scroll', articles_last_view);
    $(document.body).on('touchmove', articles_last_view);
</script>
<!--// newsblog articles last -->
