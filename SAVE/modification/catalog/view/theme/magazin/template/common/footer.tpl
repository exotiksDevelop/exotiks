
            <?php if (isset($yandex_money_metrika_active) && $yandex_money_metrika_active){ ?>
                <?php echo $yandex_metrika; ?>
                <script type="text/javascript">
                    window.dataLayer = window.dataLayer || [];
                    function sendEcommerceAdd(id, quantity) {
                       $.ajax({
                            url: "<?= $yandex_money_product_info_url; ?>",
                            type: 'post',
                            data: 'id=' + id,
                            dataType: 'json',
                            success: function(json) {
                                json.quantity = quantity;
                                dataLayer.push({ecommerce: {add: {products: [json]}}});
                            }
                        });
                    }
                    $(window).on("load", function () {
                        var opencartCartAdd = cart.add;
                        cart.add = function (product_id, quantity) {
                            opencartCartAdd(product_id, quantity);
                            sendEcommerceAdd(product_id, typeof(quantity) !== 'undefined' ? parseInt(quantity) : 1);
                        };
                        $('#button-cart').on('click', function() {
                            sendEcommerceAdd($('#product input[name="product_id"]').val(), parseInt($('#product input[name="quantity"]').val()));
                        });
                    });
                </script>
            <?php } ?>
<footer>
<div id="footer">
		<div class="rassilka">
			<div class="rassilka_content">
				<p>E-mail рассылка об акциях <br /> и новинках магазина (1-2 раза в месяц):</p>
                <a href="/index.php?route=account/newsletter" class="btn btn-primary podpiska_news">Подписаться</a>
			</div>
		</div>
		<div class="footer_menu">
			<div class="navbar nav-justified">
               <div class="container-fluid">
                   <div class="navbar-header">
                       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#my_menu2">
                           <span class="sr-only">Открыть навигацию</span>
                           <span class="icon-bar"></span>
                           <span class="icon-bar"></span>
                           <span class="icon-bar"></span>
                       </button>
                       <span class="visible-xs menufonts">Меню</span>
                   </div>
                   <div class="collapse navbar-collapse" id="my_menu2">
                       <ul class="nav navbar-nav">
                           <li class="gorizontal_li"><a href="/o-nas">О нас</a></li>
            				<li class="gorizontal_li"><a href="/uhod-i-virashivanie">Уход и выращивание</a></li>
                            <li class="gorizontal_li"><a href="/news">Новости</a></li>
            				<li class="gorizontal_li"><a href="/dostavka-i-oplata">Доставка и оплата</a></li>
            				<li class="gorizontal_li"><a href="/faq">Вопрос/ответ</a></li>
            				<li class="gorizontal_li"><a href="/blog">Блог</a></li>
            				<li class="gorizontal_li"><a href="/partneram">Партнерам</a></li>
            				<li class="gorizontal_li"><a href="/index.php?route=information/contact">Контакты</a></li>
                       </ul>
                   </div>
               </div>
           </div>
            
		</div>
		<div class="footer_content row">
		
            <div class="col-md-2 col-sm-3  col-xs-12">
            
            
    			<div class="metrika">
<!-- Yandex.Metrika informer МЕТРИКА -->
<a href="https://metrika.yandex.ru/stat/?id=33909469&amp;from=informer"
target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/33909469/3_1_FFFFFFFF_FFFFFFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" /></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter33909469 = new Ya.Metrika({
                    id:33909469,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    trackHash:true,
                    ecommerce:"dataLayer"
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<!-- /Yandex.Metrika counter МЕТРИКА --> 

<!-- Top100 (Kraken) Counter РАМБЛЕР КАТАЛОГ -->
<script>
    (function (w, d, c) {
    (w[c] = w[c] || []).push(function() {
        var options = {
            project: 4455170
        };
        try {
            w.top100Counter = new top100(options);
        } catch(e) { }
    });
    var n = d.getElementsByTagName("script")[0],
    s = d.createElement("script"),
    f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src =
    (d.location.protocol == "https:" ? "https:" : "http:") +
    "//st.top100.ru/top100/top100.js";

    if (w.opera == "[object Opera]") {
    d.addEventListener("DOMContentLoaded", f, false);
} else { f(); }
})(window, document, "_top100q");
</script>
<noscript><img src="//counter.rambler.ru/top100.cnt?pid=4455170"></noscript>
<!-- END Top100 (Kraken) Counter -->

<!-- Rating@Mail.ru counter МЕТРИКА ОТ МАЙЛ РУ -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "2837550", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2837550;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->
    			</div>
            </div>
            <div class="col-md-3 col-sm-5  col-xs-12">
    			<div class="phone_footer">
    				<a class="phone_f" href="tel:<?php echo $telephone; ?>"><i class="marat-phone"></i><?php echo $telephone; ?></a>
    				<a class="email_f" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
    			</div>
            </div>
            <div class="col-md-2 hidden-sm  col-xs-12">
			     <span>Оставьте заявку и мы Вам перезвоним</span>
            </div>
            <div class="col-md-2 col-sm-4  col-xs-12">
                <a href="#myModal" class="btn btn-primary callme" data-toggle="modal" data-target="#myModal">Заказать Звонок</a>
    		</div>
            <div class="col-md-2 col-sm-12  col-xs-12">
    			<div class="mysocseti">
    				<p>Присоединяйтесь</p>
    				<a target="_blank" href="https://vk.com/exotiks_ru" class="vk"></a>
    				<a target="_blank" href="https://www.instagram.com/exotica.ru/" class="inst"></a>
    				<a target="_blank" href="https://www.facebook.com/exzotiki/" class="fb"></a>
    				<a target="_blank" href="https://www.youtube.com/channel/UCliH9h1_ydygzbB_z1IIIow" class="youtube"></a>
    				<a target="_blank" href="https://ok.ru/group/54793255125019" class="ok"></a>
                    <a target="_blank" href="https://twitter.com/lianaglina" class="tw"></a>
    			</div>
            </div>
            <div class="clear"></div>
		</div>
	</div>
<?php echo $microdatapro; $microdatapro_main_flag = 1; //microdatapro 7.3 - 1 - main ?>
</footer>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Оставьте заявку<br/> и мы вам перезвоним</h4>
      </div>
      <div class="modal-body">       
			<form class="form-block" id="form" role="form">
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="name" placeholder="Как Вас зовут?">
                      <input type="tel" class="form-control" id="phone" placeholder="Ваш номер телефона">
                      <input type="text" class="form-control input-sm" id="datecase" placeholder="Укажите удобное время для звонка">
                      <input id="bottom" class="btn btn-block" onclick="send();" type="button" value="Заказать звонок">
                   
                </div>
            </form>
      </div>
    </div>
  </div>
</div>




<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<link href="catalog/view/theme/magazin/stylesheet/fonts.css" rel="stylesheet">
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="catalog/view/theme/default/stylesheet/geoip.css" rel="stylesheet">

<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/jquery/owl-carousel/owl.carousel.css" rel="stylesheet">
<script src="catalog/view/javascript/jquery.countdown.min.js" type="text/javascript" ></script>
<script type="text/javascript">
  
  $('.countdown').countdown($('.countdown').data("countdown"))
    .on('update.countdown', function(event) {
      var format = '%H:%M:%S';
      if(event.offset.totalDays > 0) {
        format = '%-D %!D:день,дня; ' + format;
      }
      // if(event.offset.weeks > 0) {
      //   format = '%-w неделя%!w ' + format;
      // }
      $(this).html(event.strftime(format));
    })
    .on('finish.countdown', function(event) {
      $(this).html('This offer has expired!')
        .parent().addClass('disabled');

    });
</script>
<?php if(!isset($microdatapro_main_flag)){echo $microdatapro;  $microdatapro_main_flag = 1;} //microdatapro 7.3 - 2 - extra ?>
<?php if(!isset($microdatapro_main_flag)){echo $microdatapro;} //microdatapro 7.3 - 3 - extra ?>
</body></html>