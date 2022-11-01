<?php echo $header; ?>
<div class="container">
  <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?php echo $column_left; ?>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    <div id="content" class="" style="margin-left:15px;margin-right: 5px;">
      <div class="row">        
           
        <div class="">
        <h1><?php echo $heading_title; ?></h1>
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
      <div style="padding:20px 0">
      <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,lj,viber,whatsapp,telegram"></div>
       </div>

      
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