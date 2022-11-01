
<div class="otziv_block">
<h2><a href="/otzyvy" title="Перейти на страницу Отзывы">Отзывы</a></h2>
  <?php foreach ($articles as $article) { ?>
  <div class="otziv_item">
  <h3><?php echo $article['title']; ?> </h3>
        <?php echo $article['description']; ?>
             
  </div>
  <div class="button-group">
		<button onclick="location.href = ('<?php echo $article['href']; ?>');" data-toggle="tooltip" title="<?php echo $text_more; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_more; ?></span></button>
  </div>
    
  <?php } ?>
</div>