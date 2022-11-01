<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->

<head>
  <meta charset="UTF-8" />
  <meta name="yandex-verification" content="249209024a0d9ca4" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name='wmail-verification' content='5f72fdf8d7d46c71fa9d982b0386ec75' />
  <meta name="yandex-verification" content="0b78c49282269c8f" />

  <title><?php echo $title; ?></title>
  <base href="<?php echo $base; ?>" />
  <?php if ($description) { ?>
  <meta name="description" content="<?php echo $description; ?>" />
  <?php } ?>
  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?php echo $keywords; ?>" />
  <?php } ?>
  <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
  <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
  <script src="catalog/view/javascript/jquery/jquery.geoip-module.js" type="text/javascript"></script>
  <link href="catalog/view/theme/magazin/stylesheet/stylesheet.css" rel="stylesheet">
  <meta name="google-site-verification" content="WNmhBBsTBV2zdfuF3sBhXNAfllji58-EEPERr62vLc8" />

  <?php foreach ($styles as $style) { ?>
  <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>"
    media="<?php echo $style['media']; ?>" />
  <?php } ?>

  <?php foreach ($links as $link) { ?>
  <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
  <?php } ?>
  <?php foreach ($scripts as $script) { ?>
  <script src="<?php echo $script; ?>" type="text/javascript"></script>
  <?php } ?>
  <?php foreach ($analytics as $analytic) { ?>
  <?php echo $analytic; ?>
  <?php } ?>


<body class="<?php echo $class; ?>">
  <!-- Facebook Pixel Code -->
  <script>
    !function (f, b, e, v, n, t, s) {
      if (f.fbq) return; n = f.fbq = function () {
        n.callMethod ?
        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
      n.queue = []; t = b.createElement(e); t.async = !0;
      t.src = v; s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '776613466003917');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=776613466003917&ev=PageView&noscript=1" /></noscript>
  <!-- End Facebook Pixel Code -->

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5TSVV7" height="0" width="0"
      style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div id="container">
    <div class="header">
      <div class="head1">
        <div class="head1_c">
          <div class="mycity">
            <?php echo $geoip; ?>
          </div>
        </div>
        <div class="col-sm-3"><?php echo $search; ?></div>

        <div class="myphone">
          <a class="phone" href="tel:<?php echo $telephone; ?>"><i class="marat-phone"></i><?php echo $telephone; ?></a>
          <a href="#myModal" class="button callme" data-toggle="modal" data-target="#myModal">Нужна
            консультация!</a>
        </div>

      </div>
      <div class="head2  container row">
        <div class="col-md-4 col-sm-4 col-xs-6 logo">
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>"
              alt="<?php echo $name; ?>" /></a>

        </div>
        <div class="col-md-2 hidden-sm col-xs-12">
          <div class="vremya_raboty">
            <h2>Время <font style="color: #cb536a">работы</font>
            </h2>
            <span><?php echo $open; ?></span>
          </div>
        </div>
        <div class=" col-md-2 col-xs-12 col-sm-3">
          <div class="korzina">
            <h2><a href="/index.php?route=checkout/simplecheckout" title="Перейти в корзину">Корзина</a></h2>
            <span class="link_oform"><a href="/index.php?route=checkout/simplecheckout"
                title="Перейти в корзину">Оформить заказ</a></span>
            <span><?php echo $cart; ?></span>

          </div>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12">
          <div class="vhod">
            <?php if ($logged) { ?>
            <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><br />
            <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
            <?php } else { ?>
            <a href="<?php echo $register; ?>"><?php echo $text_register; ?></a><br />
            <a href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
          <div class="socseti">
            <a rel=”nofollow” target="_blank" href="https://vk.com/exotiks_ru" class="vk"></a>
            <a rel=”nofollow” target="_blank" href="https://www.instagram.com/exotica.ru/" class="instagramm"></a>
            <a rel=”nofollow” target="_blank" href="https://www.facebook.com/exzotiki/" class="facebook"></a>
            <a rel=”nofollow” target="_blank" href="https://ok.ru/group/54793255125019" class="ok"></a>
            <a rel=”nofollow” target="_blank" href="https://www.youtube.com/channel/UCliH9h1_ydygzbB_z1IIIow"
              class="youtube"></a>
            <a rel=”nofollow” target="_blank" href="https://twitter.com/lianaglina" class="twitter"></a>
          </div>
        </div>
      </div>
      <div class="navbar nav-justified">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#my_menu">
              <span class="sr-only">Открыть навигацию</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <span class="visible-xs menufonts">Меню</span>
          </div>
          <div class="collapse navbar-collapse 111" id="my_menu">
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
  </div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Оставьте заявку<br /> и мы вам перезвоним</h4>
        </div>
        <div class="modal-body">
          <form class="form-block" id="form" role="form">
            <div class="col-sm-12">
              <input type="text" class="form-control" id="name" placeholder="Как Вас зовут?">
              <input type="tel" class="form-control" id="phone" placeholder="Ваш номер телефона">
              <input type="text" class="form-control input-sm" id="datecase"
                placeholder="Укажите удобное время для звонка">
              <input id="bottom" class="btn btn-block" onclick="send();" type="button" value="Заказать звонок">

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>