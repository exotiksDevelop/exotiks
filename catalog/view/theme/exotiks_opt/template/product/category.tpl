<?= $header ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['text'] ?></a></li>
    <?php } ?>
  </ul>
  <div id="content">

    <h1 class="introduction__title>">
      <?= $heading_title; ?>
    </h1>

    <?php if ($categories) { ?>
      <h3 class="subcategory__title"><?= $text_refine ?></h3>
      <?php if (count($categories) <= 5) { ?>
        <div class="subcategory__items">
          <ul>
            <?php foreach ($categories as $category) { ?>
              <li><a href="<?= $category['href'] ?>"><?= $category['name'] ?></a></li>
            <?php } ?>
          </ul>
        </div>
      <?php } else { ?>
        <div class="subcategory__items">
          <ul class="subcategory__items_list ">
            <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
              <?php foreach ($categories as $category) { ?>
                <li><a href="<?= $category['href'] ?>"><?= $category['name'] ?></a></li>
              <?php } ?>
            <?php } ?>
          </ul>
		  <div class="subcategory-items-show-hide-panel" style="text-align:right;display:none;">
			<a class="c-show_all" href="javascript:void(0);">Показать все &darr;</a>
			<a class="c-hide" href="javascript:void(0);" style="display:none;">Скрыть &uarr;</a>
        </div>
		  <script>
		  $(window).on('load', function() {
			  if ($('.subcategory__items_list').height() > 55) {
				  $('.subcategory__items_list').addClass('c-hidden');
				  $('.subcategory-items-show-hide-panel').show();
			  }
		  });
		  $('.subcategory-items-show-hide-panel a.c-show_all').on('click', function() {
			  $('.subcategory__items_list').removeClass('c-hidden');
			  $(this).hide();
			  $('.subcategory-items-show-hide-panel a.c-hide').show();
		  });
		  $('.subcategory-items-show-hide-panel a.c-hide').on('click', function() {
			  $('.subcategory__items_list').addClass('c-hidden');
			  $(this).hide();
			  $('.subcategory-items-show-hide-panel a.c-show_all').show();
		  });
		  </script>
        </div>
      <?php } ?>
    <?php } ?>
    <?php if ($products) { ?>
      <div class="row subcategory__sort">
        <!-- <div class="col-md-4">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="button red glyphicon glyphicon-th-list" data-toggle="tooltip" title="<?//= $button_list ?>"></button>
            <button type="button" id="grid-view" class="button red glyphicon glyphicon-th" data-toggle="tooltip" title="<?//= $button_grid ?>"></button>
          </div>
        </div> -->
        <div class="subcategory__sort-interface">
          <label class="control-label" for="input-sort"><?= $text_sort ?></label>
        </div>
        <div class="subcategory__sort-interface">
          <select id="input-sort" class="form-control" onchange="location = this.value;">
            <?php foreach ($sorts as $sorts) { ?>
              <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                <option value="<?= $sorts['href'] ?>" selected="selected"><?= $sorts['text'] ?></option>
              <?php } else { ?>
                <option value="<?= $sorts['href'] ?>"><?= $sorts['text'] ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="subcategory__sort-interface">
          <label class="control-label" for="input-limit"><?= $text_limit ?></label>
        </div>
        <div class="subcategory__sort-interface">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
              <?php if ($limits['value'] == $limit) { ?>
                <option value="<?= $limits['href'] ?>" selected="selected"><?= $limits['text'] ?></option>
              <?php } else { ?>
                <option value="<?= $limits['href'] ?>"><?= $limits['text'] ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

      <section class="products">
        <div class="container">
          <div class="products__box">
            <? foreach ($products as $product) {
                if ($product['thumb'] === NULL) {
                  $product['thumb'] = 'data:image/jpg;base64,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';
                }

                ?>
              <figure class="products__box-item">
                <a href="<?= $product['href']; ?>" class="products__box-item-img-link">
                  <img src="<?= $product['thumb']; ?>" alt="<?= $product['name']; ?>" title="<?= $product['name']; ?>" class="products__box-item-img">
                  <?=($product['available'])?'':'<div class="products__box-item-not-available"><span>нет в наличии</span></div>'?>
                </a>
                <figcaption>
                  <h4 class="products__box-item-title"><a href="<?= $product['href']; ?>" class="products__box-item-title-link"><?= $product['name']; ?></a></h4>
                  <p class="products__box-item-text"><?= $product['description']; ?></p>
                </figcaption>             
                <span class="products__box-item-bottom<?=($product['available'])?'':' disable>'?>">
                  <span class="products__box-item-price" <?=($product['available'])?'':'style="filter:grayscale(1) contrast(0)"'?>>
                  <?
                    if ($product['price']) {

                      if ($product['special']) {
                        echo $product['special'];
                        echo '<span class="products__box-item-price-old">'.$product['price'].'</span>';
                      } else {
                        echo $product['price'];
                      }

                    }
                  ?>
                  </span>
                  <button
                    <?=($product['available'])?'':'disabled style="background-color:rgb(178, 178, 178);color:rgba(0,0,0,.3);"'?>
                    onclick="cart.add('<?= $product['product_id']; ?>');" type="button" class="products__box-item-btn">
                    <i class="products__box-item-btn-ico" <?=($product['available'])?'':'style="filter:grayscale(1) contrast(0)"'?>></i>
                    <?= $button_cart; ?>                    
                  </button>
                </span>
              </figure><!-- /.products__box-item -->
            <? } ?>
          </div><!-- /.products__box -->
        </div><!-- /.container -->
      </section>
      </div><!-- /.products -->

        <div class="row">
          <div class="col-sm-6 text-left"><?= $pagination ?></div>
          <div class="col-sm-6 text-right"><?= $results ?></div>
        </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
        <p><?= $text_empty ?></p>
        <div class="buttons">
          <div class="pull-right"><a href="<?= $continue ?>" class="button"><?= $button_continue ?></a></div>
        </div>
      <?php } ?>

		<?php if ($tags): ?>
		<div id="product_tags" class="sidebar-section">
			<!--<div class="sidebar-section-header">
				<h3 class="title">Популярные подборки</h3>
			</div>-->
			<div class="sidebar-section-content">
				<div class="tags_container">
					<ul class="tags_items_list ">
					<?php  
					foreach ($tags as $t):
					echo '<li><a href="' . $t['href'] . '" data-tag_id="' . $t['id'] . '">' . $t['tag'] . '</a></li>';
					endforeach;
					?>
					</ul>
				</div>
			</div>
		</div>
		<?php endif; // #tags ?>

      <?php if ($thumb || $description) { ?>
      <div class="introduction">
        <?php if ($thumb) { ?>
          <div class="introduction__img-wrap">
            <img src="<?= $thumb ?>" alt="<?= $heading_title ?>" title="<?= $heading_title ?>" class="img-thumbnail introduction__img" />
          </div>
        <?php } ?>
        <?php if ($description) { ?>
          <div class="introduction__text"><?= $description ?></div>
        <?php } ?>
      </div>
      <hr class="introduction__clear">

    </div><!-- /.container -->

    <?php } ?>

  </div><!-- /#content -->
  <!-- <add position="before"> -->

  <?= $footer ?>