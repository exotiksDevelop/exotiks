$(function () {

  // $('#review').delegate('.pagination a', 'click', function (e) {
  //   e.preventDefault();

  //   $('#review').fadeOut('slow');

  //   $('#review').load(this.href);

  //   $('#review').fadeIn('slow');
  // });

  // $('#review').load('index.php?route=product/product/review&product_id=<?= $product_id; ?>');
  // $('#button-review').on('click', function () {
  //   $.ajax({
  //     url: 'index.php?route=product/product/write&product_id=<?= $product_id; ?>',
  //     type: 'post',
  //     dataType: 'json',
  //     data: $("#form-review").serialize(),
  //     beforeSend: function () {
  //       $('#button-review').button('loading');
  //     },
  //     complete: function () {
  //       $('#button-review').button('reset');
  //     },
  //     success: function (json) {
  //       $('.alert-success, .alert-danger').remove();

  //       if (json['error']) {
  //         $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
  //       }

  //       if (json['success']) {
  //         $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

  //         $('input[name=\'name\']').val('');
  //         $('textarea[name=\'text\']').val('');
  //         $('input[name=\'rating\']:checked').prop('checked', false);
  //       }
  //     }
  //   });
  // });
  //product.tpl end

      // $('.thumbnails').magnificPopup({
    //   type: 'image',
    //   delegate: 'a',
    //   gallery: {
    //     enabled: true
    //   }
    // });


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
    //         $('#cartCount').text(json['count']);
    //         // $('html, body').animate({
    //         //   scrollTop: 0
    //         // }, 'slow');
    //         $('#cart > ul').load('index.php?route=common/cart/info ul li');
    //       }
    //     },
    //     error: function (xhr, ajaxOptions, thrownError) {
    //       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    //     }
    //   });
    // });


    //simplecheckout
    // var navTitles = $('.checkout-heading.panel-heading');
    // var navPanel = $('#navPanel');
    // var navContents = $('.simplecheckout-block')

    // navTitles.each(function (i) {
    //   navPanel.append(this);
    // });

    // navTitles.on('click', function (e) {
    //   navTitles.removeClass('active');
    //   $(this).addClass('active');
    //   navContents.removeClass('active');
    //   var currentId = $(this).data('id');
    //   $('#' + currentId).addClass('active');
    // });

  // $('.date').datetimepicker({
  //   pickTime: false
  // });


  // $('.datetime').datetimepicker({
  //   pickDate: true,
  //   pickTime: true
  // });


  // $('.time').datetimepicker({
  //   pickDate: false
  // });


  // $('button[id^=\'button-upload\']').on('click', function () {
  //   var node = this;

  //   $('#form-upload').remove();

  //   $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

  //   $('#form-upload input[name=\'file\']').trigger('click');

  //   if (typeof timer != 'undefined') {
  //     clearInterval(timer);
  //   }
  //   timer = setInterval(function () {
  //     if ($('#form-upload input[name=\'file\']').val() != '') {
  //       clearInterval(timer);
  //       $.ajax({
  //         url: 'index.php?route=tool/upload',
  //         type: 'post',
  //         dataType: 'json',
  //         data: new FormData($('#form-upload')[0]),
  //         cache: false,
  //         contentType: false,
  //         processData: false,
  //         beforeSend: function () {
  //           $(node).button('loading');
  //         },
  //         complete: function () {
  //           $(node).button('reset');
  //         },
  //         success: function (json) {
  //           $('.text-danger').remove();

  //           if (json['error']) {
  //             $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
  //           }

  //           if (json['success']) {
  //             alert(json['success']);

  //             $(node).parent().find('input').attr('value', json['code']);
  //           }
  //         },
  //         error: function (xhr, ajaxOptions, thrownError) {
  //           alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  //         }
  //       });
  //     }
  //   }, 500);
  // });

});

