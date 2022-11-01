$(function () {

    $.getScript("/catalog/view/javascript/_artem.js", function () { });

    $.getScript("/catalog/view/javascript/_header.js", function () { });

    $.getScript("/catalog/view/javascript/_sliders.js", function () { });

    $.getScript("/catalog/view/javascript/_fundraiser.js", function () { });
    
    $.getScript("/catalog/view/javascript/_forms.js", function () { });

    $.getScript("/catalog/view/javascript/_popups.js", function () { });
    

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

});//$(function (){});


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