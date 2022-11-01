<?=$header;?>

<section class="product-detail">
  <div class="container">
  
    <ul class="breadcrumb" style="display:none;">
	  <li>&nbsp;</li>
	</ul>
	
    <a class="product-detail__back" onclick="javascript:history.back();">
      <i class="product-detail__back-ico"></i>Назад</a>
    <h1 class="product-detail__title"><?=$heading_title;?></h1>

    <div class="product-detail__box">

      <div class="product-detail__slider">
        <div class="<?=($stock == 'Отсутствует') ? 'no-goods' : ''?>">
          <?=($stock == 'Отсутствует') ? '<span class="no-goods-text">нет в наличии</span>' : ''?>
          <img
            src="<?=$thumb?>"
            title="<?=$heading_title?>"
            alt="<?=$heading_title?>"
            class="product-detail__slider-img"
            data-mfp-src="<?=$popup?>">
        </div>

        <? if ($images) { ?>
          <? foreach ($images as $image) { ?>
            <div class="<?=($stock == 'Отсутствует') ? 'no-goods' : ''?>">
              <?=($stock == 'Отсутствует') ? '<span class="no-goods-text">нет в наличии</span>' : ''?>
              <img
                src="<?=$image['thumb']?>"
                title="<?=$heading_title?>"
                alt="<?=$heading_title?>"
                class="product-detail__slider-img"
                data-mfp-src="<?=$image['popup']?>">
            </div>
          <? } ?>
        <? } ?>

      </div><!-- /.product-detail__slider -->

      <div class="product-detail__right" id="product">
        <h2 class="product-detail__right-price">
          <span class="product-detail__right-price-title">Цена</span>
          <?
            if ($price) {
              if (!$special) {
                echo '<span class="product-detail__right-price-number">'.$price.'</span>';
              } else {
                echo '<span class="product-detail__right-price-number">'.$special.'</span>';;
                echo '<span class="products__box-item-price-old product">'.$price.'</span>';
              }
            }?>
        </h2>
        <div class="product-detail__right-articul">
          <span>Артикул:</span>
          <span><?=$model?></span>
        </div>
        <div class="product-detail__right-availability"><span>Наличие:</span>
          <?if ($stock == 'Есть') { //смена значка?>
            <!--<span>Есть</span><i class="product-detail__right-availability-ico"></i>-->
          <?} else {?>
            <!--<span>Отсутствует</span><i class="product-detail__right-availability-ico x"></i>-->
          <?}?>
		  <span class="quantity-text"><?php echo $quantity; ?> шт.</span>
        </div>

        <div class="product-detail__right-count">
          <span class="product-detail__right-count-title">Количество:</span>
          <!-- TODO если type number то не работает добавление в корзину правильного кол-ва -->
          <input type="text" name="quantity" value="1<?//= $minimum; ?>" size="2" id="input-quantity"
            class="product-detail__right-count-num" />
          <input type="hidden" name="product_id" value="<?=$product_id;?>" />
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
            <a class="product-detail__right-rating-link count-feedback" href=""
              onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
              <?=$reviews . '&nbsp;';?>
            </a>
            <a class="product-detail__right-rating-link write-new-feedback" href=""
              onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
              <?='&nbsp;' . $text_write;?>
            </a>
          </div>
        </div><!-- /.product-detail__right-rating -->

        <button type="button" id="button-cart"
          <?=($stock == 'Отсутствует') ? 'disabled' : ''?>
          data-loading-text="<?=$text_loading;?>"
          class="product-detail__right-btn<?=($stock === 'Отсутствует') ? ' not-available' : ''?>">
          Купить
          <i class="product-detail__right-btn-addtocart"></i>
        </button><!-- /#button-cart -->

        <!-- AddThis Button BEGIN -->
        <div class="addthis_inline_share_toolbox product-detail__right-share"></div>
        <script>
          var addthis_config = {
            "data_track_clickback": true
          };
        </script><!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-570c75b682d7176b"></script>
        <!-- AddThis Button END -->
		
		<!-- payments icons -->
		<div class="payments-icons">
			<div class="row-padding">
				<div class="ceil">
					<img class="payments-icon img-responsive lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAATSURBVHjaYvj//z8DAAAA//8DAAj8Av7TpXVhAAAAAElFTkSuQmCC" data-src="/image/payments/logo-visa.png" alt="logo-visa" />
				</div>
				<div class="ceil">
					<img class="payments-icon img-responsive lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAATSURBVHjaYvj//z8DAAAA//8DAAj8Av7TpXVhAAAAAElFTkSuQmCC" data-src="/image/payments/logo-mastercard.png" alt="logo-mastercard" />
				</div>
				<div class="ceil">
					<img class="payments-icon img-responsive lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAATSURBVHjaYvj//z8DAAAA//8DAAj8Av7TpXVhAAAAAElFTkSuQmCC" data-src="/image/payments/logo-mir.png" alt="logo-mir" />
				</div>
				<div class="ceil">
					<img class="payments-icon img-responsive lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAATSURBVHjaYvj//z8DAAAA//8DAAj8Av7TpXVhAAAAAElFTkSuQmCC" data-src="/image/payments/logo-samsung-pay.png" alt="logo-samsung-pay" />
				</div>
				<div class="ceil">
					<img class="payments-icon img-responsive lazy" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAATSURBVHjaYvj//z8DAAAA//8DAAj8Av7TpXVhAAAAAElFTkSuQmCC" data-src="/image/payments/logo-apple-pay.png" alt="logo-apple-pay" />
				</div>
			</div>
		</div>
		<!--// payments icons -->
		
      </div>
    </div><!-- /.product-detail__box -->
	
	<div class="product-detail__middlebox" style="display:block;">
		<div class="estimate-shipping">
			<div class="header" onclick="$('.estimate-shipping .content').slideToggle();">
				<h3 class="header-title">Стоимость доставки &nbsp; &nbsp; &darr; &uarr; </h3>
			</div>
			<div class="content" style="display:none;">
			  <!-- Shipping calculate -->
              <?php if(isset($estimate_shipping) && $estimate_shipping['estimate_shipping_status']) { ?>
              <h3><?php echo $estimate_shipping['estimate_shipping_header_title']; ?></h3>
              <div class="form-horizontal">
                <?php if($estimate_shipping['estimate_shipping_geo']) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-country"><?php echo $entry_country; ?></label>
                    <select name="country_id" id="input-country" class="form-control">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php foreach ($countries as $country) { ?>
                      <?php if ($country['country_id'] == $country_id) { ?>
                      <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-zone"><?php echo $entry_zone; ?></label>
                    <select name="zone_id" id="input-zone" class="form-control">
                    </select>
                  </div>
                </div>
                <?php } ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
                    <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-city"><?php echo $entry_city; ?></label>
                    <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
                  </div>
                </div>
                <button type="button" id="button-quote" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_quote; ?></button>
              </div>
              <?php } ?>
              <!-- Shipping calculate -->
			</div>
		</div> 
	</div>

    <div class="product-detail__bottombox">
      <ul class="product-detail__bottombox-nav">
        <li class="active product-detail__bottombox-nav-item">
          <a href="#tab-description" data-toggle="tab">
            <?=$tab_description;?>
          </a>
        </li>
        <? if ($attribute_groups) { ?>
        <li class="product-detail__bottombox-nav-item">
          <a href="#tab-specification" data-toggle="tab">
            <?=$tab_attribute;?>
          </a>
        </li>
        <? } ?>
        <? if ($review_status) { ?>
        <li class="product-detail__bottombox-nav-item">
          <a href="#tab-review" data-toggle="tab">
            <?=$tab_review;?>
          </a>
        </li>
        <? } ?>
      </ul>
      <div class="tab-content product-detail__bottombox-nav-item-content">
        <div class="tab-pane active product-detail__bottombox-nav-item-content-tab" id="tab-description">
          <?=$description;?>
        </div>
        <?//var_dump($attribute_groups)?>
        <? if ($attribute_groups) { ?>
        <div class="tab-pane" id="tab-specification">
          <table class="table table-bordered">
            <? foreach ($attribute_groups as $attribute_group) { ?>
            <thead>
              <tr>
                <td colspan="2">
                  <strong>
                    <?=$attribute_group['name'];?>
                  </strong>
                </td>
              </tr>
            </thead>
            <tbody>
              <? foreach ($attribute_group['attribute'] as $attribute) { ?>
              <tr>
                <td><strong><?=$attribute['name'];?></strong></td>
              </tr>
              <tr>
                <td><?=$attribute['text'];?></td>
              </tr>
              <? } ?>
            </tbody>
            <? } ?>
          </table>
        </div>
        <?}?>
        <?if ($review_status) {?>
        <div class="tab-pane product-detail__bottombox-nav-item-content-tab" id="tab-review">
          <form class="form-horizontal" id="form-review">
            <div id="review"></div>
            <h2><?=$text_write;?></h2>
            <?if ($review_guest) { ?>
            <div class="form-group required">
              <div class="col-sm-12">
                <label class="control-label" for="input-name"><?=$entry_name;?></label>
                <input type="text" name="name" value="" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="form-group required">
              <div class="col-sm-12">
                <label class="control-label" for="input-review"><?=$entry_review;?></label>
                <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                <div class="help-block"><?=$text_note;?></div>
              </div>
            </div>
            <div class="form-group required">
              <div class="col-sm-12">
                <label class="control-label"><?=$entry_rating;?></label>
                &nbsp;&nbsp;&nbsp; <?=$entry_bad;?>&nbsp;
                <input type="radio" name="rating" value="1" />
                &nbsp;
                <input type="radio" name="rating" value="2" />
                &nbsp;
                <input type="radio" name="rating" value="3" />
                &nbsp;
                <input type="radio" name="rating" value="4" />
                &nbsp;
                <input type="radio" name="rating" value="5" />
                &nbsp;<?=$entry_good;?></div>
            </div>
            <?=$captcha;?>
            <div class="buttons clearfix">
              <div class="pull-right">
                <button type="button" id="button-review" data-loading-text="<?=$text_loading;?>"
                  class="button"><?=$button_continue;?></button>
              </div>
            </div>
            <?} else { ?>
            <?=$text_login;?>
            <?} ?>
          </form>
        </div>
        <?}?>
      </div><!-- /.product-detail__bottombox-nav-item-content -->
	  
	  <?php if ($tags) { ?>
		<p style="text-align:center;padding:4px;"><?php echo $text_tags; ?>
		<?php for ($i = 0; $i < count($tags); $i++) { ?>
		<?php if ($i < (count($tags) - 1)) { ?>
		<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
		<?php } else { ?>
		<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
		<?php } ?>
		<?php } ?>
		</p>
	  <?php } ?>
	  
    </div><!-- /.container -->
</section>
<!-- /.product-detail -->

<script>
  $('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {
    $.ajax({
      url: 'index.php?route=product/product/getRecurringDescription',
      type: 'post',
      data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
      dataType: 'json',
      beforeSend: function () {
        $('#recurring-description').html('');
      },
      success: function (json) {
        $('.alert, .text-danger').remove();

        if (json['success']) {
          $('#recurring-description').html(json['success']);
        }
      }
    });
  });
</script>
<script>
 $('#button-cart').on('click', function () {
      $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
        dataType: 'json',
        beforeSend: function () {
          $('#button-cart').button('loading');
        },
        complete: function () {
          $('#button-cart').button('reset');
        },
        success: function (json) {
          $('.alert, .text-danger').remove();
          $('.form-group').removeClass('has-error');

          if (json['error']) {
            if (json['error']['option']) {
              for (i in json['error']['option']) {
                var element = $('#input-option' + i.replace('_', '-'));
                if (element.parent().hasClass('input-group')) {
                  element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                } else {
                  element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                }
              }
            }
            if (json['error']['recurring']) {
              $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
            }
            // Highlight any found errors
            $('.text-danger').parent().addClass('has-error');
          }

          if (json['success']) {
            $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            $('#cartCount').text(json['count']);
            // $('html, body').animate({
            //   scrollTop: 0
            // }, 'slow');
            $('#cart > ul').load('index.php?route=common/cart/info ul li');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    });



  // $('#button-cart').on('click', function () {
  //   $.ajax({
  //     url: 'index.php?route=checkout/cart/add',
  //     type: 'post',
  //     data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
  //     dataType: 'json',
  //     beforeSend: function () {
  //       $('#button-cart').button('loading');
  //     },
  //     complete: function () {
  //       $('#button-cart').button('reset');
  //     },
  //     success: function (json) {
  //       $('.alert, .text-danger').remove();
  //       $('.form-group').removeClass('has-error');

  //       if (json['error']) {
  //         if (json['error']['option']) {
  //           for (i in json['error']['option']) {
  //             var element = $('#input-option' + i.replace('_', '-'));

  //             if (element.parent().hasClass('input-group')) {
  //               element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
  //             } else {
  //               element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
  //             }
  //           }
  //         }

  //         if (json['error']['recurring']) {
  //           $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
  //         }

  //         // Highlight any found errors
  //         $('.text-danger').parent().addClass('has-error');
  //       }

  //       if (json['success']) {
  //         $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

  //         $('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);

  //         $('html, body').animate({ scrollTop: 0 }, 'slow');

  //         $('#cart > ul').load('index.php?route=common/cart/info ul li');
  //       }
  //     },
  //     error: function (xhr, ajaxOptions, thrownError) {
  //       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  //     }
  //   });
  // });
</script>
<script>
  $('.date').datetimepicker({
    pickTime: false
  });

  $('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
  });

  $('.time').datetimepicker({
    pickDate: false
  });

  $('button[id^=\'button-upload\']').on('click', function () {
    var node = this;

    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

    $('#form-upload input[name=\'file\']').trigger('click');

    if (typeof timer != 'undefined') {
      clearInterval(timer);
    }

    timer = setInterval(function () {
      if ($('#form-upload input[name=\'file\']').val() != '') {
        clearInterval(timer);

        $.ajax({
          url: 'index.php?route=tool/upload',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#form-upload')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $(node).button('loading');
          },
          complete: function () {
            $(node).button('reset');
          },
          success: function (json) {
            $('.text-danger').remove();

            if (json['error']) {
              $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
            }

            if (json['success']) {
              alert(json['success']);

              $(node).parent().find('input').attr('value', json['code']);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    }, 500);
  });
</script>
<script>
  $('#review').delegate('.pagination a', 'click', function (e) {
    e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
  });

  $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

  $('#button-review').on('click', function () {
    $.ajax({
      url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
      type: 'post',
      dataType: 'json',
      data: $("#form-review").serialize(),
      beforeSend: function () {
        $('#button-review').button('loading');
      },
      complete: function () {
        $('#button-review').button('reset');
      },
      success: function (json) {
        $('.alert-success, .alert-danger').remove();

        if (json['error']) {
          $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
        }

        if (json['success']) {
          $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

          $('input[name=\'name\']').val('');
          $('textarea[name=\'text\']').val('');
          $('input[name=\'rating\']:checked').prop('checked', false);
        }
      }
    });
  });

  // $(document).ready(function () {
  //   $('.thumbnails').magnificPopup({
  //     type: 'image',
  //     delegate: 'a',
  //     gallery: {
  //       enabled: true
  //     }
  //   });
  // });
</script>

<script type="text/javascript">
$('#button-quote').on('click', function() {
    $.ajax({
          url: 'index.php?route=product/product/quoteProduct',
          type: 'post',
          data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
          dataType: 'json',
          beforeSend: function() {
              $('#button-quote').button('loading');
          },
          complete: function() {
              $('#button-quote').button('reset');
          },
          success: function(json) {
              $('.alert, .text-danger').remove();
              $.ajax({
    				url: 'index.php?route=product/product/quote',
    				type: 'post',
    				data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&city=' + $('input[name=\'city\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
    				dataType: 'json',
    				success: function(json) {
                        if (json['error']) {
                              if (json['error']['warning']) {
                                  $('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                  $('html, body').animate({ scrollTop: 0 }, 'slow');
                              }
                              if (json['error']['country']) {
                                  $('select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>');
                              }
                              if (json['error']['zone']) {
                                  $('select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['zone'] + '</div>');
                              }
                              if (json['error']['postcode']) {
                                  $('input[name=\'postcode\']').after('<div class="text-danger">' + json['error']['postcode'] + '</div>');
                              }
                        }
                          if (json['shipping_method']) {
                              $('#modal-shipping').remove();
                              html  = '<div id="modal-shipping" class="modal">';
                              html += '  <div class="modal-dialog">';
                              html += '    <div class="modal-content">';
                              html += '      <div class="modal-header">';
                              html += '        <h4 class="modal-title"><?php echo $estimate_shipping["estimate_shipping_popup_title"]; ?></h4>';
                              html += '      </div>';
                              html += '      <div class="modal-body" style="overflow:auto;">';
                              for (i in json['shipping_method']) {
                                  html += '<p><strong>' + json['shipping_method'][i]['title'] + '</strong></p>';
                                  if (!json['shipping_method'][i]['error']) {
                                      for (j in json['shipping_method'][i]['quote']) {
                                          html += '<div class="radio">';
                                          html += '  <label>';
                                          html += json['shipping_method'][i]['quote'][j]['title'] + ' - ' + json['shipping_method'][i]['quote'][j]['text'] + '</label></div>';
                                      }
                                  } else {
                                      html += '<div class="alert alert-danger">' + json['shipping_method'][i]['error'] + '</div>';
                                  }
                              }
                              html += '      </div>';
                              html += '      <div class="modal-footer">';
                              html += '        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>';
                              html += '      </div>';
                              html += '    </div>';
                              html += '  </div>';
                              html += '</div> ';
                              $('body').append(html);
                              $('#modal-shipping').modal('show');
                              $('input[name=\'shipping_method\']').on('change', function() {
                                  $('#button-shipping').prop('disabled', false);
                              });
                          }
    				}//success
    			});
    		},
    		error: function(xhr, ajaxOptions, thrownError) {
    			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    		}
    });
});
</script>
<script type="text/javascript">
$('select[name=\'country_id\']').on('change', function() {
    $.ajax({
		url: 'index.php?route=product/product/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        complete: function() {
            $('.fa-spin').remove();
        },
        success: function(json) {
            if (json['postcode_required'] == '1') {
                  $('input[name=\'postcode\']').parent().parent().addClass('required');
            } else {
                  $('input[name=\'postcode\']').parent().parent().removeClass('required');
            }
            html = '<option value=""><?php echo $text_select; ?></option>';
            if (json['zone'] && json['zone'] != '') {
                  for (i = 0; i < json['zone'].length; i++) {
                      html += '<option value="' + json['zone'][i]['zone_id'] + '"';
                      if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                          html += ' selected="selected"';
                      }
                      html += '>' + json['zone'][i]['name'] + '</option>';
                  }
            } else {
                html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
            }
              $('select[name=\'zone_id\']').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
$('select[name=\'country_id\']').trigger('change');
</script>

<?if ( !empty($products) ) {?>
<section class="products products_related">
  <div class="container">

    <h2 class="products__title products__title_related">
      <?=$text_related;?>
    </h2>
    <div class="products__box">

      <?
        $i = 0;
        foreach ($products as $product) {
          if ($i===4) break;
          if ($product['thumb'] === NULL) {
            $product['thumb'] = 'data:image/jpg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD//gATQ3JlYXRlZCB3aXRoIEdJTVD/2wBDAAcFBQYFBAcGBQYIBwcIChELCgkJChUPEAwRGBUaGRgVGBcbHichGx0lHRcYIi4iJSgpKywrGiAvMy8qMicqKyr/2wBDAQcICAoJChQLCxQqHBgcKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKir/wgARCAFvAbADAREAAhEBAxEB/8QAGwABAAMBAQEBAAAAAAAAAAAAAAQFBgMBAgf/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAH9IAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB8FcSCYAAAAAAAAAAAAAAAAAAAAAAAAD5MYSCGaEuAAAAAAAAAAAAAAAAAAAAAAADgUJYFKa8gGcNkAAAAAAAAAAAAAAAAAAAAAAeFCUBbF6Y81JVnhpwAAAAAAAAAAAAAAAAAAAAD5K46lcdCmNoVZSEo0R9gAAAAAAAAAAAAAAAAAAHh6Dwxp0I5dl4Y4uS4AAAAAAAAAAAAAAAAAAAABRkY0Z6QzMG0IhkzbkQ4liAAAAAAAAAAAAAAAAAAAACOYc6n0aQzJqCvIxrAAAAAAAAAAAAAAAAAAAAAADMEM6F2URflGdzRHUAAAAAAAAAAAAAAAAAAAAHh6DGGkMwTjia4AAAAAAAAAAAAAAAAAAAAAHMz5GNaCnM8fZENuSgAAAAAAAAAAAAAAAAAAAACGZE5GzJgBGORQFiXgAAAAAAAAAAAAAAAAAB4cCQCnKosSyKshGrAAOJ9HQAAAAAAAAAAAAAAAAAAgGXNsVxmzQGXNcTjEmrJoAAAAAAAAAAAAAAAAAAAPkzpoz0yRYHpUH0WhILU4HcAAAAAAAAAAAAAAAAAAAAGQLQmGZI5sDIFmVxpyzAAAAAAAAAAAAAAAAAAAAABFMWdzSFeczQFcTiUAAAAAAAAAAAAAAAAAAAAAADNA0p8GXNUegAAAAAAAAAAAAAAAAAAAAAAA5mINiSgAAAAAAAAAAAAAAAAAAAAAAAAAQiWfQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/8QAKBAAAgIABgIBAwUAAAAAAAAAAgMBBAAFERITUBAUFSAiMSEjQYCg/9oACAEBAAEFAv7QEUBE5giJXaU3syKAF7zsMDL3ELUsQVGzyj17HAqDzIpZcZuo0BgrWLoQVWlOlvrZnSH5jgjI5pVVuhyYOusyQ4LySG5dho5crVvVyUDBX0DK7aW4+N1fapj69R/A7FmkLsTQfEqy0pkAhY9PEwUeJnSLNknsXQawXV2ImhZk/FxHC7L3719VmXJA5c/Qt0RjXXFydKtMYK1iyEHXqzpZxZRD1V6go6t+yU/gohjcCxqSU2Llf7kOXfUQ27wmFBW9/W5i6ZZ6perRfxOvJFiKJ7bVmmL8Fl74lWWnMrWKg6rX9fF2NLdQhZVsp4HTd1pUF77HWGYrF+YzOKTG+z4vVuUVtYgnWTfj+a6gUrq7TSSkmMeZDIFVkCR5ZXUzA00Di9X4m5c7cHSzOkLets+LF8Vz8i7VV4XYTXWmMxRrFB/G36WqFwAsVj0txUtr13Sh0Tui83iRWRNhhU6y1nt30iMq8xBC9UodUZLUdQU7R+SKXROsYvo4m5c/Uczj9uva9cYB9w+BVJda57DMGkGF+OqvI4nZc/cD7i04fZZYkClTJgbdViyUY5iQgbGPOlX4V9ZYTzp+5ZprsfKKK1YzFGmMufsNiQbHxqtVV1p6/MUaFlz9pYMYMAoN5o/HYMCGAYEh1Z3OntG1VuYIwMf4KP/EABQRAQAAAAAAAAAAAAAAAAAAALD/2gAIAQMBAT8BG0//xAAUEQEAAAAAAAAAAAAAAAAAAACw/9oACAECAQE/ARtP/8QALhAAAQMBBwMDBAIDAAAAAAAAAQACETESEyEiMkFQAxBRYYGRICNCcWKhcoCg/9oACAEBAAY/Av8AaCXGFUlZXY8mXHZemwU4D9rNh6qw/UOQl5QsCGq02jkJ27OnZN46So6PypcZVt5mPxR6bRHhA7hSXQrHTp5V5sOMlxhVlQHfKJtR019puLf7U/ia9rTcHKkr7pgKy0YcRIM95K/jsFOn9rMPdXb67dsNLqK7dVvFgtOTdXTt6LErBPhNDuzgfCZHntZ32U1d54t15pWUqRacsCQnNOqMV4c0rMbJVjpb1KtbN467FAr7+lB0uRd+QTfVTRywEr7hshWWDDjnpo9iiNtlYOuitbN42XmFHRw9VIl01722agsphC3GHYWN9+MtMErGXFQ4QQmnpiPozMC0K03S5HpnanDYohhmO9npi0VsrvrCJWQe6vW7VVh2l31WX0UMEDhjZqMUHfKkLCpwUbblZ8PWUbs4bIXgoiDQot+EC4Y8ST4QwhikdrQ0uV06oomn1ToEkqa+uytvzvVmxHYF4kji5GlyunVFFGp3gLNTwg4VC/yCsuCshjVmxKl2o8aW77LwQso91JzOV633V26jqLO2VUrI3Hzx963equnUNOxa6hRGkA6uSLXUKjdqDt9+VDn7KGiB/wAFP//EACUQAQACAQQDAAEFAQAAAAAAAAEAESExQVFhEFBxgSCAobHBoP/aAAgBAQABPyH90FNQ5ZUvwCNUfRx7PSJFzOLywS5cu0Cw9BHRuF5PYVdnUxBLm9WAi6E180s8cG1jHo74fXAmAIRZ33jN6eYxyCCMYYEAJS5Jny7jMg+fKOyOH76yogG7KRf4JgBbtDasiwNYe1WX4iCnYgRLNGK2f7ysB7DCiHEawLSHp9NZWEOTyDaBGAKBhdIB0gFpbCZzsGXPi/puRlzi7PV2siwCXzYy+46JPzAag/IruE01NfG1baI7wI1gZXE1/wCrACdIzLzJhwy2DkdZnZmzMLWAQbkAu8oMYXpA4M3vz66x3L9g62f05mW9h6ghAyjEIaYswd7+ZQhHIwMlwGWCKA9XRQJZt5Wxu3BZTRRH3Xl9TXp1HqMNNx9bTKO5clTnKEDw83IsOTkjq5bjCQeCiFUvBDLMF9vWLu66mbUaBEu+AwaZrIc/oVtjzFLA/Ziiv4mPY3WuvTBYqDdljtr8qqDq7E1V14qFodG9mbm+WrK0s4Tnj/F/VRa9Uq16vTGidAczaJoOoYtYlkVb1hSm2hmFmxcoIuG+TLYBwF3IYdhTNspldRbQcN7+pwitLonI/k3gAljp4wvu/GXzbnzHTaENV9Cu0uyxy0RpbiuKppC7vwU3QwAUFHqsN7h0y3T+0QNtCpCraZjoWYtpg6YjpJ/MHhIbQ2pbQIrdr160ley7lopcZqbDt1Kke5l4PDibVv6jKsXaOVh4uaMdmvr64sYfcvU3frwKVhTE8RlAhFtDX2I/YKjB4bDA+O+vams3sN4HIGx/wU//2gAMAwEAAgADAAAAEJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJIAJJJJJJJJJJJJJJJJJJJJJJJJJAJAJJJJJJJJJJJJJJJJJJJJJJJAJJAJJJJJJJJJJJJJJJJJJJJJJJAIAAJJJJJJJJJJJJJJJJJJJIJJIBAJJJJJJJJJJJJJJJJJJJJJBJBJJBJJJJJJJJJJJJJJJJJJJJJJAIBBJJJJJJJJJJJJJJJJJJJJJJAIJAJJJJJJJJJJJJJJJJJJJJJJJIIJJJJJJJJJJJJJJJJJJJJJJJJJIABJJJJJJJJJJJJJJJJJJJJJBBJIAJJJJJJJJJJJJJJJJJJJJJBBAJJIBJJJJJJJJJJJJJJJJJJAAIIIJJJJJJJJJJJJJJJJJJJJJBJIIAJBJJJJJJJJJJJJJJJJJJJJJAJBIJJJJJJJJJJJJJJJJJJJJJJABIJJJJJJJJJJJJJJJJJJJJJJJJJBBJJJJJJJJJJJJJJJJJJJJJJJJJIJJJJJJJJJJJJJJJJJJJJJJJJJJIJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJJP/8QAFBEBAAAAAAAAAAAAAAAAAAAAsP/aAAgBAwEBPxAbT//EABQRAQAAAAAAAAAAAAAAAAAAALD/2gAIAQIBAT8QG0//xAApEAEAAgEDBAIBBAMBAAAAAAABABEhMUFRUGFxgRCRwSDR4fCAoLHx/9oACAEBAAE/EP8AKB4V6pUcmpvjgohaZ3U1IpiYhVouN+8FjSsTMYGrzMPhl2ILT+19Qd8V3PgjwIaMh/EuDYCcMoCBSefhkBfayTa23wV05GKVrwTKo0Sx6Jr8mVcfG60YrhYLI0igTSJ0r135IBwPD0YtvJz48Ai5gkXK6Y21hJRHJWatxE67FVVwDBNSq9u0HkzlZdy+WMa0ftz6gNRCxN41NvNY8pRjcGC3GXOu0O8WgOjqBUAarAijolnyxlCq9pRiIG/eDQRYWX1MFScmx9wVbbO5BtEERLHUjIqubY5JdWT1Of4elrZrX52t4mrn2O249x+ku4IVYnKuYNFr9wOBC0d0IFFGkJ0RAuyZGLZlI9x+FSFWzVFgKulbeOliMRE7ce5eoG48PZiSaaF0ghds3U+RldnvDZ8XDFHQntKMWYe/aIdgFwVwR+mqedh042gISbqBXIbwzKjpOX6LZgiRqmpuMbhyFzCMKNJjyiDYaHUYlSyFk+493pYorUDk+eLIPFSqgtu3NwujZ3n8JqcTE15S+yhTvt010D7rWWzaO4+OJqDi645Xt82wqgf2uLVyo2HyTP421TCgdjl4IiwQ+/v0wirGk7XeoRVPdfRFSo7KN0tD6DW/0dnIin7Ja+d6wj15AMbhLuzSMdl9GTGNalBCHtCn45+NC2GWwlf/AEjhsjsx+yMW5/yCQLGc3snDOBubM0OpRemy/j9TpaJg0icQAebDXz0YdjGHFdRgPvPvt0C0MhuMOrnYbG8VbGC6IzCgzWVgcXwFLCZ+p6dphpEAdxj6uzutjBKbcKw36SThUQ2tcRTAGr5RysDgIUbnxobZFabh+Zo5tjvuPUCa1D2sgVWVjFJcW9nD4ZQ4CB032IUFdZK+LWqXYYEGgHSddZZ0HadNwmjH219PqOjipdeXaExA2XB+7LdzGnHqZDMhf30ZZTe5iKKQhwPqPjytMeCGkY0cOOmnIYdkNIDX5ZUmyTLBvwz3ARh2cD2JhdoINHZ/EwCpdvp9zH4abj3F0H2EzwvI+3T9TPoNtj7mARLXbce/i/wQQUPkgaiG8qVwNKt56jYUUu3eNc3Ib8JAJilHA16q08NKUHa5h5oDR/oU/wD/2Q==';
          }
      ?>

        <figure class="products__box-item <?=$class;?>">
          <a href="<?=$product['href'];?>" class="products__box-item-img-link">
            <img src="<?=$product['thumb'];?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" class="products__box-item-img">
            <?=($product['available']) ? '' : '<div class="products__box-item-not-available"><span>нет в наличии</span></div>'?>
          </a>
          <figcaption>
            <h4 class="products__box-item-title">
              <a href="<?=$product['href'];?>" class="products__box-item-title-link">
                <?=$product['name'];?>
              </a>
            </h4>
            <p class="products__box-item-text"><?=$product['description'];?></p>
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
            <button type="button" onclick="cart.add('<?=$product['product_id'];?>', '<?=$product['minimum'];?>');"
              class="products__box-item-btn">
              <i class="products__box-item-btn-ico"></i>
              Купить
              <? //=$button_cart
                          ?>
            </button>
          </span>
        </figure><!-- /.products__box-item -->
        <?$i++;?>
      <? } ?>

    </div><!-- /.products__box -->
  </div><!-- /.container -->
</section>
<!-- /.products -->
<?}?>

<?=$content_bottom?>

<?=$footer;?>