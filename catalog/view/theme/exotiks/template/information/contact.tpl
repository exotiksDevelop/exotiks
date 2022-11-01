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
            <strong><?php echo $telephone; ?> <br>(звонки/whatsapp/viber) <br>+7(499) 346-86-36</strong><br>
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

Склад и офис интернет-магазина:</strong> Бескудниковский бульвар, дом 6к4 (вход справа от 2 жилого подъезда)<br>
Метро: Верхние Лихоборы (450 м)
Тел.: 
 +7(999) 345-27-21
<br><strong>Режим работы интернет-магазина и склада:</strong> c 10:00 до 20:00 ежедневно
<details>
<summary> <font color="blue">КАК ПРОЙТИ</font> &#128072;</summary>
<p><img alt="" src="https://exotiks.ru/image/catalog/raznoe/vhod_exotica.jpg" style="width: 80%;" /></p>
</details>


                  <br><br><strong>Магазин:</strong> ТЦ "Мега Теплый Стан" МКАД, 41-й километр, с1 (этаж 1-й, слева после центрального входа со стороны МКАД)</strong>
<br><strong>Режим работы:</strong> ежедневно 10:00–22:00
<details>
<summary> <font color="blue">КАК ПРОЙТИ</font> &#128072;</summary>
<p><img alt="" src="https://exotiks.ru/image/catalog/raznoe/Exotica_vhod.jpg" style="width: 80%;" /></p>
</details>


<br><br><strong>Магазин:</strong> ТЦ "Мега Белая дача", Котельники, 1-й Покровский пр., 1 (находимся на мосту, которая между мегой, уровень 2-й)</strong>
<br><strong>Режим работы:</strong> с 10:00 до 23:00 ежедневно
<details>
<summary> <font color="blue">КАК ПРОЙТИ</font> &#128072;</summary>
<p><img alt="" src="https://exotiks.ru/image/catalog/raznoe/Mega_belya_dacha.jpg" style="width: 80%;" /></p>
</details>

<br><br><strong>Магазин:</strong> ТЦ "Мега Химки", г. Химки, микрорайон ИКЕА, к2)</strong>
<br><strong>Режим работы:</strong> ежедневно 10:00–23:00
<details>
<summary> <font color="blue">КАК ПРОЙТИ</font> &#128072;</summary>
<p><img alt="" src="https://exotiks.ru/image/catalog/raznoe/mega_khimki_exotica.jpg" style="width: 80%;" /></p>
</details>

<br><br><strong>Магазин:</strong> ТЦ "Авиапарк", г. Москва, Ходынский бульвар, 4, этаж 1 (от аквариума в сторону ОБИ)</strong>
<br><strong>Режим работы:</strong> пн-чт 10:00–22:00; пт,сб 10:00–23:00; вс 10:00–22:00
<details>
<summary> <font color="blue">КАК ПРОЙТИ</font> &#128072;</summary>
<p><img alt="" src="https://exotiks.ru/image/catalog/raznoe/aviapark_exotica.jpg" style="width: 80%;" /></p>
</details>

                  <strong><br><br>ИП Галина Л. М. <br>ИНН 024000325545 <br>ОГРИНП 316028000200402
            </address>
        </div>
      </div>
        <div class="row">
        <div class="col-md-3">
            <p class="labelcontact">Заказы обрабатываются:</p>
        </div>
        <div class="col-md-4">
            <strong></strong>c 09:00 до 21:00 ежедневно

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