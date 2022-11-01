<?php echo $header; ?>
<div class="container categories">
  <div class="row">
        <!-- <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left">  -->
                <?php //echo $column_left; ?>
            <!-- </div>
        </div> -->
        <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    <div id="content" class="otzyvy">
    <div class="row">
        <form class="form-block" id="form2" role="form">
            <p>Обратная связь</p>
                    <div class="col-sm-12">
                       <div class=" col-sm-6"> 
                            <input type="text" class="form-control" id="name2" placeholder="Как Вас зовут?"/>
                       </div>
                       <div class="col-xs-12">
                            <textarea name="textarea" id="textarea2" class=" form-control" rows="4" placeholder="Ваш отзыв"></textarea>
                       </div>
                       <div class=" col-sm-6">
                            <input id="bottom" class="btn btn-block" onclick="send2();" type="button" value="Отправить отзыв"/>
                        </div>
                </div>
            </form>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      
      <?php if ($thumb || $description) { ?>
      <div class="row">
        <?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>
        <?php if ($description) { ?>
        <div class="col-sm-10"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      <hr>
      <?php } ?>
      <?php if ($categories) { ?>
      <h3 class="tut"><?php echo $text_refine; ?></h3>
      <?php if (count($categories) <= 5) { ?>
      <div class="row">
        <div class="col-sm-3">
          <ul>
            <?php foreach ($categories as $category) { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <?php } else { ?>
      <div class="row">
        <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
        <div class="col-sm-3">
          <ul>
            <?php foreach ($categories as $category) { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <?php } ?>
      <?php if ($articles) { ?>
      <div class="row">
        <?php foreach ($articles as $article) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
            <div class="image col-md-4 col-sm-4 col-xs-12"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>" title="<?php echo $article['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption  col-md-8 col-sm-8 col-xs-12">
                <?php echo strip_tags($article['preview'], '<h1><h2><p><font>'); ?> <a class="chitatdalee" href="<?php echo $article['href']; ?>">Читать далее.</a>

                <? if ($article['attributes']) { ?>
	                <? foreach ($article['attributes'] as $attribute_group) { ?>
	                	<? foreach ($attribute_group['attribute'] as $attribute_item) { ?>
                       	<div class="vkblock"><a href="https://<?=$attribute_item['text'];?>"><?=$attribute_item['text'];?></a></div>
	                	<? } ?>
	                <? } ?>
                <? } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$articles) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <h3>Оставить отзыв</h3>
  
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>