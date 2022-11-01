<nav role="navigation" class="nav nav-justified">
  <!-- Логотип и мобильное меню -->
  <div class="navbar-header">

    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
      <span class="sr-only">Меню</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <span class="visible-xs menufonts">КАТАЛОГ</span>
  </div>
  <!-- Навигационное меню -->
  <div id="navbarCollapse" class="collapse navbar-collapse">
    <?php if ($modules) { ?>
      <aside id="column-left" class="">
        <?php foreach ($modules as $module) { ?>

          <!-- Поместите этот тег div в место, где будет располагаться блок отзывов -->
          <div id="feedback_vk"></div>
          <?php echo $module; ?>
        <?php } ?>
      </aside>
    <?php } ?>
    <!-- AddThis Button BEGIN -->
    <div class="addthis_toolbox addthis_default_style ">
      <a class="addthis_button_vk"></a>
      <a class="addthis_button_mymailru"></a>
      <a class="addthis_button_odnoklassniki_ru"></a>
      <a class="addthis_button_facebook"></a>
      <a class="addthis_button_compact"></a>
      <a class="addthis_counter addthis_bubble_style"></a>
    </div>
    <script type="text/javascript">
      var addthis_config = {
        "data_track_clickback": true
      };
    </script>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=YOUR_ACCOUNT_ID!!"></script>
    <!-- AddThis Button END -->
  </div>
</nav>