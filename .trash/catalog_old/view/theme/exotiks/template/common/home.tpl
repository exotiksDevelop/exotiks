<?= $header; ?>

<section class="top-banner">
  <div class="container">
    <div class="top-banner__img-right"></div>
    <h1 class="top-banner__title">Магазин живых
      <br>
      подарков №1 в России</h1>
    <br>
    <h4 class="top-banner__subtitle">Хищные растения, суккуленты, геометрические
      флорариумы, семена и все для ухода</h4>
    <br>
    <a href="/cataloglist" class="top-banner__btn">Перейти в каталог</a>
  </div>
</section>
<!-- /.top_banner -->

<?= $exposition; ?>

<?= $content_slider; ?>

<?= $content_top; ?>
<!-- components/our_store -->

<?= $ourservice; ?>

<section class="delivery">
  <div class="container">
    <h2 class="delivery__title">Условия по доставке</h2>
    <h3 class="delivery__subtitle">Стоимость доставки не меняется при заказе от 1 до 4-х растения</h3>

    <div class="delivery__box">
      <span class="delivery__box-item">
        <span class="delivery__box-item-ico ico-1"></span>
        <span class="delivery__box-item-title">Доставка по Москве</span>
        <span class="delivery__box-item-text">
          Курьером - <span class="green">350</span><span class="red"> руб.</span>
          <br>
          Самовывоз - <span class="green">0</span><span class="red"> руб.</span>
          <br>
          За МКАД - <span class="green">450</span><span class="red"> руб.</span></span>
      </span>
      <span class="delivery__box-item">
        <span class="delivery__box-item-ico ico-2"></span>
        <span class="delivery__box-item-title">Доставка семян</span>
        <span class="delivery__box-item-text">
          По всей России - <span class="green">150</span><span class="red"> руб.</span>
        </span>
      </span>
      <span class="delivery__box-item">
        <span class="delivery__box-item-ico ico-3"></span>
        <span class="delivery__box-item-title">Доставка в регионы</span>
        <span class="delivery__box-item-text">
          До пункта выдачи - <span class="green">350</span><span class="red"> руб.</span>
          <br>
          Курьерская - <span class="green">450</span><span class="red"> руб.</span></span>
      </span>
    </div><!-- /.delivery__box -->

    <a href="/dostavka-i-oplata" class="delivery__btn">Подробнее</a>
  </div><!-- /.container -->
</section>
<!-- /.delivery -->

<section class="feedback">
  <div class="container">
    <h2 class="feedback__title">Клиенты о нас<span>с 2012 года мы получили более трехсот отзывов от наших клиентов</span></h2>
    <div class="feedback__box">
      <div class="feedback__box-inner">
        <div id="feedback_vk"></div>
      </div>
    </div>
  </div>
  <script src="https://feedbackcloud.kupiapp.ru/widget/widget.js" type="text/javascript"></script>
  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", feedback_vk.init({
      id: 'feedback_vk',
      gid: 40950886,
      count: 5
    }));
  </script>
</section>

<?= $questions; ?>

<section class="request">
  <div class="container">
    <div class="request__img-right"></div>
    <h3 class="request__title">Остались вопросы?</h3>
    <br>
    <h4 class="request__subtitle">Оставьте заявку или позвоните нам. Ответим на все вопросы, проконсультируем по уходу
    </h4>
    <br>
    <form class="request__form form-block" id="form" role="form">
      <input type="text" class="request__form-input" name="name" id="name" placeholder="Ваше имя" required>
      <input type="tel" class="request__form-input" name="phone" id="phone" placeholder="Ваш телефон" required>
      <br>
      <input id="bottom" class="request__form-btn" onclick="send();" type="button" value="Оставить заявку">
      <p class="request__form-btn-label">Нажимая кнопку “Оставить заявку” вы даете согласие на обработку персональных данных</p>
    </form>
  </div>
  <script>
    $(function() {
      // Марат-скрипт
      function send() {
        //Получаем параметры
        if ($('#name').val() == '') {
          $('input#name').css({
            'border': '1px solid red',
            'padding-left': '0px'
          });
        };
        if ($('#phone').val() == '') {
          $('input#phone').css('border', '1px solid red');
        };
        if ($('#name').val() != '' && $('#phone').val() != '') {
          var name = $('#name').val();
          var phone = $('#phone').val();
          var datecase = $('#datecase').val();
          // Отсылаем паметры
          $.ajax({
            type: "POST",
            url: "send.php",
            data: "name=" + name + "&phone=" + phone + "&datecase=" + datecase,
            // Выводим то что вернул PHP
            success: function(html) {
              //предварительно очищаем нужный элемент страницы
              $("#result").empty();
              //и выводим ответ php скрипта
              $("#result").append(html);
              $('input#name').css('border', '1px solid #ccc');
              $('input#phone').css('border', '1px solid #ccc');
              $('#result').css('display', 'block');
              $('#form')[0].reset();
              $('#myModal').modal('hide');
              alert("Спасибо! Ваше сообщение отправлено.");
            }
          });
        };
      }
    });
  </script>
</section>
<!-- /.request -->

<?= $content_bottom; ?>
<!-- components/products_hits -->
<!-- components/description -->
<!-- components/fundraiser -->

<?= $footer; ?>