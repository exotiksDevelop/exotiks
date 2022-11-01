//special.tpl start
$(document).on('countdownLoaded', function() {
$('.fundraiser__block2-countdown').countdown($('.fundraiser__block2-countdown').data("countdown"))
.on('update.countdown', function (event) {//'%-D %!D:день,дня; '
  var format = `
    <span class="fundraiser__block2-sub sub-2">%D<span>дней</span></span>
    <span class="fundraiser__block2-sub sub-3">%H<span>часов</span></span>
    <span class="fundraiser__block2-sub sub-4">%M<span>минут</span></span>
    <span class="fundraiser__block2-sub sub-5">%S<span>секунд</span></span>
  `;
  $(this).html(event.strftime(format));
}).on('finish.countdown', function (event) {
  $(this).html('This offer has expired!').parent().addClass('disabled');
});//special.tpl end
});