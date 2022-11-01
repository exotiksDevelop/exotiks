<?=$header?>
<div class="container search" id="product_page">

  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
    <?php } ?>
  </ul>
  <div id="content" class="search__content <?=$class?>">
    <?=$content_top?>
    <h1 class="heading-title"><?=$heading_title?></h1>
    <div class="row search__interface">
      <input type="text" name="search" value="<?=$search?>" placeholder="<?=$text_keyword?>" id="input-search"
        class="form-control filter__input" />
      <select name="category_id" class="form-control filter__input">
        <option value="0"><?=$text_category?></option>
        <?php foreach ($categories as $category_1) { ?>
        <?php if ($category_1['category_id'] == $category_id) { ?>
        <option value="<?=$category_1['category_id']?>" selected="selected">
          <?=$category_1['name']?></option>
        <?php } else { ?>
        <option value="<?=$category_1['category_id']?>"><?=$category_1['name']?></option>
        <?php } ?>
        <?php foreach ($category_1['children'] as $category_2) { ?>
        <?php if ($category_2['category_id'] == $category_id) { ?>
        <option value="<?=$category_2['category_id']?>" selected="selected">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$category_2['name']?></option>
        <?php } else { ?>
        <option value="<?=$category_2['category_id']?>">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$category_2['name']?></option>
        <?php } ?>
        <?php foreach ($category_2['children'] as $category_3) { ?>
        <?php if ($category_3['category_id'] == $category_id) { ?>
        <option value="<?=$category_3['category_id']?>" selected="selected">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$category_3['name']?>
        </option>
        <?php } else { ?>
        <option value="<?=$category_3['category_id']?>">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$category_3['name']?>
        </option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select>
      <label class="checkbox-inline filter__checkbox">
        <?php if ($sub_category) { ?>
        <input type="checkbox" name="sub_category" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="sub_category" value="1" />
        <?php } ?>
        <?=$text_sub_category?></label>
      <label class="checkbox-inline filter__checkbox">
        <?php if ($description) { ?>
        <input type="checkbox" name="description" value="1" id="description" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="description" value="1" id="description" />
        <?php } ?>
        <?=$entry_description?>
      </label>
    </div>
    <input type="button" value="<?=$button_search?>" id="button-search" class="button search__button" />
    <h2 class="heading-title heading-title_2"><?=$text_search?></h2>
    <?php if ($products) { ?>
    <div class="row search__interface">
      <label class="control-label filter__label" for="input-sort"><?=$text_sort?></label>
      <select id="input-sort" class="form-control filter__input " onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?=$sorts['href']?>" selected="selected"><?=$sorts['text']?></option>
        <?php } else { ?>
        <option value="<?=$sorts['href']?>"><?=$sorts['text']?></option>
        <?php } ?>
        <?php } ?>
      </select>
      <label class="control-label filter__label" for="input-limit"><?=$text_limit?></label>
      <select id="input-limit" class="form-control filter__input" onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?=$limits['href']?>" selected="selected"><?=$limits['text']?></option>
        <?php } else { ?>
        <option value="<?=$limits['href']?>"><?=$limits['text']?></option>
        <?php } ?>
        <?php } ?>
      </select>
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
            <a href="<?=$product['href']?>" class="products__box-item-img-link">
              <img src="<?=$product['thumb']?>" alt="<?=$product['name']?>" title="<?=$product['name']?>" class="products__box-item-img">
              <?=($product['available'])?'':'<div class="products__box-item-not-available"><span>нет в наличии</span></div>'?>
            </a>
            <figcaption>
              <h4 class="products__box-item-title">
                <a href="<?=$product['href']?>" class="products__box-item-title-link"><?=$product['name']?></a>
              </h4>
              <p class="products__box-item-text">
                <?=$product['description']?>

                <?php if ($product['rating']) { ?>
                <div class="product-detail__right-rating-icos card">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <i class="ico-0"></i>
                  <?php } else { ?>
                  <i class="ico-1"></i>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>

              </p>
            </figcaption>
            <span class="products__box-item-bottom">
              <span class="products__box-item-price">
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
              <button onclick="cart.add('<?=$product['product_id']?>', '<?=$product['minimum']?>');" type="button"
                class="products__box-item-btn">
                <i class="products__box-item-btn-ico"></i>
                <?=$button_cart?>
              </button>
            </span>
          </figure><!-- /.products__box-item -->
          <? } ?>
        </div><!-- /.products__box -->


      </div><!-- /.container -->
    </section>
    <!-- /.products -->




    <div class="container">
      <div class=" text-left"><?=$pagination?></div>
      <div class=" text-right"><?=$results?></div>
    </div>
    <?php } else { ?>
    <p><?=$text_empty?></p>
    <?php } ?>
    <?=$content_bottom?>
  </div>
</div>

<script>
  $('#button-search').bind('click', function () {
    url = 'index.php?route=product/search';

    var search = $('#content input[name=\'search\']').prop('value');

    if (search) {
      url += '&search=' + encodeURIComponent(search);
    }

    var category_id = $('#content select[name=\'category_id\']').prop('value');

    if (category_id > 0) {
      url += '&category_id=' + encodeURIComponent(category_id);
    }

    var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');

    if (sub_category) {
      url += '&sub_category=true';
    }

    var filter_description = $('#content input[name=\'description\']:checked').prop('value');

    if (filter_description) {
      url += '&description=true';
    }

    location = url;
  });

  $('#content input[name=\'search\']').bind('keydown', function (e) {
    if (e.keyCode == 13) {
      $('#button-search').trigger('click');
    }
  });

  $('select[name=\'category_id\']').on('change', function () {
    if (this.value == '0') {
      $('input[name=\'sub_category\']').prop('disabled', true);
    } else {
      $('input[name=\'sub_category\']').prop('disabled', false);
    }
  });

  $('select[name=\'category_id\']').trigger('change');
</script>
<?=$footer?>