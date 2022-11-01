
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
<footer class="footer">

    <div class="container">
        <div class="footer__col-left">
            <a class="footer__logo" href="<?php echo $home; ?>">
                <img class="footer__logo-img" src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
                    alt="<?php echo $name; ?>" />
            </a>

            <h6 class="footer__title">Магазин живых подарков</h6>

            <div class="footer__hours">
                <time datetime="T10:00-20:00">10:00-20:00&nbsp;МСК</time>
                <span>Понедельник&nbsp;-&nbsp;Суббота</span>
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
                <a class="footer__myphone-link" href="tel:+79993452721">
                    +7(999) 345-27-21
                </a>
                <br>
                <a class="footer__myphone-link" href="tel:<?php echo $telephone; ?>">
                    <?php echo $telephone; ?>
                </a>
                <br>
                <a class="footer__myphone-link" href="https://wa.me/79993452721">
                    WhatsApp
                </a>
            </div>

            <div class="footer__myemail">info@exotiks.ru</div>

            <a href="#myModal" class="footer__callback" data-toggle="modal" data-target="#myModal">Заказать звонок</a>
        </div><!-- /.footer__col-right -->


    </div><!-- /.container -->
    <div class="footer__bottom-box">
        <div class="container">
            <span class="footer__bottom-box-title">Мы в соцсетях</span>
            <!-- /.footer__bottom-box-title -->
            <a target="_blank" rel="nofollow" href="https://vk.com/exotiks_ru" class="footer__bottom-box-link"><i class="ico vk"></i>
                <!-- /.ico vk --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://www.instagram.com/exotica.ru/" class="footer__bottom-box-link"><i
                    class="ico inst"></i> <!-- /.ico inst --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://www.facebook.com/exotiks.ru/" class="footer__bottom-box-link"><i
                    class="ico fb"></i> <!-- /.ico fb --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://www.youtube.com/channel/UCliH9h1_ydygzbB_z1IIIow"
                class="footer__bottom-box-link"><i class="ico tube"></i> <!-- /.ico you --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://ok.ru/group/54793255125019" class="footer__bottom-box-link"><i
                    class="ico ok"></i> <!-- /.ico ok --></a>
            <!-- /.footer__bottom-box-link -->
            <a target="_blank" rel="nofollow" href="https://twitter.com/lianaglina" class="footer__bottom-box-link last"><i
                    class="ico twit"></i> <!-- /.ico twit --></a>
            <!-- /.footer__bottom-box-link -->
            <a href="/exotiks-doc.pdf" target="_blank" class="footer__bottom-box-policy"><span>Политика
                    конфиденциальности</span></a>
            <!-- /.footer__bottom-box-policy -->
            <span rel="nofollow" class="footer__bottom-box-counter"><iframe src="https://yandex.ru/sprav/widget/rating-badge/114384451629" width="150" height="50" frameborder="0"></iframe></span>
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
        <script  type="text/javascript">
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
        </script>
        <!-- /Yandex.Metrika counter МЕТРИКА -->

        <!-- Top100 (Kraken) Counter РАМБЛЕР КАТАЛОГ -->
        <script>
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
        </script>
        <noscript><img src="//counter.rambler.ru/top100.cnt?pid=4455170"></noscript>
        <!-- END Top100 (Kraken) Counter -->

        <!-- Rating@Mail.ru counter МЕТРИКА ОТ МАЙЛ РУ -->
        <script type="text/javascript">
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
        </script>
        <noscript>
            <div style="position:absolute;left:-10000px;">
                <img src="//top-fwz1.mail.ru/counter?id=2837550;js=na" style="border:0;" height="1" width="1"
                    alt="Рейтинг@Mail.ru" />
            </div>
        </noscript>
        <!-- //Rating@Mail.ru counter -->
    </div><!-- ./metrika -->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Оставьте заявку<br /> и мы вам перезвоним</h4>
                </div>
                <div class="modal-body">
                    <form class="form-block" id="form2" role="form" method="post" action="/send.php">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name2" placeholder="Как Вас зовут?">
                            <input type="tel" class="form-control" id="phone2" placeholder="Ваш номер телефона">
                            <input type="text" class="form-control input-sm" id="datecase2"
                                placeholder="Укажите удобное время для звонка">
                            <input id="bottom" class="btn btn-block" onclick="send2();" type="button"
                                value="Заказать звонок">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ./modal -->

<?php echo $microdatapro; $microdatapro_main_flag = 1; //microdatapro 7.3 - 1 - main ?>
</footer><!-- /.footer -->

<!-- old theme -->
<!-- <link href="/catalog/view/theme/magazin/stylesheet/fonts.css" rel="stylesheet"> -->
<!-- font-awesome все еще присутствуют на сайте -->
<link  href="/catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!-- <link href="/catalog/view/javascript/jquery/owl-carousel/owl.carousel.css" rel="stylesheet"> -->
<!-- old theme -->
<script  src="/catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script  src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script  src="/catalog/view/javascript/jquery.countdown.min.js" type="text/javascript"></script>
<script  src="/catalog/view/javascript/dev.js"></script>

<?php if(!isset($microdatapro_main_flag)){echo $microdatapro;  $microdatapro_main_flag = 1;} //microdatapro 7.3 - 2 - extra ?>
</body>

<?php if(!isset($microdatapro_main_flag)){echo $microdatapro;} //microdatapro 7.3 - 3 - extra ?>
</html>