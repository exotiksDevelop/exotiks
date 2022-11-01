<section class="slider">
  <div class="container">

    <div class="slider__slick" id="slideshow<?php echo $module; ?>">
      <? foreach ($banners as $banner) { ?>
        <div class="slider__box">
          <span class="slider__box-left">
            <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" title="<?= $banner['title']; ?>" class="slider__box-left-img">
          </span>
          <span class="slider__box-right">
            <h2 class="slider__box-right-title"><?= $banner['title']; ?></h2>
            <h3 class="slider__box-right-subtitle"><?= $banner['link']; ?></h3>
          </span>
        </div><!-- /.slider__box -->
      <? } ?>
    </div><!-- /.slider__slick -->
  </div><!-- /.container -->

</section>
<!-- /.slider -->