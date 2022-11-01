$(function () {

  $(document).ready(function () {

    $('#burger').on('click', function () {
      $('#burger').toggleClass('active');
      // $('#topNav').toggleClass('active');
      if ($('#burger').hasClass('active')) {
        $('#topNav').slideDown();
        if (isMobile()) {
          $('#topNav').css({
            'overflow-y': 'auto',
            'bottom': '0'
          });
          $('body').css('overflow', 'hidden');
        }
      } else {
        $('#topNav').slideUp();
        $('body').css('overflow', 'auto');
      }
    }); /* #burger */



    $(window).on('load resize', function (e) {

      var elPhone = $('.head1__myphone-link');
      if (maxWidth(991)) {
        $('.head1__burger-nav-li.phone').show();
        $('.head1__burger-nav-li.phone').html(elPhone);
      } else if (minWidth(992)) {
        $('.head1__burger-nav-li.phone').hide();
        $('.head1__myphone').html(elPhone);
      }

      var elConsult = $('.head1__callme');
      if (maxWidth(767)) {
        $('.head1__burger-nav-li.consult').show();
        $('.head1__burger-nav-li.consult').html(elConsult);
      } else if (minWidth(768)) {
        $('.head1__burger-nav-li.consult').hide();
        $('.head1__callme-wrap').html(elConsult);
      }

      var elCity = $('.geoip-module');
      if (maxWidth(499)) {
        $('.head1__city').hide();
        $('.head1__burger-nav-li.city').show();
        $('.head1__burger-nav-li.city').html(elCity);
      } else if (minWidth(500)) {
        $('.head1__burger-nav-li.city').hide();
        $('.head1__city').show();
        $('.head1__city').html(elCity);
      }

    });// $(window).on('load resize')



    //special.tpl start
    $('.fundraiser__block2-countdown').countdown($('.fundraiser__block2-countdown').data("countdown"))
      .on('update.countdown', function (event) {//'%-D %!D:день,дня; '
        var format = `
        <span class="fundraiser__block2-sub sub-2">%D<span>дней</span></span>
        <span class="fundraiser__block2-sub sub-3">%H<span>часов</span></span>
        <span class="fundraiser__block2-sub sub-4">%M<span>минут</span></span>
        <span class="fundraiser__block2-sub sub-5">%S<span>секунд</span></span>
        `;
        // var format = '%H:%M:%S';
        // if (event.offset.totalDays > 0) {
        //   format = '%-D %!D:день,дня; ' + format;
        // }
        // if(event.offset.weeks > 0) {
        //   format = '%-w неделя%!w ' + format;
        // }
        $(this).html(event.strftime(format));
      })
      .on('finish.countdown', function (event) {
        $(this).html('This offer has expired!')
          .parent().addClass('disabled');
      });//special.tpl end



    $("#phone").inputmask({
      "mask": "+7 (999) 999-99-99"
    });



    $('#headerSearch input[name=\'search\']').parent().find('button').on('click', function () {
      url = $('base').attr('href') + 'index.php?route=product/search';

      var value = $('input[name=\'search\']').val();

      if (value) {
        url += '&search=' + encodeURIComponent(value);
      }

      location = url;
    });
    $('#headerSearch input[name=\'search\']').on('keydown', function (e) {
      if (e.keyCode == 13) {
        $('input[name=\'search\']').parent().find('button').trigger('click');
      }
    });/* #search */



    $('.slider__slick').slick(
      {
        prevArrow: '<i class="slider__box-left-arrow arrow"></i>',
        nextArrow: '<i class="slider__box-right-arrow arrow"></i>'
      }
    );

    $('.product-detail__slider').slick(
      {
        prevArrow: '<i class="product-detail__slider-arrow-left"></i>',
        nextArrow: '<i class="product-detail__slider-arrow-right"></i>'
      }
    );



    //product.tpl start
    //TODO если type number то не работает добавление в корзину правильного кол-ва
    $('#countNumPlus').on('click', function () {
      $('#input-quantity').val(Number($('#input-quantity').val()) + 1);
    });
    $('#countNumMinus').on('click', function () {
      if (Number($('#input-quantity').val()) <= 1) {
        $('#input-quantity').val(1);
      } else {
        $('#input-quantity').val(Number($('#input-quantity').val()) - 1);
      }
    });

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

    $('#review').delegate('.pagination a', 'click', function (e) {
      e.preventDefault();

      $('#review').fadeOut('slow');

      $('#review').load(this.href);

      $('#review').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/product/review&product_id=<?= $product_id; ?>');

    $('#button-review').on('click', function () {
      $.ajax({
        url: 'index.php?route=product/product/write&product_id=<?= $product_id; ?>',
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
    //product.tpl end



    function isMobile() {
      return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
    }

    function maxWidth(maxPx) {
      if ((window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth) <= maxPx) {
        return true;
      } else {
        return false;
      }
    }

    function minWidth(minPx) {
      if ((window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth) >= minPx) {
        return true;
      } else {
        return false;
      }
    }
  });//$(document).ready()



  //cart add remove functions
  var cart = {
    'add': function (product_id, quantity) {
      $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: 'product_id=' + product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
        dataType: 'json',
        beforeSend: function () {
          $('#cart > button').button('loading');
        },
        complete: function () {
          $('#cart > button').button('reset');
        },
        success: function (json) {
          $('.alert, .text-danger').remove();

          if (json['redirect']) {
            location = json['redirect'];
          }

          if (json['success']) {
            $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            // console.log('success');
            // Need to set timeout otherwise it wont update the total
            setTimeout(function () {
              console.log(json);
              $('#cartCount').text(json['count']);
            }, 100);

            // $('html, body').animate({ scrollTop: 0 }, 'slow');

            $('#cart > ul').load('index.php?route=common/cart/info ul li');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    },
    'update': function (key, quantity) {
      $.ajax({
        url: 'index.php?route=checkout/cart/edit',
        type: 'post',
        data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
        dataType: 'json',
        beforeSend: function () {
          $('#cart > button').button('loading');
        },
        complete: function () {
          $('#cart > button').button('reset');
        },
        success: function (json) {
          // Need to set timeout otherwise it wont update the total
          setTimeout(function () {
            $('#cart > button').html(json['total']);
          }, 100);

          if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
            location = 'index.php?route=checkout/cart';
          } else {
            $('#cart > ul').load('index.php?route=common/cart/info ul li');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    },
    'remove': function (key) {
      $.ajax({
        url: 'index.php?route=checkout/cart/remove',
        type: 'post',
        data: 'key=' + key,
        dataType: 'json',
        beforeSend: function () {
          $('#cart > button').button('loading');
        },
        complete: function () {
          $('#cart > button').button('reset');
        },
        success: function (json) {
          // Need to set timeout otherwise it wont update the total
          setTimeout(function () {
            $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
          }, 100);

          if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
            location = 'index.php?route=checkout/cart';
          } else {
            $('#cart > ul').load('index.php?route=common/cart/info ul li');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }//cart add remove functions



});//$(document).ready()



