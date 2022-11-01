<?php echo $header; ?>
<div class="container">
  <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?//php echo $column_left; ?>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    <div id="content" class="">
      <div class="row">        
        <div class="col-sm-6">
          <?php if ($thumb || $images) { ?>
          <div class="thumbnails">
            <?php if ($thumb) { ?>
            <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <span class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></span>
            <?php } ?>
            <?php } ?>
          </div>
          <?php } ?>

          
        </div>
        
        <div class="col-sm-6">
          	<?php echo $description; ?>

          	<? if ($attributes) { ?>
	      		<!--h5><?=$text_attributes;?></h5-->
	            <? foreach ($attributes as $attribute_group) { ?>
	              	<? foreach ($attribute_group['attribute'] as $attribute_item) { ?>
                   		<b><?=$attribute_item['name'];?>:</b> <?=$attribute_item['text'];?><br />
	                <? } ?>
	          	<? } ?>
            <? } ?>
        </div>
      </div>

  	  <?php if ($articles) { ?>
  	  <h3><?php echo $text_related; ?></h3>
      <div class="row">
        <?php foreach ($articles as $article) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>" title="<?php echo $article['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
                <h4><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h4>
                <p><?php echo $article['preview']; ?></p>

                <? if ($article['attributes']) { ?>
	                <!--h5><?=$text_attributes;?></h5-->
	                <? foreach ($article['attributes'] as $attribute_group) { ?>
	                	<? foreach ($attribute_group['attribute'] as $attribute_item) { ?>
                       	<b><?=$attribute_item['name'];?>:</b> <?=$attribute_item['text'];?><br />
	                	<? } ?>
	                <? } ?>
                <? } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>

      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script>
<?php echo $footer; ?>