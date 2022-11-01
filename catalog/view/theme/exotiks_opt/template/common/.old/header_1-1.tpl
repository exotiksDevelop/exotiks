<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="yandex-verification" content="249209024a0d9ca4" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/jquery/owl-carousel/owl.carousel.css" rel="stylesheet">
<link href="catalog/view/theme/magazin/stylesheet/stylesheet.css" rel="stylesheet">
<link href="catalog/view/theme/magazin/stylesheet/fonts.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
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
<div id="container">
    <div class="header">
    		<div class="head1">
    			<div class="head1_c">
    				<div class="mycity">
    					Ваш город: <span class="city">Уфа</span>
    					<span class="case_city">выбрать другой город</span>
    				</div>
    			</div>
    			<div class="myphone">
                	<a class="phone" href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a>
    				<a href="" class="callme">Нужна консультация</a>
    			</div>
    		</div>
    		<div class="head2">
    			<a href="" class="logo">
    				<span>exzotika<font style="color: #000">.</font><font style="color: #569f25">ru</font></span>
    				<p>продажа хищных растений 
                     и экзотических животных</p>
    			</a>
    			<div class="vremya_raboty">
    				<h2>Время <font style="color: #cb536a">работы</font></h2>
    				<span><?php echo $open; ?></span>
    			</div>
    			<div class="korzina">
    				<h2><a href="/index.php?route=checkout/cart" title="Перейти в корзину">Корзина</a></h2>
                    <span><?php echo $cart; ?></span>
    				
    			</div>
    			<div class="vhod">
                    <?php if ($logged) { ?>
                        <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><br/>                       
                        <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $register; ?>"><?php echo $text_register; ?></a><br/> 
                        <a href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
                        <?php } ?>
    			</div>
    			<div class="socseti">
    				<a href="" class="vk"></a>
    				<a href="" class="instagramm"></a>
    			</div>
    		</div>
    		<nav>
    			<ul class="gorizontal_ul">
				<li class="gorizontal_li"><a href="">Каталог</a>
                      
                      <?php print_r($category); ?>
                      <?php /* var_dump($category);die; */?>
                </li>
				<li class="gorizontal_li"><a href="">О нас</a></li>
				<li class="gorizontal_li"><a href="">Уход и выращивание</a></li>
				<li class="gorizontal_li"><a href="">Доставка и оплата</a></li>
				<li class="gorizontal_li"><a href="">Вопрос/ответ</a></li>
				<li class="gorizontal_li"><a href="">Отзывы</a></li>
				<li class="gorizontal_li"><a href="">Партнерам</a></li>
				<li class="gorizontal_li"><a href="">Контакты</a></li>
			</ul>
    		</nav>
    	</div>