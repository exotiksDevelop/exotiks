<footer class="footer">

    <div class="container">
        <div class="footer__col-left">
            <a class="footer__logo" href="<?php echo $home; ?>">
                <img class="footer__logo-img" src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
                    alt="<?php echo $name; ?>" width="164" height="84" />
            </a>

            <h6 class="footer__title">Магазин живых подарков</h6>

            <div class="footer__hours">
                <time datetime="21:00">10:00-20:00&nbsp;МСК</time>
                <span>Ежедневно</span>
            </div>

        </div>

        <nav class="footer__nav">
            <ul class="footer__nav-list">
                <li class="footer__nav-list-item"><a href="/cataloglist">Каталог</a></li>
                <li class="footer__nav-list-item"><a href="/o-nas">О нас</a></li>
                <li class="footer__nav-list-item"><a href="/uhod-i-virashivanie">Уход и выращивание</a></li>
                <li class="footer__nav-list-item"><a href="/news">Новости</a></li>
                <li class="footer__nav-list-item"><a href="/dostavka-i-oplata">Доставка и оплата</a></li>
                <li class="footer__nav-list-item"><a href="/faq">Вопрос/ответ</a></li>
                <li class="footer__nav-list-item"><a href="/blog">Блог</a></li>
                <li class="footer__nav-list-item"><a href="/partneram">Партнерам</a></li>
                <li class="footer__nav-list-item"><a href="/index.php?route=information/contact">Контакты</a></li>
            </ul>
        </nav>

        <div class="footer__col-right">
            <div class="footer__myphone">
                <a class="footer__myphone-link" href="tel:+74993468636">
                    +7(499) 346-86-36
                </a>
                <br>
                <a class="footer__myphone-link" href="tel:<?php echo (str_replace(" ", "", $telephone)); ?>">
                    <?php echo $telephone; ?>
                </a>
                <br>
                <a class="footer__myphone-link" href="https://wa.me/79993452721">
                    WhatsApp
                </a>
            </div>

            <div class="footer__myemail">info@exotiks.ru</div>

            <!--<a href="#myModal" class="footer__callback" data-toggle="modal" data-target="#myModal">Заказать звонок</a>-->
            <a href="https://t.me/Exotiks_bot" class="footer__callback" data-toggle="modal">Написать в Telegram</a>
        </div><!-- /.footer__col-right -->
    </div><!-- /.container -->
	
    <div id="footer_bottom_box" class="footer__bottom-box">
        <div class="container">
            <span class="footer__bottom-box-title">Мы в соцсетях</span>
            <!-- /.footer__bottom-box-title -->
            <a target="_blank" rel="nofollow" href="https://vk.com/exotiks_ru" class="footer__bottom-box-link"><i class="ico vk- lazy-bg-img-load"></i>
                <!-- /.ico vk --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://www.instagram.com/exotica.ru/" class="footer__bottom-box-link"><i
                    class="ico inst- lazy-bg-img-load"></i> <!-- /.ico inst --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://www.facebook.com/exotiks.ru/" class="footer__bottom-box-link"><i
                    class="ico fb- lazy-bg-img-load"></i> <!-- /.ico fb --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://www.youtube.com/channel/UCliH9h1_ydygzbB_z1IIIow"
                class="footer__bottom-box-link"><i class="ico tube- lazy-bg-img-load"></i> <!-- /.ico you --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://ok.ru/group/54793255125019" class="footer__bottom-box-link"><i
                    class="ico ok- lazy-bg-img-load"></i> <!-- /.ico ok --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://twitter.com/lianaglina" class="footer__bottom-box-link last"><i
                    class="ico twit- lazy-bg-img-load"></i> <!-- /.ico twit --></a>
            <!-- /.footer__bottom-box-link -->
            <a href="/exotiks-doc.pdf" target="_blank" class="footer__bottom-box-policy"><span>Политика
                    конфиденциальности</span></a>
            <!-- /.footer__bottom-box-policy -->
			<span class="cdate" style="position:absolute;bottom:2px;right:25%;">&nbsp;©2011-<?php echo date("Y"); ?>&nbsp;</span>
            <span id="footer_bottom_box_counter" rel="nofollow" class="footer__bottom-box-counter">
				<!--<iframe src="https://yandex.ru/sprav/widget/rating-badge/114384451629" width="150" height="50" frameborder="0"></iframe>-->
				<script>
				$(window).one('scroll', yandex_iframe_init);
				$(document.body).one('touchmove', yandex_iframe_init);
				function yandex_iframe_init() {
					var yandex_iframe = document.createElement('iframe'); 
					yandex_iframe.width = '150';
					yandex_iframe.height = '50';
					yandex_iframe.src = 'https://yandex.ru/sprav/widget/rating-badge/114384451629';
					yandex_iframe.setAttribute("frameborder", "0");
					var godefer = document.getElementById('footer_bottom_box_counter'); 
					godefer.append(yandex_iframe);
				}
				</script>

			</span>
            <!-- /.footer__bottom-box-counter -->
        </div><!-- /.container -->
    </div>
    <!-- /.footer__bottom-box -->

    <div class="metrika">
        <!-- Yandex.Metrika informer МЕТРИКА -->
        <a href="https://metrika.yandex.ru/stat/?id=33909469&amp;from=informer" target="_blank" rel="nofollow"><img
                src="https://informer.yandex.ru/informer/33909469/3_1_FFFFFFFF_FFFFFFFF_0_pageviews"
                style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика"
                title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" /></a>
        <!-- /Yandex.Metrika informer -->

        <!-- Yandex.Metrika counter -->
        <script>
		$(document.body).one('mousemove', yandex_metrika_init);
		$(document.body).one('touchmove', yandex_metrika_init);
		function yandex_metrika_init() {
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function () {
                    try {
                        w.yaCounter33909469 = new Ya.Metrika({
                            id: 33909469,
                            clickmap: true,
                            trackLinks: true,
                            accurateTrackBounce: true,
                            webvisor: true,
                            trackHash: true,
                            ecommerce: "dataLayer"
                        });
                    } catch (e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () {
                        n.parentNode.insertBefore(s, n);
                    };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "yandex_metrika_callbacks");
		}
        </script>
        <!-- /Yandex.Metrika counter МЕТРИКА -->

        <!-- Top100 (Kraken) Counter РАМБЛЕР КАТАЛОГ -->
        <script>
		$(window).one('scroll', rambler_init);
		$(document.body).one('touchmove', rambler_init);
		function rambler_init() {
            (function (w, d, c) {
                (w[c] = w[c] || []).push(function () {
                    var options = {
                        project: 4455170
                    };
                    try {
                        w.top100Counter = new top100(options);
                    } catch (e) { }
                });
                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () {
                        n.parentNode.insertBefore(s, n);
                    };
                s.type = "text/javascript";
                s.async = true;
                s.src =
                    (d.location.protocol == "https:" ? "https:" : "http:") +
                    "//st.top100.ru/top100/top100.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(window, document, "_top100q");
		}
        </script>
        <noscript><img src="//counter.rambler.ru/top100.cnt?pid=4455170" alt=""></noscript>
        <!-- END Top100 (Kraken) Counter -->

        <!-- Rating@Mail.ru counter МЕТРИКА ОТ МАЙЛ РУ -->
        <script>
		$(window).one('scroll', mailru_init);
		$(document.body).one('touchmove', mailru_init);
		function mailru_init() {
            var _tmr = window._tmr || (window._tmr = []);
            _tmr.push({
                id: "2837550",
                type: "pageView",
                start: (new Date()).getTime()
            });
            (function (d, w, id) {
                if (d.getElementById(id)) return;
                var ts = d.createElement("script");
                ts.type = "text/javascript";
                ts.async = true;
                ts.id = id;
                ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
                var f = function () {
                    var s = d.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(ts, s);
                };
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "topmailru-code");
		}
        </script>
        <noscript>
            <div style="position:absolute;left:-10000px;">
                <img src="//top-fwz1.mail.ru/counter?id=2837550;js=na" style="border:0;" height="1" width="1"
                    alt="Рейтинг@Mail.ru" />
            </div>
        </noscript>
        <!-- //Rating@Mail.ru counter -->
    </div><!-- ./metrika -->

    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel2">Оставьте заявку<br /> и мы вам перезвоним</h4>
                </div>
                <div class="modal-body">
                    <form class="form-block" id="form2" method="post" action="/send.php">
                        <!--<div class="col-sm-12">
                            <input type="text" class="form-control" id="name2" placeholder="Как Вас зовут?">
                            <input type="tel" class="form-control" id="phone2" placeholder="Ваш номер телефона">
                            <input type="text" class="form-control input-sm" id="datecase2"
                                placeholder="Укажите удобное время для звонка">
                            <input id="bottom2" class="btn btn-block" onclick="send2();" type="button"
                                value="Заказать звонок">
                        </div>-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ./modal -->

</footer><!-- /.footer -->

<!-- old theme -->
<!-- <link href="/catalog/view/theme/magazin/stylesheet/fonts.css" rel="stylesheet"> -->
<!-- font-awesome все еще присутствуют на сайте -->
<!--<link  href="/catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->
<!-- <link href="/catalog/view/javascript/jquery/owl-carousel/owl.carousel.css" rel="stylesheet"> -->
<!-- old theme -->
<!--<script  src="/catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->
<!--<script  src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>-->
<!--<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>-->
<!--<script  src="/catalog/view/javascript/jquery.countdown.min.js" type="text/javascript"></script>-->
<script  src="/catalog/view/javascript/dev.js" onload="init_ym();"></script>
<!--<script async src="https://privacypolicy.lider-poiska.ru/" defer></script>-->

<script>
function script_exists(src) {
	return document.querySelector('script[src="' + src + '"]') ? true : false;
}
function load_js(src, loc = 'head', onLoad = function(){}) {
	if (script_exists(src)) {
		return;
	}
	var _script = document.createElement('script'); 
	_script.type = 'text/javascript'; 
	_script.charset = 'utf-8';
	_script.async = '';
	_script.src = src;
	_script.onload = onLoad;
	var godefer = document.getElementsByTagName(loc)[0]; 
	godefer.append(_script);
}
function load_jss() {
	load_js('catalog/view/javascript/bootstrap/js/bootstrap.min.js');
	load_js('https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', 'body', function() { $(document).trigger('slickLoaded'); });
	load_js('https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js', 'body', function() { $(document).trigger('inputmaskLoaded'); });
	load_js('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
	load_js('catalog/view/javascript/smartnotifications/noty/packaged/jquery.noty.packaged.js');
	load_js('catalog/view/javascript/smartnotifications/noty/themes/smart-notifications.js', 'head', function() { $(document).trigger('smartNotificationsLoaded'); });
	load_js('catalog/view/javascript/oca_back_to_top/oca_back_to_top.js');
	load_js('//api-maps.yandex.ru/2.1/?lang=ru_RU&ns=cdekymap');
	load_js('catalog/view/javascript/sdek.js');
	load_js('https://static.yandex.net/kassa/pay-in-parts/ui/v1/');
	load_js('catalog/view/javascript/jquery.countdown.min.js', 'body', function() { $(document).trigger('countdownLoaded'); });
	//load_js('https://privacypolicy.lider-poiska.ru/');
	
	load_css('https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css');
	load_css('catalog/view/javascript/font-awesome/css/font-awesome.min.css');
	load_css('catalog/view/theme/default/stylesheet/geoip.css');
	load_css('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
	load_css('catalog/view/theme/default/stylesheet/sdek.css');
	load_css('catalog/view/theme/default/stylesheet/smartnotifications/animate.css');
	load_css('catalog/view/theme/default/stylesheet/smartnotifications/smartnotifications.css');
	load_css('catalog/view/javascript/oca_back_to_top/oca_back_to_top.php');
	
}
$(window).one('scroll', load_jss);
$(document.body).one('touchmove', load_jss);
function isInViewport(element) {
	if (typeof(element) === 'undefined' || element == null) {
		return false;
	}
	var rect = element.getBoundingClientRect();
	return (
		rect.bottom >= 0 && 
		rect.right >= 0 && 
		rect.top <= (window.innerHeight || document.documentElement.clientHeight) && 
		rect.left <= (window.innerWidth || document.documentElement.clientWidth)
	);
}
function lazy_load_images() {
	$(document.body).find("img.lazy").not(".loaded").each(function() {
		lazy_load_image($(this)[0]);
	});
}
function lazy_load_image(img) {
	if (isInViewport(img)) {
		$(img).attr("src", $(img).attr("data-src"));
		$(img).addClass("loaded");
	}
}
$(window).on('scroll', lazy_load_images);
$(document.body).on('touchmove', lazy_load_images);
function lazy_load_bg_images() {
	if (isInViewport($('#top_banner_img_right')[0])) {
		$('#top_banner_img_right').addClass('top-banner__img-right');
	}
	if (isInViewport($('#request_img_right')[0])) {
		$('#request_img_right').addClass('request__img-right');
	}
	if (isInViewport($('#ourservice_box_bottom')[0])) {
		$('#ourservice_box_bottom').find('.ourservice__box-bottom-item-ico.ico-1-').addClass('ico-1');
		$('#ourservice_box_bottom').find('.ourservice__box-bottom-item-ico.ico-2-').addClass('ico-2');
		$('#ourservice_box_bottom').find('.ourservice__box-bottom-item-ico.ico-3-').addClass('ico-3');
	}
	if (isInViewport($('#slider_top_section')[0])) {
		$('#slider_top_section').addClass('slider');
	}
	if (isInViewport($('#our_store_section')[0])) {
		$('#our_store_section').addClass('our-store');
	}
	if (isInViewport($('#delivery_section')[0])) {
		$('#delivery_section').addClass('delivery');
	}
	if (isInViewport($('#delivery_box')[0])) {
		$('#delivery_box').find('.delivery__box-item-ico.ico-1-').addClass('ico-1');
		$('#delivery_box').find('.delivery__box-item-ico.ico-2-').addClass('ico-2');
		$('#delivery_box').find('.delivery__box-item-ico.ico-3-').addClass('ico-3');
	}
	if (isInViewport($('#footer_bottom_box')[0])) {
		$('#footer_bottom_box').find('.footer__bottom-box-link .ico.vk-').addClass('vk');
		$('#footer_bottom_box').find('.footer__bottom-box-link .ico.inst-').addClass('inst');
		$('#footer_bottom_box').find('.footer__bottom-box-link .ico.fb-').addClass('fb');
		$('#footer_bottom_box').find('.footer__bottom-box-link .ico.tube-').addClass('tube');
		$('#footer_bottom_box').find('.footer__bottom-box-link .ico.ok-').addClass('ok');
		$('#footer_bottom_box').find('.footer__bottom-box-link .ico.twit-').addClass('twit');
	}
	
}
$(window).on('scroll', lazy_load_bg_images);
$(document.body).on('touchmove', lazy_load_bg_images);

$('#slider_top_section').one('click', function() { $(window).trigger('scroll'); });
$('#page_header').one('mousemove', function() { load_js('catalog/view/javascript/bootstrap/js/bootstrap.min.js'); });

function load_css(file) {
	var css = document.createElement('link'); 
	css.type = 'text/css'; 
	css.rel = 'stylesheet';
	css.href = file;
	var godefer = document.getElementsByTagName('head')[0]; 
	godefer.append(css);
}
$(document).ready(function() {
	load_css('//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
	load_js('catalog/view/javascript/jquery/jquery.geoip-module.js', 'head', function() { $(document).trigger('geoipModuleLoaded'); });
	setTimeout(function() { lazy_load_images(); }, 1000);
	//lazy_load_bg_images();
	setTimeout(function() { lazy_load_bg_images(); }, 1000);
	
});
</script>

<!-- Scroll up -->
<div id="up" style="display:none;">
  	<div class="wrap">
	    <span class="icon fa fa-arrow-up"></span>
	</div>
</div>
<script><!--
var topLink = $('#up');
function scroll_up() {
	if (isInViewport($('#page_header')[0])) {
		topLink.fadeOut(300);
	} 
	else {
		topLink.fadeIn(300);
	}
}
$(window).on('scroll', scroll_up);
$(document.body).on('touchmove', scroll_up);
topLink.on('click', function(e) {
	$("body, html").animate({ scrollTop: 0 }, 500, function() { topLink.fadeOut(300); });
	return false;
});
//--></script>
<!--// Scroll up -->

</body>

</html>