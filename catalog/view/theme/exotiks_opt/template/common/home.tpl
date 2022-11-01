<?= $header; ?>

<section class="top-banner">
  <div class="container">
    <!--<div class="top-banner__img-right"></div>-->
    <div id="top_banner_img_right" class="lazy-bg-img-load"></div>
	
    <h1 class="top-banner__title">Хищные растения
      <br>
      и суккуленты оптом</h1>
    <br>
    <h4 class="top-banner__subtitle">Доставка по России и СНГ.</h4>
    <br>
    <a href="/cataloglist" class="top-banner__btn">Перейти в каталог</a>
  </div>
</section>
<!-- /.top_banner -->
<!--ВК277S--><!--Код ВК-->

<section class="exposition">
  <div class="container">
    <div class="exposition__box">
      <h5 class="exposition__box-title">Оплата при получении</h5>
      <h5 class="exposition__box-title">Даем инструкцию по уходу</h5>
      <h5 class="exposition__box-title">Бесплатная упаковка</h5>
      <h5 class="exposition__box-title">Большой выбор растений</h5>
      <h5 class="exposition__box-title">Скидки постоянным клиентам</h5>
    </div>
  </div>
</section>
<!-- /.exposition -->

<?= $content_slider; ?>

<?= $content_top; ?>
<!-- components/our_store -->



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

    <div id="ourservice_video_01" class="ourservice__video">
      <!-- <video autoplay muted preload="auto" class="ourservice__video-content"> -->
      <!--<iframe width="100%" height="100%" class="ourservice__video-content" src="https://www.youtube.com/embed/PTYtXMDmvOs?autoplay=1&mute=1&showinfo=0&controls=0" frameborder="0" allowfullscreen>--><!-- ?autoplay=1&mute=1&showinfo=0&controls=0" frameborder="0" allowfullscreen -->
      <!--</iframe>-->
      <!-- <source src="./catalog/view/theme/magazin/stylesheet/images/video.mp4" type="video/mp4"> -->
      <!-- </video> -->
	    <script>
		var youtube_video_iframe_init = false;
		function _youtube_video_iframe_init() {
		  if (isInViewport($('#ourservice_video_01')[0]) && !youtube_video_iframe_init) {
			var video_iframe = document.createElement('iframe'); 
			video_iframe.setAttribute("class", "ourservice__video-content");
			video_iframe.width = '100%';
			video_iframe.height = '100%';
			video_iframe.src = 'https://www.youtube.com/embed/PTYtXMDmvOs?autoplay=1&mute=1&showinfo=0&controls=0';
			video_iframe.setAttribute("allowfullscreen", "");
			video_iframe.setAttribute("frameborder", "0");
			var ourservice_video_01 = document.getElementById('ourservice_video_01'); 
			ourservice_video_01.append(video_iframe);
			youtube_video_iframe_init = true;
		  }
		}
		$(window).on('scroll', _youtube_video_iframe_init);
		$(document.body).on('touchmove', _youtube_video_iframe_init);
		</script>
    </div>

    <div id="ourservice_box_bottom" class="ourservice__box-bottom">
      <div class="ourservice__box-bottom-item">
        <div class="ourservice__box-bottom-item-ico ico-1- lazy-bg-img-load"></div>
        <div class="ourservice__box-bottom-item-text">Все растения перед отправкой мы упаковываем так, чтобы не
          рассыпалась земля и не пострадали ловушки и листья</div>
      </div>
      <div class="ourservice__box-bottom-item">
        <div class="ourservice__box-bottom-item-ico ico-2- lazy-bg-img-load"></div>
        <div class="ourservice__box-bottom-item-text">Если вдруг вас не устроит качество товара, то вы можете отказаться
          от заказа</div>
      </div>
      <div class="ourservice__box-bottom-item">
        <div class="ourservice__box-bottom-item-ico ico-3- lazy-bg-img-load"></div>
        <div class="ourservice__box-bottom-item-text">Хищные растения безопасны как для людей так и для животных</div>
      </div>
    </div><!-- /.ourservice__box-bottom -->

  </div>
</section><!-- /.ourservice -->



<section id="delivery_section" class="delivery- ">
  <div class="container">
    <h2 class="delivery__title">Условия по доставке</h2>
    <h3 class="delivery__subtitle">Стоимость доставки не меняется при заказе от 1 до 4-х растения</h3>

    <div id="delivery_box" class="delivery__box">
      <span class="delivery__box-item">
        <span class="delivery__box-item-ico ico-1- lazy-bg-img-load"></span>
        <span class="delivery__box-item-title">Доставка по Москве</span>
        <span class="delivery__box-item-text">
          Курьером - <span class="green">400</span><span class="red"> руб.</span>
          <br>
          Самовывоз - <span class="green">0</span><span class="red"> руб.</span>
          <br>
          За МКАД - <span class="green">от 700</span><span class="red"> руб.</span></span>
      </span>
      <span class="delivery__box-item">
        <span class="delivery__box-item-ico ico-2- lazy-bg-img-load"></span>
        <span class="delivery__box-item-title">Доставка семян</span>
        <span class="delivery__box-item-text">
          По всей России - <span class="green">200</span><span class="red"> руб.</span>
        </span>
      </span>
      <span class="delivery__box-item">
        <span class="delivery__box-item-ico ico-3- lazy-bg-img-load"></span>
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

<section id="feedback_section" class="feedback">
  <div class="container">
    <h2 class="feedback__title">Клиенты о нас<span>с 2012 года мы получили более трехсот отзывов от наших клиентов</span></h2>
    <div class="feedback__box">
      <div class="feedback__box-inner">
        <div id="feedback_vk"></div>
      </div>
    </div>
  </div>
  <!--<script src="https://feedbackcloud.kupiapp.ru/widget/widget.js" type="text/javascript"></script>-->
  <script>
    var feedback_vk_init = false;
    function _feedback_vk() {
	  if (isInViewport($('#feedback_section')[0]) && !feedback_vk_init) {
		var _script = document.createElement('script'); 
		_script.type = 'text/javascript'; 
		_script.charset = 'utf-8';
		_script.async = '';
		_script.src = 'https://feedbackcloud.kupiapp.ru/widget/widget.js';
		_script.onload = function() {
			feedback_vk.init({
			  id: 'feedback_vk',
			  gid: 40950886,
			  count: 5
			});
		};
		var feedback_section = document.getElementById('feedback_section'); 
		feedback_section.append(_script);
		feedback_vk_init = true;
	  }
    }
    $(window).on('scroll', _feedback_vk);
    $(document.body).on('touchmove', _feedback_vk);
  
    /*document.addEventListener("DOMContentLoaded", feedback_vk.init({
      id: 'feedback_vk',
      gid: 40950886,
      count: 5
    }));*/
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
    <!--<div class="request__img-right"></div>-->
	<div id="request_img_right" class="lazy-bg-img-load"></div>
    <h3 class="request__title">Остались вопросы?</h3>
    <br>
    <h4 class="request__subtitle">Оставьте заявку или позвоните нам. Ответим на все вопросы, проконсультируем по уходу
    </h4>
    <br>
    <form class="request__form form-block" id="form" method="post" action="/send.php">
      <input type="text" class="request__form-input" name="name" id="name" placeholder="Ваше имя" required value="">
      <input type="tel" class="request__form-input" name="phone" id="phone" placeholder="Ваш телефон" required value="">
      <br>
      <input id="bottom" class="request__form-btn" onclick="send();" type="button" value="Оставить заявку">
      <p class="request__form-btn-label">Нажимая кнопку “Оставить заявку” вы даете согласие на обработку персональных данных</p>
    </form>
  </div>  
</section>
<!-- /.request -->

<?= $content_bottom; ?>
<!-- components/products_hits -->
<!-- components/description -->
<!-- components/fundraiser -->

<!--<script async type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A2818174c266602623f2d5f4b2f6ddec7fe92958bf56724846be860785fdd0761&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>-->
<div id="yandex_maps" style="clear:both;">&nbsp;</div>
<script>
	var yandex_maps_init = false;
    function _yandex_maps_init() {
	  if (isInViewport($('#yandex_maps')[0]) && !yandex_maps_init) {
		var ym_script = document.createElement('script'); 
		ym_script.type = 'text/javascript'; 
		ym_script.charset = 'utf-8';
		ym_script.async = '';
		ym_script.src = 'https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A2818174c266602623f2d5f4b2f6ddec7fe92958bf56724846be860785fdd0761&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true';
		var yandex_maps = document.getElementById('yandex_maps'); 
		yandex_maps.append(ym_script);
		yandex_maps_init = true;
	  }
    }
    $(window).on('scroll', _yandex_maps_init);
    $(document.body).on('touchmove', _yandex_maps_init);
</script>

<?= $footer; ?>