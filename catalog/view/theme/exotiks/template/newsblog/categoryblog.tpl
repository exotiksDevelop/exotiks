<?= $header ?>
<div class="container blog">

  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['text'] ?></a></li>
    <?php } ?>
  </ul>

  <h2 class="heading-title"><?= $heading_title ?></h2>
  <?php if ($thumb || $description) { ?>

    <?php if ($thumb) { ?>
      <img src="<?= $thumb ?>" alt="<?= $heading_title ?>" title="<?= $heading_title ?>" class="img-thumbnail" />
    <?php } ?>
    <?php if ($description) { ?>
      <?= $description ?>
    <?php } ?>

    <hr>
  <?php } ?>
  <?php if ($categories) { ?>
    <h3><?= $text_refine ?></h3>
    <?php if (count($categories) <= 5) { ?>
      <ul>
        <?php foreach ($categories as $category) { ?>
          <li><a href="<?= $category['href'] ?>"><?= $category['name'] ?></a></li>
        <?php } ?>
      </ul>
    <?php } else { ?>
      <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
        <div class="col-sm-3">
          <ul>
            <?php foreach ($categories as $category) { ?>
              <li><a href="<?= $category['href'] ?>"><?= $category['name'] ?></a></li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($articles) { ?>

  <? if ($dates) { ?>
    <? $month = array("01" => "Январь", "02" => "Февраль", "03" => "Март", "04" => "Апрель", "05" => "Май", "06" => "Июнь", "07" => "Июль", "08" => "Август", "09" => "Сентябрь", "10" => "Октябрь", "11" => "Ноябрь", "12" => "Декабрь") ?>
    <!-- <p>Даты:</p> -->
    <select class="blog__select form-control" onchange="location = this.value;">
      <? foreach ($dates as $i => $date) { ?>
        <option value="<?= $date['url'] ?>" <? if ($date['selected']) { ?>selected<? } ?>><?= $month[$date['m']] ?> <?= $date['y'] ?> (<?= $date['count'] ?>)</option>
      <? } ?>
    </select>
  <? } ?>


  <? if ($tags) { ?>
    <!-- <p>Темы:</p> -->
    <div class="blog__tags">

      <? foreach ($tags as $i => $tag) { ?>
        <? if ($i > 0) { ?>
        <? } ?>
        <a href="<?= $tag['url'] ?>">
          <?= $tag['name'] ?>
          (<?= $tag['count'] ?>)</a>
      <? } ?>
    </div><!-- /.blog__tags -->
  <? } ?>



  <div class="blog__box">
  <?php foreach ($articles as $article) { ?>
    <div class="product-layout product-list blog__box-item">
      <div class="product-thumb">
        <div class="caption t">
          <h4 class="blog__box-item-title"><a class="blog__box-item-title-link"href="<?= $article['href'] ?>"><?= $article['name'] ?></a> <?= $article['date'] ?></h4>
        </div>
        <div class="caption blog-art">
          <p><?= $article['preview'] ?></p>
          <? if ( $article['attributes'] ) { ?>
            <h5><?= $text_attributes ?></h5>
            <? foreach ($article['attributes'] as $attribute_group) { ?>
              <? foreach ($attribute_group['attribute'] as $attribute_item) { ?>
                <b><?= $attribute_item['name'] ?>:</b> <?= $attribute_item['text'] ?><br />
              <? } ?>
            <? } ?>
          <? } ?>
        </div>
      </div>
    </div>
  <?php } ?>
  </div><!-- /.blog__box -->



  <div class="text-left"><?= $pagination ?></div>
  <div class="text-right"><?= $results ?></div>

<?php } ?>
<?php if (!$categories && !$articles) { ?>
  <p><?= $text_empty ?></p>
  <div class="buttons">
    <div class="pull-right"><a href="<?= $continue ?>" class="button"><?= $button_continue ?></a></div>
  </div>
<?php } ?>
<? //php echo $content_bottom
?>
<?= $footer ?>