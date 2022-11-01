<?php echo $header; ?>
<div class="container categories">
  <div class="row">
        <!-- <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left">  -->
                <?php// echo $column_left; ?>
            <!-- </div>
        </div> -->
        <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
    <div id="content" class="<?php echo $class; ?>">
      <h2><?php echo $heading_title; ?></h2>
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
      <h3><?php echo $text_refine; ?></h3>
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

      <? if ($dates) { ?>
      <? $month = array("01"=>"Январь","02"=>"Февраль","03"=>"Март","04"=>"Апрель","05"=>"Май", "06"=>"Июнь", "07"=>"Июль","08"=>"Август","09"=>"Сентябрь","10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь"); ?>
      <div class="row">
      	<div class="col-xs-2 text-right"><p>Даты:</p></div>
      	<div class="col-xs-4">
      		<select class="form-control" onchange="location = this.value;">
      		<? foreach ($dates as $i=>$date) { ?>
	      		<option value="<?=$date['url'];?>" <? if ($date['selected']) { ?>selected<? } ?>><?=$month[$date['m']];?> <?=$date['y'];?> (<?=$date['count'];?>)</option>
			<? } ?>
      		</select>
      	</div>
      </div>
      <? } ?>


	<? if ($tags) { ?>
      <div class="row">
      	<div class="col-xs-2 text-right"><p>Темы:</p></div>
      	<div class="col-xs-10">
      		<? foreach ($tags as $i=>$tag) { ?><? if ($i>0) { ?>, <? } ?><a href="<?=$tag['url'];?>"><?=$tag['name'];?> (<?=$tag['count'];?>)</a><? } ?>
      	</div>
      </div>
      <? } ?>

      <div class="row">
        <?php foreach ($articles as $article) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
            <div class="caption col-md-12 col-sm-8 col-xs-12 t">
                <h4><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a> <?php echo $article['date']; ?></h4>
            </div>
           
            <div class="caption col-md-12 col-sm-8 col-xs-12 blog-art">
                <p><?php echo $article['preview']; ?></p>

                <? if ($article['attributes']) { ?>
	                <h5><?=$text_attributes;?></h5>
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
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>