<?= $header; ?>

<section class="product-detail">
  <div class="container">
    <a class="product-detail__back" onclick="javascript:history.back();">
    <i class="product-detail__back-ico"></i>Назад</a>
    <h1 class="product-detail__title"><?= $heading_title; ?></h1>

    <div class="product-detail__box">

      <div class="product-detail__slider">

        <? if ($images) { ?>
          <? foreach ($images as $image) { ?>
            <? //=$image['popup']; 
                ?>
            <img src="<?= $image['popup']; ?>" title="<?= $heading_title; ?>" alt="<?= $heading_title; ?>" class="product-detail__slider-img">
          <? } ?>
        <? } ?>

      </div><!-- /.product-detail__slider -->

      <div class="product-detail__right" id="product">
        <h2 class="product-detail__right-price"><span>Цена</span><span> <?= $price; ?></span></h2>
        <?
          if ($stock === 'Есть') { //смена значка
            $stock = '<span>Есть</span><i class="product-detail__right-availability-ico"></i>';
          } else {
            $stock = '<span>' . $stock . '</span><i class="product-detail__right-availability-ico x"></i>';;
          }
        ?>
        <div class="product-detail__right-availability"><span>Наличие:</span><?= $stock; ?></div>

        <div class="product-detail__right-count">
          <span class="product-detail__right-count-title">Количество:</span>
          <!-- TODO если type number то не работает добавление в корзину правильного кол-ва -->
          <input type="text" name="quantity" value="<?= $minimum; ?>" size="2" id="input-quantity" class="product-detail__right-count-num" />
          <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
          <div class="product-detail__right-count-num-btn">
            <button id="countNumPlus" class="product-detail__right-count-num-btn-plus"></button>
            <button id="countNumMinus" class="product-detail__right-count-num-btn-minus"></button>
          </div>
        </div><!-- /.product-detail__right-count -->

        <div class="product-detail__right-rating">
          <div class="product-detail__right-rating-icos">
            <? for ($i = 1; $i <= 5; $i++) { ?>
              <? if ($rating < $i) { ?>
                <i class="ico-0"></i>
              <? } else { ?>
                <i class="ico-1"></i>
              <? } ?>
            <? } ?>
          </div>
          <div class="product-detail__right-rating-link-wrap">
            <a class="product-detail__right-rating-link count-feedback" href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
              <?= $reviews . '&nbsp;'; ?>
            </a>
            <a class="product-detail__right-rating-link write-new-feedback" href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
              <?= '&nbsp;' . $text_write; ?>
            </a>
          </div>
        </div><!-- /.product-detail__right-rating -->

        <button type="button" id="button-cart" data-loading-text="<?= $text_loading; ?>" class="product-detail__right-btn">
          Купить<i class="product-detail__right-btn-addtocart"></i>
        </button><!-- /#button-cart -->

        <!-- AddThis Button BEGIN -->
        <div class="addthis_inline_share_toolbox product-detail__right-share"></div>
        <script type="text/javascript">
          var addthis_config = {
            "data_track_clickback": true
          };
        </script><!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-570c75b682d7176b"></script>
        <!-- AddThis Button END -->

      </div>
    </div><!-- /.product-detail__box -->

    <div class="product-detail__bottombox">
      <ul class="product-detail__bottombox-nav">
        <li class="active product-detail__bottombox-nav-item"><a href="#tab-description" data-toggle="tab"><?= $tab_description; ?></a></li>
        <? if ($attribute_groups) { ?>
          <li class="product-detail__bottombox-nav-item"><a href="#tab-specification" data-toggle="tab"><?= $tab_attribute; ?></a></li>
        <? } ?>
        <? if ($review_status) { ?>
          <li class="product-detail__bottombox-nav-item"><a href="#tab-review" data-toggle="tab"><?= $tab_review; ?></a>
          </li>
        <? } ?>
      </ul>
      <div class="tab-content product-detail__bottombox-nav-item-content">
        <div class="tab-pane active" id="tab-description"><?= $description; ?></div>
        <? if ($attribute_groups) { ?>
          <div class="tab-pane" id="tab-specification">
            <table class="table table-bordered">
              <? foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?= $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <? foreach ($attribute_group['attribute'] as $attribute) { ?>
                    <tr>
                      <td><?= $attribute['name']; ?></td>
                      <td><?= $attribute['text']; ?></td>
                    </tr>
                  <? } ?>
                </tbody>
              <? } ?>
            </table>
          </div>
        <? } ?>
        <? if ($review_status) { ?>
          <div class="tab-pane" id="tab-review">
            <form class="form-horizontal" id="form-review">
              <div id="review"></div>
              <h2><?= $text_write; ?></h2>
              <? if ($review_guest) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?= $entry_name; ?></label>
                    <input type="text" name="name" value="" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?= $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block"><?= $text_note; ?></div>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label"><?= $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?= $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
                    &nbsp;
                    <input type="radio" name="rating" value="2" />
                    &nbsp;
                    <input type="radio" name="rating" value="3" />
                    &nbsp;
                    <input type="radio" name="rating" value="4" />
                    &nbsp;
                    <input type="radio" name="rating" value="5" />
                    &nbsp;<?= $entry_good; ?></div>
                </div>
                <?= $captcha; ?>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?= $text_loading; ?>" class="button green mt-1"><?= $button_continue; ?></button>
                  </div>
                </div>
              <? } else { ?>
                <?= $text_login; ?>
              <? } ?>
            </form>
          </div>
        <? } ?>
      </div><!-- /.product-detail__bottombox-nav-item-content -->
    </div><!-- /.container -->
</section>
<!-- /.product-detail -->

<section class="products products_related">
  <div class="container">

    <h2 class="products__title products_related"><?= $text_related; ?></h2>
    <div class="products__box">

      <? $i = 0; ?>
      <?
      foreach ($products as $product) {
        if ($product['thumb'] === NULL) {
          $product['thumb'] = 'data:image/jpg;base64,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';
        }
        ?>

        <figure class="products__box-item <?= $class; ?>">
          <a href="<?= $product['href']; ?>" class="products__box-item-img-link">
            <img src="<?= $product['thumb']; ?>" alt="<?= $product['name']; ?>" title="<?= $product['name']; ?>" class="products__box-item-img">
          </a>
          <figcaption>
            <h4 class="products__box-item-title">
              <a href="<?= $product['href']; ?>" class="products__box-item-title-link">
                <?= $product['name']; ?>
              </a>
            </h4>
            <p class="products__box-item-text"><?= $product['description']; ?></p>
          </figcaption>
          <span class="products__box-item-bottom">
            <span class="products__box-item-price">
              <?
                if ($product['price']) {
                  if (!$product['special']) {
                    echo $product['price'];
                  } else {
                    echo $product['special'];
                  }
                }
                ?>
            </span>
            <button type="button" onclick="cart.add('<?= $product['product_id']; ?>', '<?= $product['minimum']; ?>');" class="products__box-item-btn">
              <i class="products__box-item-btn-ico"></i>
              Купить<? //=$button_cart
                      ?>
            </button>
          </span>
        </figure><!-- /.products__box-item -->
      <? } ?>

    </div><!-- /.products__box -->
  </div><!-- /.container -->
</section>
<!-- /.products -->

<?= $content_bottom ?>

<?= $footer; ?>