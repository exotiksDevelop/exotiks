$(window).on('load resize', function (e) {

  // var elPhone = $('.head1__myphone-link');
  // if (maxWidth(991)) {
  //   $('.head1__burger-nav-li.phone').show();
  //   $('.head1__burger-nav-li.phone').html(elPhone);
  // } else if (minWidth(992)) {
  //   $('.head1__burger-nav-li.phone').hide();
  //   $('.head1__myphone').html(elPhone);
  // }

  var elConsult = $('.head1__callme');
  if (maxWidth(849)) {
    $('.head1__burger-nav-li.consult').show();
    $('.head1__burger-nav-li.consult').html(elConsult);
  } else if (minWidth(850)) {
    $('.head1__burger-nav-li.consult').hide();
    $('.head1__callme-wrap').html(elConsult);
  }

  var elCity = $('.geoip-module');
  if (maxWidth(669)) {
    $('.head1__city').hide();
    $('.head1__burger-nav-li.city').show();
    $('.head1__burger-nav-li.city').html(elCity);
  } else if (minWidth(670)) {
    $('.head1__burger-nav-li.city').hide();
    $('.head1__city').show();
    $('.head1__city').html(elCity);
  }

});// $(window).on('load resize');



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
});/*#burger*/



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
});/* #headerSearch */