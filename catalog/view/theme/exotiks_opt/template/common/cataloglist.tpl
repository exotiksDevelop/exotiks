<?= $header; ?>

<?= $content_top; ?>



<section class="ourservice">
  <div class="container">
    <hgroup class="ourservice__box-left">
      <h2 class="ourservice__box-left-title">
        <span class="ourservice__box-left-title-1">При заказе вы</span>
        <br><span class="ourservice__box-left-title-1">получите товар как</span>
        <br><span class="ourservice__box-left-title-2">на фотографиях</span>
      </h2>
      <h3 class="ourservice__box-left-subtitle">
        В наличии только здоровые и красивые растения выращенные в специализированных питомниках
      </h3>
    </hgroup><!-- /.ourservice__box-left -->

	<div id="ourservice_video_02" class="ourservice__video">
      <!-- <video autoplay muted preload="auto" class="ourservice__video-content"> -->
      <!--<iframe width="100%" height="100%" class="ourservice__video-content" src="https://www.youtube.com/embed/d6eYdVrh31g?autoplay=1&mute=1&showinfo=0&controls=0" frameborder="0" allowfullscreen>--><!-- ?autoplay=1&mute=1&showinfo=0&controls=0" frameborder="0" allowfullscreen -->
      <!--</iframe>-->
      <!-- <source src="./catalog/view/theme/magazin/stylesheet/images/video.mp4" type="video/mp4"> -->
      <!-- </video> -->
	    <script>
		var youtube_video_iframe_init = false;
		function _youtube_video_iframe_init() {
		  if (isInViewport($('#ourservice_video_02')[0]) && !youtube_video_iframe_init) {
			var video_iframe = document.createElement('iframe'); 
			video_iframe.setAttribute("class", "ourservice__video-content");
			video_iframe.width = '100%';
			video_iframe.height = '100%';
			video_iframe.src = 'https://www.youtube.com/embed/d6eYdVrh31g?autoplay=1&mute=1&showinfo=0&controls=0';
			video_iframe.setAttribute("allowfullscreen", "");
			video_iframe.setAttribute("frameborder", "0");
			var ourservice_video_02 = document.getElementById('ourservice_video_02'); 
			ourservice_video_02.append(video_iframe);
			youtube_video_iframe_init = true;
		  }
		}
		$(window).on('scroll', _youtube_video_iframe_init);
		$(document.body).on('touchmove', _youtube_video_iframe_init);
		</script>
    </div>

    <div class="ourservice__box-bottom">
      <div class="ourservice__box-bottom-item">
        <div class="ourservice__box-bottom-item-ico ico-1"></div>
        <div class="ourservice__box-bottom-item-text">Все растения перед отправкой мы упаковываем так, чтобы не
          рассыпалась земля и не пострадали ловушки и листья</div>
      </div>
      <div class="ourservice__box-bottom-item">
        <div class="ourservice__box-bottom-item-ico ico-2"></div>
        <div class="ourservice__box-bottom-item-text">Если вдруг вас не устроит качество товара, то вы можете отказаться
          от заказа</div>
      </div>
      <div class="ourservice__box-bottom-item">
        <div class="ourservice__box-bottom-item-ico ico-3"></div>
        <div class="ourservice__box-bottom-item-text">Хищные растения безопасны как для людей так и для животных</div>
      </div>
    </div><!-- /.ourservice__box-bottom -->

  </div>
</section><!-- /.ourservice -->



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



<section class="questions">
  <div class="container">
    <h2 class="questions__title">Часто задаваемые вопросы</h2>

    <hr class="questions__line">
    <details class="questions__item">
      <summary class="questions__quest"><span class="text">Они ядовитые?</span><span class="flag"></span></summary>
      <p class="questions__answer"> Все растения безвредные для людей и животных.
      </p>
    </details>

    <hr class="questions__line">
    <details class="questions__item">
      <summary class="questions__quest"><span class="text">Растения многолетние?</span><span class="flag"></span>
      </summary>
      <p class="questions__answer">Растения многолетние, а также хорошо размножаются.</p>
    </details>

    <hr class="questions__line">
    <details class="questions__item">
      <summary class="questions__quest"><span class="text">Хищные растения нужно кормить каждый день?</span><span
          class="flag"></span></summary>
      <p class="questions__answer">Плотоядные растения не нуждаются в специальном кормлении. Например Мухоловке хватает 3-4 мух в год, которых она поймает за лето.</p>
    </details>

    <hr class="questions__line">
    <details class="questions__item">
      <summary class="questions__quest"><span class="text">Сложно ухаживать за насекомоядными растениями?</span><span
          class="flag"></span></summary>
      <p class="questions__answer">На сайте представлены растения за которыми не сложно ухаживать. Самое главное для них полив мягкой водой и освещение.</p>
    </details>    

    <hr class="questions__line">
    <details class="questions__item">
      <summary class="questions__quest"><span class="text">А растения нормально доедет?</span><span class="flag"></span>
      </summary>
      <p class="questions__answer">Все растения перед отправкой мы упаковываем так, чтобы хорошо перенесли дорогу и погодные условия.</p>
    </details>

    <hr class="questions__line">
    <details class="questions__item">
      <summary class="questions__quest"><span class="text">Венерина Мухоловка больно кусается?</span><span
          class="flag"></span></summary>
      <p class="questions__answer">У Мухоловки очень мягкие ловушки, которые не могут сделать больно человеку.</p>
    </details>

    <hr class="questions__line">

  </div>
</section><!-- /.questions -->



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
<!-- components/fundraiser -->

<?= $footer; ?>