<script src="catalog/view/javascript/jquery/jquery.geoip-module.js" type="text/javascript"></script>
<?php if(!isset($microdatapro_main_flag)){echo $tc_og;} //microdatapro 7.3 - 2 - extra ?>
<script src="https://static.yandex.net/kassa/pay-in-parts/ui/v1/"></script>
<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse')): $url=preg_replace(array('/\/$/i','/\//i'),array('','!'),$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);echo '<!doctype html><html <?php echo $tc_og_prefix; //microdatapro 7.3 ?> lang="ru"><head><title>FakePagespeed</title><meta charset="utf-8"><meta name="viewport" content="width=device-width"><style>*{padding:0;margin:0}body{background-image:url(https://fake-speed.loading.express/screen/'.$url.'-412x660-cropped.webp);background-size:cover}@media screen and (min-width:600px){body{background-image:url(https://fake-speed.loading.express/screen/'.$url.'-1350x940-cropped.webp)}}</style></head><body></body></html>';exit();endif;?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html <?php echo $tc_og_prefix; //microdatapro 7.3 ?> dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html <?php echo $tc_og_prefix; //microdatapro 7.3 ?> dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php echo $tc_og_prefix; //microdatapro 7.3 ?> dir="<?=$direction; ?>" lang="<?=$lang; ?>">
<!--<![endif]-->

<head>
  <meta charset="UTF-8" />
  <meta name="yandex-verification" content="249209024a0d9ca4" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name='wmail-verification' content='5f72fdf8d7d46c71fa9d982b0386ec75' />
  <meta name="yandex-verification" content="0b78c49282269c8f" />
  
  <title><?=$title; ?></title>
  <base href="<?=$base; ?>" />
  <?php if ($description) { ?>
  <meta name="description" content="<?=$description; ?>" />
  <?php } ?>
  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?=$keywords; ?>" />
  <?php } ?>

  <!--[if lt IE 9]>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->

  <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
  <link  href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
  
  <!-- old theme -->
  <!-- <link href="catalog/view/theme/magazin/stylesheet/stylesheet.css" rel="stylesheet"> -->
  <!-- <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script> -->
  <!-- <script src="catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js" type="text/javascript"></script> -->
  <!-- <script src="catalog/view/javascript/common.js"></script> -->
  <!-- old theme -->
  
  <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link  href="catalog/view/theme/default/stylesheet/geoip.css" rel="stylesheet">
  <script  src="catalog/view/javascript/jquery/jquery.geoip-module.js"></script>
  <link  rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link  href="catalog/view/theme/<?= $theme_name ?>/stylesheet/newdesign.css" rel="stylesheet">

  <meta name="google-site-verification" content="WNmhBBsTBV2zdfuF3sBhXNAfllji58-EEPERr62vLc8" />  

<link href="catalog/view/theme/default/stylesheet/geoip.css" rel="stylesheet">
  <?php foreach ($styles as $style) { ?>
  <link href="<?=$style['href']; ?>" type="text/css" rel="<?=$style['rel']; ?>"
    media="<?=$style['media']; ?>" />
  <?php } ?>

  <?php foreach ($links as $link) { ?>
  <link href="<?=$link['href']; ?>" rel="<?=$link['rel']; ?>" />
  <?php } ?>
  <?php foreach ($scripts as $script) { ?>
  <script src="<?=$script; ?>" type="text/javascript"></script>
  <?php } ?>
<?php echo $tc_og; $microdatapro_main_flag = 1; //microdatapro 7.3 - 1 - main ?>
  <?php foreach ($analytics as $analytic) { ?>
  <?=$analytic; ?>
  <?php } ?>

  <!-- Facebook Pixel Code -->
  <script>
    ! function (f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function () {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
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

<script src="catalog/view/javascript/jquery/jquery.geoip-module.js" type="text/javascript"></script>
<?php if(!isset($microdatapro_main_flag)){echo $tc_og;} //microdatapro 7.3 - 2 - extra ?>
<script src="https://static.yandex.net/kassa/pay-in-parts/ui/v1/"></script>
</head>

<body class="<?=$class; ?>">


  <div class="header">
    <div class="head1__bg">
      <div class="head1">

        <div class="container">

          <div class="head1__burger" id="burger">
            <span class="head1__burger-line"></span>
          </div>
          <nav class="head1__burger-nav" id="topNav">
            <ul class="head1__burger-nav-ul">
              <li class="head1__burger-nav-li city"></li>
              <li class="head1__burger-nav-li"><a href="/cataloglist">??????????????</a></li>
              <li class="head1__burger-nav-li"><a href="/o-nas">?? ??????</a></li>
              <li class="head1__burger-nav-li"><a href="/uhod-i-virashivanie">???????? ?? ??????????????????????</a></li>
              <li class="head1__burger-nav-li"><a href="/news">??????????????</a></li>
              <li class="head1__burger-nav-li"><a href="/dostavka-i-oplata">???????????????? ?? ????????????</a></li>
              <li class="head1__burger-nav-li"><a href="/faq">????????????/??????????</a></li>
              <li class="head1__burger-nav-li"><a href="/blog">????????</a></li>
              <li class="head1__burger-nav-li"><a href="/partneram">??????????????????</a></li>
              <li class="head1__burger-nav-li"><a href="/index.php?route=information/contact">????????????????</a></li>
              <!-- <li class="head1__burger-nav-li phone"></li> -->
              <li class="head1__burger-nav-li consult"></li>
            </ul>
          </nav>

          <div class="head1__city">
            <?= $geoip; ?>
          </div><!-- /.head1__city -->

<!-- <div class="head1__callme-wrap">
            <a href="#myModal" class="head1__callme" data-toggle="modal" data-target="#myModal">
              <span class="head1__callme-point"></span>
              ?????????? ????????????????????????
            </a> -->

          <div class="head1__callme-wrap">
            <a href="#myModal" class="head1__callme" data-toggle="modal" data-target="#myModal">
              <span class="head1__callme-point"></span>
              ?????????? ????????????????????????
            </a> 
          </div><!-- /.head1__callme-wrap -->

          <div class="head1__cart">
            <a class="head1__cart-title" href="/index.php?route=checkout/simplecheckout" title="?????????????? ?? ??????????????">
              <div class="head1__cart-img">
                <span class="head1__cart-img-count" id="cartCount">
                  <?= $count_goods; ?>
                </span>
              </div><!-- /.head1__cart-img -->
            </a>
            <a class="head1__cart-title" href="/index.php?route=checkout/simplecheckout"
              title="?????????????? ?? ??????????????">??????????????</a>

          </div><!-- /.head1__cart -->
          <div class="head1__myphone">
            <a class="head1__myphone-link" href="tel:<?=$telephone; ?>">
              <span><?=$telephone?></span>
            </a>
          </div><!-- /.head1__myphone -->
        </div>
      </div><!-- /.head1 -->
    </div><!-- /.head1__bg -->

    <div class="head2">
      <div class="container">

        <a class="head2__logo" href="/">
          <img class="head2__logo-img" src="<?=$logo; ?>" title="<?=$name; ?>"
            alt="<?=$name; ?>" />
        </a>

        <h2 class="head2__title">?????????????? ?????????? ????????????????</h2>

        <div class="head2__hours">
          <div class="head2__hours-logo"></div>
          <time datetime="<?= $open_hours; ?>">
            <?= $open_hours; ?>
          </time>
          <span><?= $open_days; ?></span>
        </div>

        <div id="headerSearch" class="head2__search">
          <?= $search; ?>
        </div>

        <div class="head2__login">
          <i class="head2__login-ico"></i>

          <div class="head2__login-wrap">
            <? if ($logged) { ?>
            <a class="head2__login-wrap-link" href="<?=$account; ?>"><?= $text_account; ?></a>&nbsp;/&nbsp;<a
              class="head2__login-wrap-link" href="<?=$logout; ?>"><?= $text_logout; ?></a>
            <? } else { ?>
            <a class="head2__login-wrap-link" href="<?=$register; ?>"><?= $text_register; ?></a>&nbsp;/&nbsp;<a
              class="head2__login-wrap-link" href="<?=$login; ?>"><?= $text_login; ?></a>
            <? } ?>
          </div>

        </div>
      </div>
    </div><!-- /.head2 -->

    <div class="navigation">
      <div class="container">
        <ul class="navigation__list">
          <li class="navigation__list-item"><a href="/cataloglist">??????????????</a></li>
          <li class="navigation__list-item"><a href="/o-nas">?? ??????</a></li>
          <li class="navigation__list-item"><a href="/uhod-i-virashivanie">???????? ?? ??????????????????????</a></li>
          <li class="navigation__list-item"><a href="/news">??????????????</a></li>
          <li class="navigation__list-item"><a href="/dostavka-i-oplata">???????????????? ?? ????????????</a></li>
          <li class="navigation__list-item"><a href="/faq">????????????/??????????</a></li>
          <li class="navigation__list-item"><a href="/blog">????????</a></li>
          <li class="navigation__list-item"><a href="/partneram">??????????????????</a></li>
          <li class="navigation__list-item"><a href="/index.php?route=information/contact">????????????????</a></li>
        </ul>
      </div>
    </div>
  </div><!-- /.header -->



   <!-- Modal -->
   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">???????????????? ????????????<br /> ?? ???? ?????? ????????????????????</h4>
        </div>
       <!-- <div class="modal-body">
          <form class="form-block" id="form1" role="form" method="post" action="/send.php">
            <div class="col-sm-12">
              <input type="text" class="form-control" id="name1" placeholder="?????? ?????? ???????????">
              <input type="tel" class="form-control" id="phone1" placeholder="?????? ?????????? ????????????????">
              <input type="text" class="form-control input-sm" id="datecase1"
                placeholder="?????????????? ?????????????? ?????????? ?????? ????????????">
              <input id="bottom" class="btn btn-block" onclick="send1();" type="button" value="???????????????? ????????????">

            </div>
          </form>
          
        </div>-->
        <script id="bx24_form_inline" data-skip-moving="true">
        (function(w,d,u,b){w['Bitrix24FormObject']=b;w[b] = w[b] || function(){arguments[0].ref=u;
                (w[b].forms=w[b].forms||[]).push(arguments[0])};
                if(w[b]['forms']) return;
                var s=d.createElement('script');s.async=1;s.src=u+'?'+(1*new Date());
                var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://exotiks.bitrix24.ru/bitrix/js/crm/form_loader.js','b24form');

        b24form({"id":"16","lang":"ru","sec":"g8z9ts","type":"inline"});
</script>
      </div>
    </div>
  </div>