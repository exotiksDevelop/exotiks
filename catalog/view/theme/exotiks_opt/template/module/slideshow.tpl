<section id="slider_top_section" class="slider- ">
  <div class="container">

    <div class="slider__slick" id="slideshow<?php echo $module; ?>" style="max-height:368px;">
      <? foreach ($banners as $banner) { ?>
        <div class="slider__box">
          <span class="slider__box-left">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAATSURBVHjaYvj//z8DAAAA//8DAAj8Av7TpXVhAAAAAElFTkSuQmCC" data-src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" title="<?= $banner['title']; ?>" class="slider__box-left-img lazy" />
          </span>
          <div class="slider__box-right">
            <h2 class="slider__box-right-title"><?= $banner['title']; ?></h2>
            <h3 class="slider__box-right-subtitle"><?= $banner['link']; ?></h3>
          </div>
        </div><!-- /.slider__box -->
      <? } ?>
    </div><!-- /.slider__slick -->
  </div><!-- /.container -->

</section>
<!-- /.slider -->