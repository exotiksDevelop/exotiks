<?php echo $header; ?>
<div class="container contact">

    <!-- <div class="col-md-9 col-sm-8 col-xs-12"> -->
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
      <br>
      <a href="#myModal" class="button ml-4 mt-2 callme contact" data-toggle="modal" data-target="#myModal">Обратная связь</a>
      <div class="clear"></div>
      <div class="row clear" style="margin-top:40px;">
        <div class="col-md-3">
            <p class="labelcontact">Сайт: </p>
        </div>
        <div class="col-md-4">
            <strong>exotiks.ru</strong>
        </div>
        <div class="col-md-3">
            
        </div>
            
      </div>
      <div class="row">
        <div class="col-md-3">
            <p class="labelcontact">Телефоны:</p>
        </div>
        <div class="col-md-4">
            <strong><?php echo $telephone; ?> <br>+7(999) 345-27-21</strong><br>
            (основной /  whatsapp/viber) 
        </div>
        <div class="col-md-4">
            <strong><?php echo $fax; ?></strong><br />
            
        </div>
            
      </div>
      <div class="row">
        <div class="col-md-3">
            <p class="labelcontact">Почта:</p>
        </div>
        <div class="col-md-7">
            <address>
                  <strong>info@exotiks.ru</strong>
            </address>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
            <p class="labelcontact">Адрес самовывоза:</p>
        </div>
        <div class="col-md-7">
            <address>
                  <strong><?php echo $address; ?></strong>

<br><br><br><strong>г. Москва<br><br>

Склад:</strong> улица Руставели, дом 6к6 (вход с улицы, серая дверь, офис 3")<br>
Метро: Бутырская (10 мин), Дмитровская (15 мин)
Перед посещением звоните по тел.: 
 +7(999) 345-27-21
<br><strong>Режим работы склада:</strong> c 10:00 до 20:00 

                  <br><br><strong>Магазин:</strong> ТЦ "Мега Теплый Стан" МКАД, 41-й километр, с1 (этаж 1-й, слева после центрального входа со стороны МКАД)</strong>
<br><strong>Режим работы магазина в Меге:</strong> с 10:00 до 23:00

                  <strong><br><br>ИП Галина Л. М. <br>ИНН 024000325545 <br>ОГРИНП 316028000200402
            </address>
        </div>
      </div>
        <div class="row">
        <div class="col-md-3">
            <p class="labelcontact">Заказы обрабатываются:</p>
        </div>
        <div class="col-md-4">
            <strong></strong>с 10:00 до 20:00 Пн-Сб<br>Воскресенье - выходной. 

        </div>
        <div class="col-md-3">
            <strong></strong>
        </div>
  
      </div>

      <br>
      <br>
      <br>
      <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A2818174c266602623f2d5f4b2f6ddec7fe92958bf56724846be860785fdd0761&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
    <!-- </div> -->
  </div>
 <?php echo $footer; ?>