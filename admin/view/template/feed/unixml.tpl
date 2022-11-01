<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

<style>
.checkbox_exp {position: absolute;z-index: -1;opacity: 0;margin: 10px 0 0 20px;}
.checkbox_exp + label {position: relative;padding: 0 0 0 60px;cursor: pointer;}
.checkbox_exp + label:before {content: '';position: absolute;top: -4px;left: 0;width: 50px;height: 26px;border-radius: 13px;background: #CDD1DA;box-shadow: inset 0 2px 3px rgba(0,0,0,.2);transition: .2s;}
.checkbox_exp + label:after {content: '';position: absolute;top: -2px;left: 2px;width: 22px;height: 22px;border-radius: 10px;background: #FFF;box-shadow: 0 2px 5px rgba(0,0,0,.3);transition: .2s;}
.checkbox_exp:checked + label:before {background:#9FD468;}
.checkbox_exp:checked + label:after {left: 26px;}
.checkbox_exp:focus + label:before {box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);}
.col-sm-212{display:inline-block;float:none;margin-right:6px;margin-bottom:20px;}
.bottom_info{position:fixed;bottom:0;right:0px;max-width:500px;z-index:20;}
.bottom_info .info_block{padding:15px;border:2px solid #bce8f1;background:#EFF8FC;display:none;}
.bottom_info .info_block h3{font-size:18px;}
.bottom_info .info_oc{border:1px solid #bce8f1;border-bottom:0;border-radius:3px 3px 0 0:font-size:12px;background:#bce8f1;color:#31708f;position:absolute;top:-30px;height:30px;right:0px;line-height:30px;text-align:center;cursor:pointer;width:230px;}
.bottom_info .info_oc:hover{background:#31708f;color:#fff;}
.sb_item{position:relative;}
.itemi{top: -1px;left:-1px;position: absolute;height: 20px;display: block;text-align: center;color: #999;width: 20px;line-height:20px;background:#ededed;}
.form-group1{margin-left:-15px;margin-right:-15px;padding-top: 15px;padding-bottom:15px;}
.pull-right.fixed{position:fixed;top:5px;padding:15px;background:#ecf3e6;right:5px;z-index:999;box-shadow: 0 0 10px #ccc}
.pull-right .name_set, .pull-right.fixed .name_set.hided{display:none;}
.pull-right.fixed .name_set{display:inline-block;padding-right:11px;}
.scrollbox input{vertical-align:top;margin-top:2px;}
.scrollbox div{padding:8px 10px;border-bottom:1px solid #ddd;color: rgb(102, 102, 102)}
.scrollbox div.even{background:#f9f9f9;}
.well.well-sm >div{padding:8px 10px;color: rgb(102, 102, 102)}
.well.well-sm >div+div{border-top:1px solid #ddd;}
.panel-heading .success{float:right;font-weight:bold;color:green;margin-right:15px;}
#select_country>span{cursor:pointer;padding:0px 3px;margin-left:10px;display:inline-block;vertical-align:top;background:#eee;}
#select_country>span:hover{background:#1e91cf;color:#fff;}
.active_feeds_container{color:#999;font-size:14px;cursor:pointer;}
.imp-success{background: #ecf3e6;padding: 0px 20px;text-align: center;border: 1px solid #eee;border-radius: 3px;color: green;border-radius: 3px;display: inline-block;vertical-align: top;height: 35px;font-size: 11px;}
label div small{font-weight:400;}
.market_link{border-bottom:1px dashed;}
</style>
<script>
$(document).ready(function(){
  $('.active_feeds_container').on('click', function(){
    $('a[href="#tab-info"]').click();
  });
  $('.info_oc').on('click', function(){
    $(this).parent().find('.info_block').slideToggle('200');
    $(this).parent().find('.info_oc').toggle();
  });
  $('.tab-content .tab-pane').each(function(feedi,elem) {
    feedi++;
    $(this).find('.form-group').each(function(itemi,elem) {
      itemi++;
      $(this).addClass('sb_item');
      $(this).addClass('sb'+'_'+feedi+'_'+itemi);
      $(this).prepend('<div class="itemi">' + itemi + '</div>');
    });
  });
  $('.info_block a[href^="#sb"]').on('click', function(e){
    e.preventDefault();
    $('html, body').animate({scrollTop: $("." + $(this).attr('href').replace('#','')).offset().top}, 700);
  });
  $('.info_block a[href^="#sb"]').each(function(elem) {
    $(this).attr('title', 'Кликните что бы прокрутить к блоку настроек');
  });
});

$(document).ready(function(){
  $(window).bind('scroll', function() {
    if ($(window).scrollTop() > 42) {
      $('.container-fluid .pull-right').addClass('fixed');
    }else {
      $('.container-fluid .pull-right').removeClass('fixed');
    }
 });
});

</script>

  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <div class="name_set">Настройки для <b></b></div>
        <button type="submit" form="form-unixml" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title_module; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?> <span class="active_feeds_container">(Показано <span id="count_active_feeds"><?php echo $count_active_feeds; ?></span> из <?php echo $count_feeds; ?>)</span></h3>
        <div style="float:right;" id="select_country">Показать выгрузки для <span id="">Всех</span><span id="ua">UA</span><span id="ru">RU</span><span id="kz">KZ</span></div>
        <script>
          $('#select_country>span').on('click', function(){
            $.ajax({
              url: 'index.php?route=<?php echo $path; ?>/select_country&token=<?php echo $token; ?>&country=' + $(this).attr('id'),
              method: 'post',
              dataType: 'json',
              success: function(json) {
                $('#tab-info input[type="checkbox"]').each(function() {
                  $(this).prop('checked', false);
                });
                setTimeout(function(){
                  $.each(json, function (index, value) {
                    $('#tab-info input[value="' + value + '"]').prop('checked', true).change();
                  });
                },150);
              }
            });
          });
        </script>

        <?php if($success){ ?>
          <div class="success"><?php echo $success; ?></div>
        <?php } ?>
      </div>
      <div class="panel-body">

      <?php if($miv < 20000){  ?>
        <div class="alert alert-warning" role="alert" style="display:block;"><b>Внимание! Ваше значение max_input_vars <?php echo $miv; ?></b>. Сохранение настроек модуля может работать некорректно. Рекомендуется в конец файла <b>.htaccess</b> (в корне сайта) добавить строку <b>php_value max_input_vars 20000</b> либо поменять значение max_input_vars другим способом. Если не разберетесь пишите в поддержку info@microdata.pro</div>
      <?php } ?>

       <?php if(!$a){ ?>
         <div>
           <?php echo $message; ?>
         </div>
       <?php }else{ ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-unixml" class="form-horizontal">
          <input type="hidden" name="unixml_status" value="1">

          <ul class="nav nav-tabs">
            <?php foreach($feeds as $key => $feed){ ?>
              <li <?php if(!in_array($feed, $unixml_hide)){ ?>style="display:none;"<?php } ?> <?php if(!$key){ ?>class="active"<?php } ?>><a href="#tab-<?php echo $feed; ?>" data-toggle="tab"><?php echo $feed; ?></a></li>
            <?php } ?>
            <li><a href="#tab-info" data-toggle="tab"><i class="fa fa-sliders" aria-hidden="true"></i> Настройки / <i class="fa fa-info-circle" aria-hidden="true"></i> Информация</a></li>
          </ul>

          <div class="tab-content">
            <?php foreach($feeds as $key => $feed){ ?>
            <!-- ++tab-<?php echo $feed; ?> -->
            <div class="tab-pane <?php if(!$key){ ?>active<?php } ?>" id="tab-<?php echo $feed; ?>">


              <div class="exp-imp">
                <div class="row">
                  <div class="col-sm-6">
                    <h3 style="line-height:35px;">Настройка выгрузки в <strong><?php echo $feed; ?></strong></h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a class="btn btn-info ixport-import" href="<?php echo $export_setting; ?>&feed=<?php echo $feed; ?>" data-toggle="tooltip" title="Экспортировать/сохранить настройки для <?php echo $feed; ?>. Внимание! Перед тем как сделать экспорт сохраните текущие настройки."><i class="fa fa-upload" aria-hidden="true"></i> Экспорт настроек</a>
                    <span class="btn btn-info ixport-import upload_file" data-feed="<?php echo $feed; ?>" data-toggle="tooltip" title="Импортировать/загрузить настройки для <?php echo $feed; ?>. Внимание! При импортировании все настройки перезаписываются."><i class="fa fa-download" aria-hidden="true"></i> Импорт настроек</span>
                  </div>
                </div>
              </div>
              <hr>


              <?php if(${$feed . '_info'}){ ?>
                <div class="<?php echo $feed; ?>_info bottom_info">
                  <div class="info_oc">Полезные подсказки для <strong><?php echo $feed; ?></strong> <i class="fa fa-info-circle" aria-hidden="true"></i></div>
                  <div class="info_oc" style="display:none;">Закрыть подсказки для <?php echo $feed; ?> <i class="fa fa-times" aria-hidden="true"></i></div>
                  <div class="info_block">
                  <h3>Рекомендации и подсказки для выгрузки в <?php echo $feed; ?></h3>
                  <?php echo ${$feed . '_info'}; ?>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_status" title="Статус при который будет включена или выключена выгрузка <?php echo $feed; ?>" data-toggle="tooltip"><span><?php echo $entry_status; ?></span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_status" name="unixml_<?php echo $feed; ?>_status" class="form-control">
                    <?php if (${'unixml_' . $feed . '_status'}) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_name" title="Название магазина в выгрузке. В некоторых может и не нужно, но там где требуют надо заполнить" data-toggle="tooltip"><span><?php echo $entry_store_name; ?></span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_name" type="text" name="unixml_<?php echo $feed; ?>_name" value="<?php echo ${'unixml_' . $feed . '_name'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_language" title="В выгрузку будут попадать данные на выбранном языке" data-toggle="tooltip"><span><?php echo $entry_language; ?></span></label>
                <div class="col-sm-10">
                  <select name="unixml_<?php echo $feed; ?>_language" id="unixml_<?php echo $feed; ?>_language" class="form-control">
                    <?php foreach ($languages as $language) { ?>
                      <?php if ($language['language_id'] == ${'unixml_' . $feed . '_language'}) { ?>
                        <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_currency" title="Цены в выгрузке будут пересчитаны согласно курса выбранной валюты" data-toggle="tooltip"><span>Валюта выгрузки</span></label>
                <div class="col-sm-10">
                  <select name="unixml_<?php echo $feed; ?>_currency" id="unixml_<?php echo $feed; ?>_currency" class="form-control">
                    <?php foreach ($currencies as $currency) { ?>
                      <?php if ($currency['currency_id'] == ${'unixml_' . $feed . '_currency'}) { ?>
                        <option value="<?php echo $currency['currency_id']; ?>" selected="selected"><?php echo $currency['title']; ?> (<?php echo $currency['code']; ?>) - курс <?php echo $currency['value']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $currency['currency_id']; ?>"><?php echo $currency['title']; ?> (<?php echo $currency['code']; ?>) - курс <?php echo $currency['value']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_field_id" title="Во многих маркетплейсах есть связующее звено - идентификатор. Указать что будет идентификатором можно в этой настройке. Внимание! Идентификатор должен быть только уникальным числом. Если стоит умножение товара на опции будет приставка опции для соблюдения уникальности идентификатора" data-toggle="tooltip"><span>Из какого поля берем id товара</span><br><br><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#uxmShort">Какие поля доступны?</button></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_field_id" type="text" name="unixml_<?php echo $feed; ?>_field_id" value="<?php echo ${'unixml_' . $feed . '_field_id'}; ?>" class="form-control" placeholder="p.product_id">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_markup" title="Можно ставить либо процент '10%', либо фиксированную наценку '200'" data-toggle="tooltip"><span>Наценка на товар</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_markup" placeholder="10% - это 10% на цену или же 200 - это 200 единиц валюты" type="text" name="unixml_<?php echo $feed; ?>_markup" value="<?php echo ${'unixml_' . $feed . '_markup'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_custom_xml" title="Кастомный код прописывается как код php где $product['price'] - финальная цена товара которая идет в выгрузку а $product['special'] - финальная акицонная цена товара. $product['name'] - имя товара." data-toggle="tooltip"><span>Кастомный код. Синтаксис php!<br><small style="font-weight:400;">Пожалуйста, будьте предельно внимательные т.к. неправильно написаный код может наружить работу модуля и магазина!</small></span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon" id="input-_custom_xml1">&lt;?php</span>
                    <textarea style="min-height:150px;" id="unixml_<?php echo $feed; ?>_custom_xml" placeholder="if($product['price'] < 1000){$product['price'] *= 1.1;}else{$product['price'] *= 1.05;}" type="text" name="unixml_<?php echo $feed; ?>_custom_xml" class="form-control"><?php echo ${'unixml_' . $feed . '_custom_xml'}; ?></textarea>
                    <span class="input-group-addon" id="input-_custom_xml2">?&gt;</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_utm" title="Можно ставить любую приставку к ссылке товара, например UTM метку. Можно использовать любые данные с массива товара, например {name},{product_id},{model} или любое другое поле которое укажете в пункте 10" data-toggle="tooltip"><span>Приставка к ссылке товара (UTM и т.п.)</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_utm" placeholder="?utm_source=<?php echo $feed; ?>&utm_medium=cpc&utm_campaign=utm_metki" type="text" name="unixml_<?php echo $feed; ?>_utm" value="<?php echo ${'unixml_' . $feed . '_utm'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_delivery_cost" title="Стоимость надо указывать целым числом без валюты" data-toggle="tooltip"><span>Стоимость доставки</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_delivery_cost" placeholder="Стоимость надо указывать целым числом без валюты" type="text" name="unixml_<?php echo $feed; ?>_delivery_cost" value="<?php echo ${'unixml_' . $feed . '_delivery_cost'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_delivery_time" title="Можно указать например 2 - это будет 2 дня или же 1-2 это будет день-два" data-toggle="tooltip"><span>Сроки доставки в днях</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_delivery_time" placeholder="Можно указать например 2 - это будет 2 дня или же 1-2 это будет день-два" type="text" name="unixml_<?php echo $feed; ?>_delivery_time" value="<?php echo ${'unixml_' . $feed . '_delivery_time'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_delivery_jump" title="Время после которого доставка будет считаться со следующего дня" data-toggle="tooltip"><span>Час перескока</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_delivery_jump" placeholder="Время после которого доставка будет считаться со следующего дня" type="text" name="unixml_<?php echo $feed; ?>_delivery_jump" value="<?php echo ${'unixml_' . $feed . '_delivery_jump'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_fields" title="В некоторых выгрузках, например google нужны данные mpn. Так как UniXML это модуль с оптимизированным кодом ничего лишнего с базы не забирается. Что бы все же забирать дополнительные данные их необходимо прописать. Через запятую." data-toggle="tooltip"><span>Дополнительные поля базы для выгрузки</span><br><br><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#uxmShort">Какие поля доступны?</button></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_fields" type="text" name="unixml_<?php echo $feed; ?>_fields" value="<?php echo ${'unixml_' . $feed . '_fields'}; ?>" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_field_price" title="Во многих магазинах цена для выгрузки находится в другом поле. Что бы цена шла с нужного поля, просто укажите его" data-toggle="tooltip"><span>Из какого поля цена</span><br><br><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#uxmShort">Какие поля доступны?</button></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_field_price" type="text" name="unixml_<?php echo $feed; ?>_field_price" value="<?php echo ${'unixml_' . $feed . '_field_price'}; ?>" class="form-control" placeholder="p.price">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_name" title="Чем больше число товаров за раз - тем быстрее сгенерируется xml. Для такого требуется больше оперативной памяти. Чем число меньше тем меньше оперативной памяти потребуется, но выгрузка будет дольше генерировать. Рекомендуется около 10 000 товаров за раз, но если будут ошибки о нехватки оперативной памяти уменьшайте до исчезновения ошибки" data-toggle="tooltip"><span>Количество за раз</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_step" type="text" name="unixml_<?php echo $feed; ?>_step" value="<?php echo ${'unixml_' . $feed . '_step'}?${'unixml_' . $feed . '_step'}:10000; ?>" class="form-control">
                </div>
              </div>

              <?php if($seopro){ ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_seopro" title="Если Вы используете SEOPRO там есть главная категория. Если она задана как конечная и во всех товарах проставлена главная категория то отмечайте привязываться. Если не используется SEOPRO или же в товарах не проставлена главная категория лучше не включайте. Выгрузка постарается забрать конечную категорию товара, если он привязан к многим." data-toggle="tooltip"><span>Привязка к главной категории</span></label>
                  <div class="col-sm-10">
                    <select id="unixml_<?php echo $feed; ?>_seopro" name="unixml_<?php echo $feed; ?>_seopro" class="form-control">
                      <?php if (${'unixml_' . $feed . '_seopro'}) { ?>
                        <option value="1" selected="selected">Привязываемся к главной категории (главная проставлена во всех товарах и как конечная по цепочке)</option>
                        <option value="0">Не привязываемся к главной категории. Пусть UniXML сам найдет конечную категорию товара</option>
                      <?php } else { ?>
                        <option value="1">Привязываемся к главной категории (главная проставлена во всех товарах и как конечная по цепочке)</option>
                        <option value="0" selected="selected">Не привязываемся к главной категории. Пусть UniXML сам найдет конечную категорию товара</option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_log" title="Что бы понимать все ли ок, все ли работает и как быстро, можно ввести название файла - лога куда будут записываться информация о выгрузках" data-toggle="tooltip"><span>Логировать выгрузку</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <span class="input-group-addon" id="input-easyphoto_rename_direct"><?php echo HTTPS_CATALOG; ?>system/storage/logs/</span>
                    <input id="unixml_<?php echo $feed; ?>_log" placeholder="Напр: unixml.log также можно и log_name.secret_ext - для защиты. Если пусто - не логируется" type="text" name="unixml_<?php echo $feed; ?>_log" value="<?php echo ${'unixml_' . $feed . '_log'}; ?>" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_quantity" title="Выгружать все товары даже с нулевым остатком. По умолчанию выгрузка забирает только товары в наличии. Нужно для магазинов которые не привязаны к остаткам товара или же для обновления данных при отсутствии товара в магазине" data-toggle="tooltip"><span>Привязка к количеству</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_quantity" name="unixml_<?php echo $feed; ?>_quantity" class="form-control">
                    <?php if (${'unixml_' . $feed . '_quantity'}) { ?>
                      <option value="1" selected="selected">Не привязываемся, выгружаем даже то что не в наличии</option>
                      <option value="0">Привязываемся, выгружаем только в наличии</option>
                    <?php } else { ?>
                      <option value="1">Не привязываемся, выгружаем даже то что не в наличии</option>
                      <option value="0" selected="selected">Привязываемся, выгружаем только в наличии</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#unixml_<?php echo $feed; ?>_quantity').on('change', function(){
                    if($(this).val() == 1){
                      $('#hideblock<?php echo $feed; ?>').slideDown(100);
                    }else{
                      $('#hideblock<?php echo $feed; ?>').slideUp(100);
                    }
                  });
                });
              </script>

              <div id="hideblock<?php echo $feed; ?>" class="form-group" <?php if (!${'unixml_' . $feed . '_quantity'}) { ?>style="display:none;"<?php } ?>>
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_stock" title="Статус товара во вкладке Данные при котором товар будет в наличии, даже при условии что его остаток 0" data-toggle="tooltip"><span>Статус в наличии</span></label>
                <div class="col-sm-10">
                  <select name="unixml_<?php echo $feed; ?>_stock" id="unixml_<?php echo $feed; ?>_stock" class="form-control">
                    <?php foreach ($stock_statuses as $stock_status) { ?>
                      <?php if ($stock_status['stock_status_id'] == ${'unixml_' . $feed . '_stock'}) { ?>
                        <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_option_multiplier_status" title="Если для маркетплейса необходимо подавать товары с опциями как отдельные позиции необходимо включить настройку и ниже выбрать опцию на которую будет умножение" data-toggle="tooltip"><span><?php echo $entry_option_multiplier ?></span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_option_multiplier_status" name="unixml_<?php echo $feed; ?>_option_multiplier_status" class="form-control">
                    <?php if (${'unixml_' . $feed . '_option_multiplier_status'}) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <script>
                $(document).ready(function(){
                  $('#unixml_<?php echo $feed; ?>_option_multiplier_status').on('change', function(){
                    if($(this).val() == 1){
                      $('#hideoption<?php echo $feed; ?>').slideDown(100);
                    }else{
                      $('#hideoption<?php echo $feed; ?>').slideUp(100);
                    }
                  });
                });
              </script>

              <div id="hideoption<?php echo $feed; ?>" class="form-group" <?php if (!${'unixml_' . $feed . '_option_multiplier_status'}) { ?>style="display:none;"<?php } ?>>
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_option_multiplier_id" title="Это может быть размер, цвет, комплектация. То что на сайте как один товар в выгрузке будут идти как разные товары которые отличаются выбранной опцией" data-toggle="tooltip"><span>Опция на которую множим</span></label>
                <?php if (${'unixml_' . $feed . '_options'}) { ?>
                  <div class="col-sm-10">
                    <div id="unixml_<?php echo $feed; ?>_option_multiplier_id" class="scrollbox" style="max-height:200px;border:1px solid #ccc;overflow:auto;">
                      <?php $class = 'odd'; ?>
                      <?php foreach (${'unixml_' . $feed . '_options'} as $option) { ?>
                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                        <div class="<?php echo $class; ?>">
                          <input type="checkbox" name="unixml_<?php echo $feed; ?>_option_multiplier_id[]" value="<?php echo $option['option_id']; ?>" <?php if (in_array($option['option_id'], ${'unixml_' . $feed . '_option_multiplier_id'})) { ?>checked="checked"<?php } ?> />
                          <?php echo $option['name']; ?>
                        </div>
                      <?php } ?>
                    </div>
                    <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выбрать все</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Убрать все</a>
                  </div>
                <?php }else{ ?>
                  <div class="col-sm-10 control-label" style="text-align:left;">На данный момент в магазине нет опций, умножение недоступно</div>
                <?php } ?>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_genname" title="В некоторых случаях требуется специальное название товара определенной структуры. С помощью шаблона можно генерировать название товара на лету." data-toggle="tooltip"><span>Шаблон генерации названий товаров</span><br><br><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#uxmLong">Какие поля доступны?</button></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_genname" type="text" name="unixml_<?php echo $feed; ?>_genname" value="<?php echo ${'unixml_' . $feed . '_genname'} ?>" class="form-control">
                  <b>((таблица.поле))</b> - поле из базы данных. Таблица может быть <b>p</b> - product и <b>pd</b> - product_description. Пример: <b>((p.quantity))</b> или <b>((pd.meta_title))</b><br>
                  <b>{{атрибут}}</b> - название атрибута (если не найден - не выводится),<br>
                  <b>[[опция]]</b> - название опции (работает в случает умножения товара на опцию)<br>
                  <b>{поле массива}</b> - любое поле массива товаров, например name (уже сгенерированное), manufacturer, special и т.п.<br>
                  <small>После настройки шаблона генерации обязательно проверьте корректность работы выгрузки</small>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_products_mode">
                  <select name="unixml_<?php echo $feed; ?>_products_mode" class="form-control" style="font-weight:400;">
                    <option value="" <?php if(!${'unixml_' . $feed . '_products_mode'}){ ?>selected="selected"<?php } ?>>Выгружаем только:</option>
                    <option value="1" <?php if(${'unixml_' . $feed . '_products_mode'} == 1){ ?>selected="selected"<?php } ?>>Запретить выгружать:</option>
                  </select>
                  <small style="font-weight:400;text-align:left;display:block;">
                    <br><b>Выгружаем только</b> - выгрузка только выбранных товаров несмотря на настройки категории, брендов и т.п.
                    <hr><b>Запретить выгружать</b> - выгрузка всех товаров учитывая настройки кроме выбранных. Они не попадут в выгрузку
                  </small>
                </label>
                <div class="col-sm-10">
                  <input type="text" name="unixml_<?php echo $feed; ?>_product" value="" placeholder="Вводите название товара или артикул" id="input-products" class="form-control" />
                  <div id="unixml_<?php echo $feed; ?>_products" class="well well-sm" style="height: 250px; overflow: auto;">
                    <?php foreach(${'unixml_' . $feed . '_products'} as $product){ ?>
                      <div id="unixml_<?php echo $feed; ?>_products<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                        <input type="hidden" name="unixml_<?php echo $feed; ?>_products[]" value="<?php echo $product['product_id']; ?>" />
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <script>
              $('input[name="unixml_<?php echo $feed; ?>_product"]').autocomplete({
                'source': function(request, response) {
                  $.ajax({
                    url: 'index.php?route=<?php echo $path; ?>/autocomplete_product&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                      response($.map(json, function(item) {
                        return {
                          label: item['name'],
                          value: item['product_id']
                        }
                      }));
                    }
                  });
                },
                'select': function(item) {
                  $('input[name="unixml_<?php echo $feed; ?>_product"]').val('');

                  $('#unixml_<?php echo $feed; ?>_products' + item['value']).remove();

                  $('#unixml_<?php echo $feed; ?>_products').append('<div id="unixml_<?php echo $feed; ?>_products' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="unixml_<?php echo $feed; ?>_products[]" value="' + item['value'] + '" /></div>');
                }
              });

              $('#unixml_<?php echo $feed; ?>_products').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
              });
              </script>


              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_categories" title="Выгружать товары из некоторых категорий (если выбраны бренды то товары будут и брендов и категорий)" data-toggle="tooltip">
                  <span>Категории</span>
                  <input type="text" placeholder="Найти категорию" class="form-control category_search" style="margin-top:10px;" data-feed="<?php echo $feed; ?>">
                  <div style="text-align:left;"><small>показано <b id="<?php echo $feed; ?>_category_counter"><?php echo $category_all; ?></b> из <b id="<?php echo $feed; ?>_category_all"><?php echo $category_all; ?></b></small></div>
                </label>
                <div class="col-sm-10">
                  <div id="unixml_<?php echo $feed; ?>_categories" class="scrollbox" style="max-height:400px;min-height:100px;border:1px solid #ccc;overflow:auto;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($categories as $category) { ?>
                      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                      <div class="<?php echo $class; ?>" data-id="<?php echo $category['category_id']; ?>">
                        <?php if (in_array($category['category_id'], ${'unixml_' . $feed . '_categories'})) { ?>
                          <input type="checkbox" name="unixml_<?php echo $feed; ?>_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                          <?php echo $category['name']; ?>
                        <?php } else { ?>
                          <input type="checkbox" name="unixml_<?php echo $feed; ?>_categories[]" value="<?php echo $category['category_id']; ?>" />
                          <?php echo $category['name']; ?>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выбрать все</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Убрать все</a>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_category_match" title="В выгрузку можно отдавать другие названия категорий. Нужно для того что бы было точное совпадение с категориями маркетплейса. Наценка позволяет делать наценку на товары определенной категории. Свои теги позволяют выводить для товаров определенной категории свою информацию" data-toggle="tooltip"><span>Соответствие названий категорий, наценка для категории а также свои теги для категории</span></label>
                <div class="col-sm-10 table-responsive" style="overflow: visible;">
                  <table id="unixml_<?php echo $feed; ?>_category_match" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left">Категория магазина</td>
                        <td class="text-left">Категория в выгрузке</td>
                        <td class="text-left">Наценка</td>
                        <td class="text-left">Теги и их значения</td>
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php ${'category_match_row' . $feed} = 10000 * $key; ?>
                      <?php foreach (${'unixml_' . $feed . '_category_match'} as $xml_name) { ?>
                        <tr id="category_match_row<?php echo ${'category_match_row' . $feed}; ?>">
                          <td class="text-left" style="width: 22%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_category_match[<?php echo ${'category_match_row' . $feed}; ?>][category_name]" value="<?php echo $xml_name['category_name']; ?>" placeholder="Вводите что-то из названия категории" class="form-control" />
                            <input type="hidden" name="unixml_<?php echo $feed; ?>_category_match[<?php echo ${'category_match_row' . $feed}; ?>][category_id]" value="<?php echo $xml_name['category_id']; ?>" />
                          </td>
                          <td class="text-left" style="width: 22%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_category_match[<?php echo ${'category_match_row' . $feed}; ?>][xml_name]" value="<?php echo $xml_name['xml_name']; ?>" placeholder="Название этой категории в выгрузке" class="form-control" />
                          </td>
                          <td class="text-left" style="width: 13%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_category_match[<?php echo ${'category_match_row' . $feed}; ?>][markup]" value="<?php echo $xml_name['markup']; ?>" placeholder="Наценка на товары категории" class="form-control" />
                          </td>
                          <td class="text-left" style="width: 41%;">
                            <textarea name="unixml_<?php echo $feed; ?>_category_match[<?php echo ${'category_match_row' . $feed}; ?>][custom]" placeholder="Теги для товаров этой категории" class="form-control" ><?php echo $xml_name['custom']; ?></textarea>
                          </td>
                          <td class="text-center"><button type="button" onclick="$('#category_match_row<?php echo ${'category_match_row' . $feed}; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php ${'category_match_row' . $feed}++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="4"></td>
                        <td class="text-center"><button type="button" onclick="addCategoryMatch<?php echo $feed; ?>();" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                  <div>
                    Наценка может быть как "10%"" так и фиксированное число, например "100".<br>
                    <hr>
                    Теги и их значения пишем в формате <small>(каждый тег и значение с новой строки, разделитель <b>==</b>)</small>
                    <blockquote style="font-size:12px;margin:0;">keywords==Футболки, Футболки для мальчиков, Футболки размера S<br>
                      delivery==Доставка от 3 до 5 дней<br>
                      param name="Гарантия магазина"==12мес<br>
                      country_of_origin==Испания
                    </blockquote>
                    <b>Можно использовать переменные [[Опция]] а также {{Атрибут}}</b>
                    <br><br>
                    В выгрузке будет:<br>
                    <blockquote style="font-size:12px;margin:0;">
                    &lt;keywords&gt;Футболки, Футболки для мальчиков, Футболки размера S&lt;/keywords&gt;<br>
                    &lt;delivery&gt;Доставка от 3 до 5 дней&lt;/delivery&gt;<br>
                    &lt;param name="Гарантия магазина"&gt;12мес&lt;/param&gt;<br>
                    &lt;country_of_origin&gt;Доставка от 3 до 5 дней&lt;/country_of_origin&gt;
                    </blockquote>
                  </div>
                </div>
              </div>


              <style>.va-top{vertical-align:top!important;}.va-top + td .well{margin-bottom:0;}.importMarkup{width:100%;margin-top:10px;}</style>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_product_markup" title="В выгрузке можно формировать наценку на каждый товар. Для этого создаем группу наценки и добавляем туда товары. Для импорта достаточно вставить данные и указать разделитель и что это за поле" data-toggle="tooltip"><span>Наценки на группы товаров</span></label>
                <div class="col-sm-10 table-responsive" style="overflow-y:scroll;max-height:730px;">
                  <table id="unixml_<?php echo $feed; ?>_product_markup" class="table table-striped2 table-bordered table-hover2">
                    <thead>
                      <tr>
                        <td class="text-left">Название группы</td>
                        <td class="text-left">Товары</td>
                        <td class="text-left">Наценка</td>
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php ${'product_markup_row' . $feed} = 10000 * $key; ?>
                      <?php foreach (${'unixml_' . $feed . '_product_markup'} as $markup_item) { ?>
                        <tr id="product_markup_row<?php echo ${'product_markup_row' . $feed}; ?>">
                          <td class="text-left va-top" style="width: 15%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_product_markup[<?php echo ${'product_markup_row' . $feed}; ?>][name]" value="<?php echo $markup_item['name']; ?>" placeholder="Наценка на товары категории" class="form-control" />
                            <button class="btn btn-info importMarkup" data-feed="<?php echo $feed; ?>" data-row="<?php echo ${'product_markup_row' . $feed}; ?>" title="Импортировать товары" data-toggle="tooltip"><i class="fa fa-upload" aria-hidden="true"></i> Импорт</button>
                          </td>
                          <td class="text-left" style="width: 67%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_product_markup_input<?php echo ${'product_markup_row' . $feed}; ?>" value="" placeholder="Вводите название товара или артикул" id="input-markup-products<?php echo ${'product_markup_row' . $feed}; ?>" class="form-control" />
                            <div id="unixml_<?php echo $feed; ?>_markup_products<?php echo ${'product_markup_row' . $feed}; ?>" class="well well-sm" style="height: 250px; overflow: auto;">
                              <?php foreach($markup_item['products'] as $product){ ?>
                                <div id="unixml_<?php echo $feed; ?>_markup_products<?php echo ${'product_markup_row' . $feed}; ?>-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['model']; ?> - <?php echo $product['name']; ?>
                                  <input type="hidden" name="unixml_<?php echo $feed; ?>_product_markup[<?php echo ${'product_markup_row' . $feed}; ?>][products][]" value="<?php echo $product['product_id']; ?>" />
                                </div>
                              <?php } ?>
                            </div>
                          </td>
                          <td class="text-left va-top" style="width: 13%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_product_markup[<?php echo ${'product_markup_row' . $feed}; ?>][markup]" value="<?php echo $markup_item['markup']; ?>" placeholder="Наценка" class="form-control" />
                          </td>
                          <td class="text-center va-top"><button type="button" onclick="$('#product_markup_row<?php echo ${'product_markup_row' . $feed}; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php ${'product_markup_row' . $feed}++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3">При добавлении сюда товаров другие скидки (общие и на категорию) срабатывать не будут.<br>Наценка может быть как "10%"" так и фиксированное число, например "100"</td>
                        <td class="text-center"><button type="button" onclick="addProductMarkup<?php echo $feed; ?>();" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_andor" title="Жесткая привязка - товары выбранных категорий и только выбранных брендов; Плюсуем - товары выбранных категорий а также товары выбранных брендов" data-toggle="tooltip"><span>Логика выгрузки</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_andor" name="unixml_<?php echo $feed; ?>_andor" class="form-control">
                    <?php if (${'unixml_' . $feed . '_andor'}) { ?>
                      <option value="0">Товары выбранных категорий и только выбранных брендов</option>
                      <option value="1" selected="selected">Товары выбранных категорий а также товары выбранных брендов</option>
                    <?php } else { ?>
                      <option value="0" selected="selected">Товары выбранных категорий и только выбранных брендов</option>
                      <option value="1">Товары выбранных категорий а также товары выбранных брендов</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_brands" title="Выгружать товары некоторых брендов (если выбраны и категории то товары будут и категорий и брендов)" data-toggle="tooltip"><span>Бренды</span></label>
                <div class="col-sm-10">
                  <div id="unixml_<?php echo $feed; ?>_brands" class="scrollbox" style="max-height:400px;border:1px solid #ccc;overflow:auto;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                      <div class="<?php echo $class; ?>">
                        <?php if (in_array($manufacturer['manufacturer_id'], ${'unixml_' . $feed . '_manufacturers'})) { ?>
                          <input type="checkbox" name="unixml_<?php echo $feed; ?>_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                          <?php echo $manufacturer['name']; ?>
                        <?php } else { ?>
                          <input type="checkbox" name="unixml_<?php echo $feed; ?>_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                          <?php echo $manufacturer['name']; ?>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выбрать все</a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Убрать все</a>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_replace_names" title="Можно внести что меняем на что меняем в названии, модели, производителе и описании. Часто маркетплейсы запрещают какие-то слова или тексты в выгрузке. С помощью списка автозамены можно убрать или поменять те данные что нужно." data-toggle="tooltip"><span>Список замен слов</span></label>
                <div class="col-sm-10 table-responsive">
                  <table id="unixml_<?php echo $feed; ?>_replace_names" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $entry_replace_from; ?></td>
                        <td class="text-left"><?php echo $entry_replace_to; ?></td>
                        <td class="text-center"></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php ${'replace_name_row' . $feed} = 10000 * $key; ?>
                      <?php foreach (${'unixml_' . $feed . '_replace_name'} as $xml_attribute) { ?>
                        <tr id="replace_name-row<?php echo ${'replace_name_row' . $feed}; ?>">
                          <td class="text-left" style="width: 40%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_replace_name[<?php echo ${'replace_name_row' . $feed}; ?>][name_from]" value="<?php echo $xml_attribute['name_from']; ?>" placeholder="<?php echo $entry_replace_from; ?>" class="form-control" />
                          </td>
                          <td class="text-left">
                            <input type="text" name="unixml_<?php echo $feed; ?>_replace_name[<?php echo ${'replace_name_row' . $feed}; ?>][name_to]" value="<?php echo $xml_attribute['name_to']; ?>" placeholder="<?php echo $entry_replace_to; ?>" class="form-control" />
                          </td>
                          <td class="text-center"><button type="button" onclick="$('#replace_name-row<?php echo ${'replace_name_row' . $feed}; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php ${'replace_name_row' . $feed}++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-center"><button type="button" onclick="addReplaceRow<?php echo $feed; ?>();" data-toggle="tooltip" title="<?php echo $button_replace_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_image" title="Выгружать все товары или только с фото" data-toggle="tooltip"><span>Привязка к фото</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_image" name="unixml_<?php echo $feed; ?>_image" class="form-control">
                    <?php if (${'unixml_' . $feed . '_image'}) { ?>
                      <option value="0">Выгружать товары даже без фото</option>
                      <option value="1" selected="selected">Не выгружать товары без фото</option>
                    <?php } else { ?>
                      <option value="0" selected="selected">Выгружать товары даже без фото</option>
                      <option value="1">Не выгружать товары без фото</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_images" title="Некоторые маркетплейсы принимают дополнительные фото товара. Рекомендуется включать доп фото. Однако там где не надо, выключайте что бы лишний раз не нагружать сервер" data-toggle="tooltip"><span>Выгрузка дополнительных фото</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_images" name="unixml_<?php echo $feed; ?>_images" class="form-control">
                    <?php if (${'unixml_' . $feed . '_images'}) { ?>
                      <option value="1" selected="selected">Выгружать дополнительные фото</option>
                      <option value="0">Не выгружать дополнительные фото</option>
                    <?php } else { ?>
                      <option value="1">Выгружать дополнительные фото</option>
                      <option value="0" selected="selected">Не выгружать дополнительные фото</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_attribute_status" title="Некоторые маркетплейсы не требуют характеристик. По этому это это тот самый случай лучше выключить их что бы не нагружать базу данных" data-toggle="tooltip"><span>Выгрузка атрибутов</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_attribute_status" name="unixml_<?php echo $feed; ?>_attribute_status" class="form-control">
                    <?php if (${'unixml_' . $feed . '_attribute_status'}) { ?>
                      <option value="0">Выгружать атрибуты</option>
                      <option value="1" selected="selected">Не выгружать атрибуты</option>
                    <?php } else { ?>
                      <option value="0" selected="selected">Выгружать атрибуты</option>
                      <option value="1">Не выгружать атрибуты</option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <script>
                $(document).ready(function(){
                  $('#unixml_<?php echo $feed; ?>_attribute_status').on('change', function(){
                    if($(this).val() != 1){
                      $('#hideattr<?php echo $feed; ?>').slideDown(100);
                    }else{
                      $('#hideattr<?php echo $feed; ?>').slideUp(100);
                    }
                  });
                });
              </script>

              <div id="hideattr<?php echo $feed; ?>" class="form-group" <?php if (${'unixml_' . $feed . '_attribute_status'}) { ?>style="display:none;"<?php } ?>>
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_attributes" title="Если заданы соответствия то в выгрузку попадают только эти атрибуты. Если соответствий не задано в выгрузку попадают все атрибуты" data-toggle="tooltip"><span>Соответствие атрибутов</span></label>
                <div class="col-sm-10 table-responsive" style="overflow: visible;">
                  <table id="unixml_<?php echo $feed; ?>_attributes" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $entry_attribute; ?></td>
                        <td class="text-left"><?php echo $entry_attribute_xml; ?></td>
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php ${'attribute_row' . $feed} = 10000 * $key; ?>
                      <?php foreach (${'unixml_' . $feed . '_attributes'} as $xml_attribute) { ?>
                        <tr id="attribute-row<?php echo ${'attribute_row' . $feed}; ?>">
                          <td class="text-left" style="width: 40%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_attributes[<?php echo ${'attribute_row' . $feed}; ?>][attribute_name]" value="<?php echo $xml_attribute['attribute_name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
                            <input type="hidden" name="unixml_<?php echo $feed; ?>_attributes[<?php echo ${'attribute_row' . $feed}; ?>][attribute_id]" value="<?php echo $xml_attribute['attribute_id']; ?>" />
                          </td>
                          <td class="text-left">
                            <input type="text" name="unixml_<?php echo $feed; ?>_attributes[<?php echo ${'attribute_row' . $feed}; ?>][xml_name]" value="<?php echo $xml_attribute['xml_name']; ?>" placeholder="<?php echo $entry_attribute_xml; ?>" class="form-control" />
                          </td>
                          <td class="text-center"><button type="button" onclick="$('#attribute-row<?php echo ${'attribute_row' . $feed}; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php ${'attribute_row' . $feed}++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-center"><button type="button" onclick="addAttribute<?php echo $feed; ?>();" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_additional_params" title="Каждому товару можно задать дополнительные статические параметры. Например сроки доставки, гарантия магазина и т.п. Часто добавляют то что требуют маркетплейсы." data-toggle="tooltip"><span>Дополнительные статические параметры</span><br><br><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#uxmLong">Какие поля доступны?</button></label>
                <div class="col-sm-10 table-responsive">
                  <table id="unixml_<?php echo $feed; ?>_additional_params" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $entry_param_name; ?></td>
                        <td class="text-left"><?php echo $entry_param_text; ?></td>
                        <td></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php ${'param_row' . $feed} = 10000 * $key; ?>
                      <?php foreach (${'unixml_' . $feed . '_additional_params'} as $xml_attribute) { ?>
                        <tr id="param-row<?php echo ${'param_row' . $feed}; ?>">
                          <td class="text-left" style="width: 40%;">
                            <input type="text" name="unixml_<?php echo $feed; ?>_additional_params[<?php echo ${'param_row' . $feed}; ?>][param_name]" value="<?php echo $xml_attribute['param_name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
                          </td>
                          <td class="text-left">
                            <input type="text" name="unixml_<?php echo $feed; ?>_additional_params[<?php echo ${'param_row' . $feed}; ?>][param_text]" value="<?php echo $xml_attribute['param_text']; ?>" placeholder="<?php echo $entry_attribute_xml; ?>" class="form-control" />
                          </td>
                          <td class="text-center"><button type="button" onclick="$('#param-row<?php echo ${'param_row' . $feed}; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                        <?php ${'param_row' . $feed}++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2">
                          В качестве Текста атрибута можно указывать не только статический текст а и переменные:<br>
                          <b>{{атрибут}}</b> - название атрибута (если не найден - не выводится)<br>
                          <b>((таблица.поле))</b> - поле из базы данных. Таблица может быть <b>p</b> - product и <b>pd</b> - product_description. Пример: <b>((p.quantity))</b> или <b>((pd.meta_title))</b><br>
                          <b>[[опция]]</b> - название опции (работает в случает умножения товара на опцию)<br>
                          <b>{поле массива}</b> - любое поле массива товаров, например name (уже сгенерированное), manufacturer, special и т.п.<br>
                          <small>После настройки шаблона генерации обязательно проверьте корректность работы выгрузки</small>
                          <hr>
                          <p>Для вывода атрибута и его значения в виде <b>&lt;attribute_name&gt;attribute_value&lt;/attribute_name&gt;</b> в качестве Названия атрибута можно указать например &lt;waranty&gt;<br>
                            <small>Нужно для некоторых маркетплейсов например Пром, нужны keywords, waranty, country_of_origin, barcode и т.п. Можно создавать любые теги в выгрузке.</small></p>
                        </td>
                        <td class="text-center"><button type="button" onclick="addParam<?php echo $feed; ?>();" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_gendesc" title="В некоторых случаях требуется специальное описание товара определенной структуры. С помощью шаблона можно генерировать описания товара на лету." data-toggle="tooltip"><span>Шаблон генерации описания товаров</span><br><br><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#uxmLong">Какие поля доступны?</button></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_gendesc" type="text" name="unixml_<?php echo $feed; ?>_gendesc" value="<?php echo ${'unixml_' . $feed . '_gendesc'}; ?>" class="form-control">
                  <b>((таблица.поле))</b> - поле из базы данных. Таблица может быть <b>p</b> - product и <b>pd</b> - product_description. Пример: <b>((p.quantity))</b> или <b>((pd.meta_title))</b><br>
                  <b>{{атрибут}}</b> - название атрибута (если не найден - не выводится),<br>
                  <b>[[опция]]</b> - название опции (работает в случает умножения товара на опцию)<br>
                  <b>{поле массива}</b> - любое поле массива товаров, например name (уже сгенерированное), manufacturer, special и т.п.<br>
                  <small>После настройки шаблона генерации обязательно проверьте корректность работы выгрузки</small>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_clear_desc" title="Можно чистить описания и выводить просто текст, а можно выводить оригинальное описание без очистки. Некоторые маркетплейсы поддерживают html разметку описаний" data-toggle="tooltip"><span>Режим очистки описаний</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_gendesc_mode" name="unixml_<?php echo $feed; ?>_clear_desc" class="form-control">
                      <option value="" <?php if (!${'unixml_' . $feed . '_clear_desc'}) { ?>selected="selected"<?php } ?>>Чистить описание от спецсимволов и html тегов</option>
                      <option value="1" <?php if (${'unixml_' . $feed . '_clear_desc'}) { ?>selected="selected"<?php } ?>>НЕ чистить описание - отдавать в выгрузку оригинал</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_gendesc_mode" title="Можно настроить когда генерировать описание а когда нет" data-toggle="tooltip"><span>Режим генерации описаний</span></label>
                <div class="col-sm-10">
                  <select id="unixml_<?php echo $feed; ?>_gendesc_mode" name="unixml_<?php echo $feed; ?>_gendesc_mode" class="form-control">
                      <option value="" <?php if (!${'unixml_' . $feed . '_gendesc_mode'}) { ?>selected="selected"<?php } ?>>Генерировать описание даже есть оно есть</option>
                      <option value="1" <?php if (${'unixml_' . $feed . '_gendesc_mode'}) { ?>selected="selected"<?php } ?>>Генерировать описание только в случае если его нет</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="unixml_<?php echo $feed; ?>_secret" title="Ключ защиты позволяет задать свой параметр в ссылку для генерации xml. Это нужно что бы по ссылке постоянно не запускали генерацию которая нагружает сайт. Введя ключ выгрузка будет по адресу с приставкой &key=secret_key" data-toggle="tooltip"><span>Ключ защиты от запуска</span></label>
                <div class="col-sm-10">
                  <input id="unixml_<?php echo $feed; ?>_secret" type="text" name="unixml_<?php echo $feed; ?>_secret" value="<?php echo ${'unixml_' . $feed . '_secret'}; ?>" class="form-control">
                  <small>что бы перейти по ссылке сначала сохраните настройки что бы заработала защита</small>
                </div>
              </div>
              <script>
                $('#unixml_<?php echo $feed; ?>_secret').keyup(function(){
                  if($(this).val() == ''){
                    $('#key_<?php echo $feed; ?> span').text('');
                    $('#cron_<?php echo $feed; ?> span').text('');
                  }else{
                    $('#key_<?php echo $feed; ?> span').text('&key=' + $(this).val());
                    $('#cron_<?php echo $feed; ?> span').text('&key=' + $(this).val());
                  }
                  $('#key_<?php echo $feed; ?>').attr('href',  $('#key_<?php echo $feed; ?>').text());
                  $('#cron_<?php echo $feed; ?>').attr('href',  $('#key_<?php echo $feed; ?>').text());
                });
              </script>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="<?php echo ${$feed . '_data_feed'}; ?>" title="Ссылку которую надо отдавать маркетплейсу. По ней генерируется xml выгрузка" data-toggle="tooltip"><span><?php echo $entry_data_feed; ?></span></label>
                <div class="col-sm-10 control-label" style="text-align:left;">
                  <a href="<?php echo ${$feed . '_data_feed'}; ?><?php if(${'unixml_' . $feed . '_secret'}){ ?>&key=<?php echo ${'unixml_' . $feed . '_secret'}; ?><?php } ?>" id="key_<?php echo $feed; ?>" target="_blank" title="Отроется в новом окне" data-toggle="tooltip"><?php echo ${$feed . '_data_feed'}; ?><span><?php if(${'unixml_' . $feed . '_secret'}){ ?>&key=<?php echo ${'unixml_' . $feed . '_secret'}; ?><?php } ?></span></a>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="<?php echo ${$feed . '_data_feed'}; ?>" title="Ссылку которую надо запускать для автоматической генерации xml в файл." data-toggle="tooltip"><span>Запуск по крону</span></label>
                <div class="col-sm-10 control-label" style="text-align:left;">
                  Ссылка запуска: <a href="<?php echo ${$feed . '_data_feed'}; ?>&cron=file<?php if(${'unixml_' . $feed . '_secret'}){ ?>&key=<?php echo ${'unixml_' . $feed . '_secret'}; ?><?php } ?>" id="cron_<?php echo $feed; ?>" target="_blank" title="Отроется в новом окне" data-toggle="tooltip"><?php echo ${$feed . '_data_feed'}; ?>&cron=file<span><?php if(${'unixml_' . $feed . '_secret'}){ ?>&key=<?php echo ${'unixml_' . $feed . '_secret'}; ?><?php } ?></span></a><br>
                  xml файл: <a href="<?php echo HTTPS_CATALOG; ?>price/<?php echo $feed; ?>.xml" target="_blank" title="Отроется в новом окне" data-toggle="tooltip"><?php echo HTTPS_CATALOG; ?>price/<?php echo $feed; ?>.xml</a>
                </div>
              </div>

            </div>
            <!-- --tab-<?php echo $feed; ?> -->
            <?php } ?>
            <!-- ++tab-info -->
            <div class="tab-pane" id="tab-info">
              <h3>Отображать во вкладках</h3>
              <p>Можно скрыть то что не используется</p>
              <div class="form-group1">
              <?php foreach($feeds as $key => $feed){ ?>
                <div class="col-sm-212">
                  <input type="checkbox" <?php if(in_array($feed, $unixml_hide)){ ?>checked="checked"<?php } ?> data-feed="<?php echo $feed; ?>" class="checkbox_exp" id="<?php echo $key; ?>" name="unixml_hide[]" value="<?php echo $feed; ?>"/>
                  <label for="<?php echo $key; ?>"><?php echo $feed; ?></label>
                </div>
              <?php } ?>
              </div>
              <script>

                $('#tab-info input').change(function(){
                  hide_tabs();
                });
                function hide_tabs(){
                  count = 0;
                  $('#tab-info input[type="checkbox"]').each(function(index, element) {
                    if($(this).is(':checked')){ //показываем
                      $('a[href="#tab-' + $(this).data('feed') + '"]').parent().show();
                      count++;
                    }else{ //прячем
                      $('a[href="#tab-' + $(this).data('feed') + '"]').parent().hide();
                    }
                    $('#count_active_feeds').text(count);
                  });
                }

              </script>

              <hr>


              <h3>Что бы сделать свою копию выгрузки или кастомную выгрузку</h3>
              <p>Перед работами создайте резервные копии а также помните что при обновлении надо будет сохранить все кастомные выгрузки. В новой версии будет возможность создавать свои выгрузки из админки модуля.</p>
              <ol>
                <li>Открываем файл price/unixml.dat (в текстовом блокноте) и добавляем в первую строку название выгрузки. Название должно быть только на литинице с маленькой буквы, без пробелов и спецсимволов. Например custom</li>
                <li>Открываем файл catalog/controller/<strong title="Для Opencart версии 2.3 и выше" data-toggle="tooltip">{extension/}</strong>feed/unixml.php и видим что для каждой выгрузке идет своя функция. Копируем ту что надо и вставляем эту функцию в конец файла перед закрывающей скобкой }. public function custom() { и $this->feed = 'custom'; меняем на название выгрузки.</li>
              </ol>

              <hr>
              <h3>В планах что добавится в новых версиях UniXML</h3>
              <ul>
                <li>Возможность создавать свои вкладки и выгрузки из админки</li>
                <li>Будет функционал не только выгрузки а и загрузки данных в магазин их XML и других форматов (Экспорт/Импорт)</li>
                <li>Будет внедрен Яндекс турбо</li>
                <li>Будет опция копирование фото с правильным названием для маркетплейса</li>
                <li>Выгрузка на авито, ozon.ru, robo.market</li>
                <li>Другие мелки доработки, которые помогут быть модулю еще удобнее и лучше</li>
              </ul>
              <hr>

              <blockquote>
              <p>Спасибо за использование модуля! Надеюсь он оправдает Ваши надежды и капиталовложения :)</p>
              <p>Также не забывайте что есть поддержка и по любым вопросам можно писать на почту info@microdata.pro</p>
              <p>Сайты автора: <a href="https://microdata.pro" target="_blank">https://microdata.pro</a> и <a href="https://for-opencart.com" target="_blank">https://for-opencart.com</a> там можно найти много полезного.</p>
              <p>По любым вопросам доработки/разработки можно обращаться к автору info@microdata.pro</p>
              </blockquote>
              <hr>
              <br>
              <?php echo $more_info; ?>
            </div>
            <!-- --tab-info -->
          </div>

          <input type="hidden" value="<?php echo $unixml_active_tab; ?>" id="unixml_active_tab" name="unixml_active_tab">
          <script>
            $('.nav.nav-tabs>li').on('click', function(){
              $('#unixml_active_tab').val($(this).find('a').attr('href'));
              if($(this).find('a').attr('href') != "#tab-info"){
                $('.name_set').removeClass('hided');
                $('.name_set b').text($(this).find('a').text());
              }else{
                $('.name_set').addClass('hided');
              }
            });
          </script>

        </form>
       <?php } ?>
      </div>
    </div>
  </div>
</div>

<?php if($a){ ?>
<?php foreach($feeds as $key => $feed){ ?>
<!-- ++tab-<?php echo $feed; ?> -->
<script>

  //product_markup
  var product_markup_row<?php echo $feed; ?> = <?php echo ${'product_markup_row' . $feed} ?>;
  function addProductMarkup<?php echo $feed; ?>() {
    html  = '<tr id="product_markup_row' + product_markup_row<?php echo $feed; ?> + '">';
    html += '  <td class="text-left va-top" style="width: 15%;"><input type="text" name="unixml_<?php echo $feed; ?>_product_markup[' + product_markup_row<?php echo $feed; ?> + '][name]" value="" placeholder="Название группы" class="form-control" /><button class="btn btn-info importMarkup" data-feed="<?php echo $feed; ?>" data-row="' + product_markup_row<?php echo $feed; ?> + '" title="Импортировать товары" data-toggle="tooltip"><i class="fa fa-upload" aria-hidden="true"></i> Импорт</button></td>';
    html += '  <td class="text-left" style="width: 67%;">';
    html += '   <input type="text" name="unixml_<?php echo $feed; ?>_product_markup_input' + product_markup_row<?php echo $feed; ?> + '" value="" placeholder="Вводите название товара или артикул" id="input-markup-products' + product_markup_row<?php echo $feed; ?> + '" class="form-control" />';
    html += '   <div id="unixml_<?php echo $feed; ?>_markup_products' + product_markup_row<?php echo $feed; ?> + '" class="well well-sm" style="height: 250px; overflow: auto;">';
    html += '   </div>';
    html += '  </td>';
    html += '  <td class="text-left va-top" style="width: 13%;"><input type="text" name="unixml_<?php echo $feed; ?>_product_markup[' + product_markup_row<?php echo $feed; ?> + '][markup]" value="" placeholder="Наценка" class="form-control" /></td>';
    html += '  <td class="text-center va-top"><button type="button" onclick="$(\'#product_markup_row' + product_markup_row<?php echo $feed; ?> + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';
    $('#unixml_<?php echo $feed; ?>_product_markup tbody').append(html);

    markupautocomplete<?php echo $feed; ?>(product_markup_row<?php echo $feed; ?>);

    product_markup_row<?php echo $feed; ?>++;
  }

  function markupautocomplete<?php echo $feed; ?>(product_markup_row) {
    $('#input-markup-products' + product_markup_row).autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=<?php echo $path; ?>/autocomplete_product&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['product_id']
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('#input-markup-products' + product_markup_row).val('');
        $('#unixml_<?php echo $feed; ?>_markup_products' + product_markup_row + '-' + item['value']).remove();
        $('#unixml_<?php echo $feed; ?>_markup_products' + product_markup_row).append('<div id="unixml_<?php echo $feed; ?>_markup_products' + product_markup_row + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="unixml_<?php echo $feed; ?>_product_markup[' + product_markup_row + '][products][]" value="' + item['value'] + '" /></div>');
      }
    });


  }

  $('#unixml_<?php echo $feed; ?>_product_markup tbody tr').each(function(index, element) {
    markupautocomplete<?php echo $feed; ?>(index);
  });
  //product_markup

  //replace
  var replace_name_row<?php echo $feed; ?> = <?php echo ${'replace_name_row' . $feed} ?>;
  function addReplaceRow<?php echo $feed; ?>() {
    html  = '<tr id="replace_name-row' + replace_name_row<?php echo $feed; ?> + '">';
    html += '  <td class="text-left" style="width: 40%;"><input type="text" name="unixml_<?php echo $feed; ?>_replace_name[' + replace_name_row<?php echo $feed; ?> + '][name_from]" value="" placeholder="<?php echo $entry_replace_from; ?>" class="form-control" /></td>';
    html += '  <td class="text-left">';

    html += '<input type="text" name="unixml_<?php echo $feed; ?>_replace_name[' + replace_name_row<?php echo $feed; ?> + '][name_to]" value="" placeholder="<?php echo $entry_replace_to; ?>" class="form-control" />';

    html += '  </td>';
    html += '  <td class="text-center"><button type="button" onclick="$(\'#replace_name-row' + replace_name_row<?php echo $feed; ?> + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#unixml_<?php echo $feed; ?>_replace_names tbody').append(html);

    replace_name_row<?php echo $feed; ?>++;
  }
  //replace

  //attribute
  var attribute_row<?php echo $feed; ?> = <?php echo ${'attribute_row' . $feed}; ?>;
  function addAttribute<?php echo $feed; ?>() {
    html  = '<tr id="attribute-row' + attribute_row<?php echo $feed; ?> + '">';
    html += '  <td class="text-left" style="width: 40%;"><input type="text" name="unixml_<?php echo $feed; ?>_attributes[' + attribute_row<?php echo $feed; ?> + '][attribute_name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="unixml_<?php echo $feed; ?>_attributes[' + attribute_row<?php echo $feed; ?> + '][attribute_id]" value="" /></td>';
    html += '  <td class="text-left">';

    html += '<input type="text" name="unixml_<?php echo $feed; ?>_attributes[' + attribute_row<?php echo $feed; ?> + '][xml_name]" value="" placeholder="<?php echo $entry_attribute_xml; ?>" class="form-control" />';

    html += '  </td>';
    html += '  <td class="text-center"><button type="button" onclick="$(\'#attribute-row' + attribute_row<?php echo $feed; ?> + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#unixml_<?php echo $feed; ?>_attributes tbody').append(html);

    attributeautocomplete<?php echo $feed; ?>(attribute_row<?php echo $feed; ?>);

    attribute_row<?php echo $feed; ?>++;
  }

  function attributeautocomplete<?php echo $feed; ?>(attribute_row_feed) {
    $('input[name=\'unixml_<?php echo $feed; ?>_attributes[' + attribute_row_feed + '][attribute_name]\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=<?php echo $path; ?>/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item.name,
                value: item.attribute_id
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name="unixml_<?php echo $feed; ?>_attributes[' + attribute_row_feed + '][attribute_name]"]').val(item['label']);
        $('input[name="unixml_<?php echo $feed; ?>_attributes[' + attribute_row_feed + '][attribute_id]"]').val(item['value']);
      }
    });
  }

  $('#unixml_<?php echo $feed; ?>_attributes tbody tr').each(function(index, element) {
    attributeautocomplete<?php echo $feed; ?>(index);
  });
  //attribute

  //param
  var param_row<?php echo $feed; ?> = <?php echo ${'param_row' . $feed}; ?>;
  function addParam<?php echo $feed; ?>() {
    html  = '<tr id="param-row' + param_row<?php echo $feed; ?> + '">';
    html += '  <td class="text-left" style="width: 40%;"><input type="text" name="unixml_<?php echo $feed; ?>_additional_params[' + param_row<?php echo $feed; ?> + '][param_name]" value="" placeholder="<?php echo $entry_param_name; ?>" class="form-control" /></td>';
    html += '  <td class="text-left">';

    html += '<input type="text" name="unixml_<?php echo $feed; ?>_additional_params[' + param_row<?php echo $feed; ?> + '][param_text]" value="" placeholder="<?php echo $entry_param_text; ?>" class="form-control" />';

    html += '  </td>';
    html += '  <td class="text-center"><button type="button" onclick="$(\'#param-row' + param_row<?php echo $feed; ?> + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#unixml_<?php echo $feed; ?>_additional_params tbody').append(html);

    param_row<?php echo $feed; ?>++;
  }
  //param

  //category_match
  var category_match_row<?php echo $feed; ?> = <?php echo ${'category_match_row' . $feed}; ?>;

  function addCategoryMatch<?php echo $feed; ?>() {
    html  = '<tr id="category_match_row' + category_match_row<?php echo $feed; ?> + '">';
    html += '  <td class="text-left" style="width: 22%;"><input type="text" name="unixml_<?php echo $feed; ?>_category_match[' + category_match_row<?php echo $feed; ?> + '][category_name]" value="" class="form-control" /><input type="hidden" name="unixml_<?php echo $feed; ?>_category_match[' + category_match_row<?php echo $feed; ?> + '][category_id]" value="" /></td>';
    html += '  <td class="text-left" style="width: 22%;">';
    html += '   <input type="text" name="unixml_<?php echo $feed; ?>_category_match[' + category_match_row<?php echo $feed; ?> + '][xml_name]" value=""  class="form-control" />';
    html += '  </td>';
    html += '  <td class="text-left" style="width: 13%;">';
    html += '   <input type="text" name="unixml_<?php echo $feed; ?>_category_match[' + category_match_row<?php echo $feed; ?> + '][markup]" value="" placeholder="Наценка на товары категори" class="form-control" />';
    html += '  </td>';
    html += '  <td class="text-left" style="width: 41%;">';
    html += '   <textarea name="unixml_<?php echo $feed; ?>_category_match[' + category_match_row<?php echo $feed; ?> + '][custom]" value="" placeholder="Теги для товаров этой категории" class="form-control" ></textarea>';
    html += '  </td>';
    html += '  <td class="text-center" style="width: 2%;"><button type="button" onclick="$(\'#category_match_row' + category_match_row<?php echo $feed; ?> + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#unixml_<?php echo $feed; ?>_category_match tbody').append(html);

    categoryautocomplete<?php echo $feed; ?>(category_match_row<?php echo $feed; ?>);
    <?php if($feed == "google"){ //special for google categories ?>
      googleautocomplete(category_match_row<?php echo $feed; ?>);
    <?php } ?>

    <?php if($feed == "facebook"){ //special for facebook categories ?>
      facebookautocomplete(category_match_row<?php echo $feed; ?>);
    <?php } ?>

    <?php if($feed == "kidstaff"){ //special for kidstaff categories ?>
      kidstaffautocomplete(category_match_row<?php echo $feed; ?>);
    <?php } ?>

    category_match_row<?php echo $feed; ?>++;
  }

  function categoryautocomplete<?php echo $feed; ?>(category_match_row_feed) {
    $('input[name=\'unixml_<?php echo $feed; ?>_category_match[' + category_match_row_feed + '][category_name]\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=<?php echo $path; ?>/autocomplete_category&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item.name,
                value: item.category_id
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name=\'unixml_<?php echo $feed; ?>_category_match[' + category_match_row_feed + '][category_name]\']').val(item['label']);
        $('input[name=\'unixml_<?php echo $feed; ?>_category_match[' + category_match_row_feed + '][category_id]\']').val(item['value']);
      }
    });
  }

  $('#unixml_<?php echo $feed; ?>_category_match tbody tr').each(function(index, element) {
    categoryautocomplete<?php echo $feed; ?>(index);
  });
  //category_match

</script>
<!-- --tab-<?php echo $feed; ?> -->
<?php } ?>

<!--for new facebook-->
<script>
function facebookautocomplete(category_match_row_feed) {
  $('input[name=\'unixml_facebook_category_match[' + category_match_row_feed + '][xml_name]\']').attr('placeholder', 'Вводите название категории google');
  $('input[name=\'unixml_facebook_category_match[' + category_match_row_feed + '][xml_name]\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=<?php echo $path; ?>/googleCategory&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.category_id
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'unixml_facebook_category_match[' + category_match_row_feed + '][xml_name]\']').val(item['label']);
    }
  });
}

$('#unixml_facebook_category_match tbody tr').each(function(index, element) {
  facebookautocomplete(index);
});
</script>
<!--/for new facebook-->

<!--for google merchant-->
<script>
function googleautocomplete(category_match_row_feed) {
  $('input[name=\'unixml_google_category_match[' + category_match_row_feed + '][xml_name]\']').attr('placeholder', 'Вводите название категории google');
  $('input[name=\'unixml_google_category_match[' + category_match_row_feed + '][xml_name]\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=<?php echo $path; ?>/googleCategory&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.category_id
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'unixml_google_category_match[' + category_match_row_feed + '][xml_name]\']').val(item['label']);
    }
  });
}

$('#unixml_google_category_match tbody tr').each(function(index, element) {
  googleautocomplete(index);
});
</script>
<!--/for google merchant-->

<!--for kidstaff-->
<script>
function kidstaffautocomplete(category_match_row_feed) {
  $('input[name=\'unixml_kidstaff_category_match[' + category_match_row_feed + '][xml_name]\']').attr('placeholder', 'Вводите название категории kidstaff');
  $('input[name=\'unixml_kidstaff_category_match[' + category_match_row_feed + '][xml_name]\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=<?php echo $path; ?>/kidstaffCategory&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.category_id
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'unixml_kidstaff_category_match[' + category_match_row_feed + '][xml_name]\']').val(item['label']);
    }
  });
}

$('#unixml_kidstaff_category_match tbody tr').each(function(index, element) {
  kidstaffautocomplete(index);
});
</script>
<!--/for kidstaff-->

<script>
  $(document).ready(function(){
    $('.success').animate({opacity: 0}, 3000);
    <?php if($unixml_active_tab){ ?>
      $('a[href="<?php echo $unixml_active_tab; ?>"]').click();
    <?php } ?>
  });
</script>

<div class="modal fade" id="uxmLong" tabindex="-1" role="dialog" aria-labelledby="uxmLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="uxmLongTitle">Доступность полей базы данных</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3>В выгрузке можно использовать:</h3>
        <div class="row">
          <div class="col-sm-6">
            <h4>Таблица product</h4>
            <ul>
              <?php foreach($fields_product as $field){ ?>
                <li>((<?php echo $field; ?>))</li>
              <?php } ?>
            </ul>
          </div>
          <div class="col-sm-6">
            <h4>Таблица product_description</h4>
            <ul>
              <?php foreach($fields_product_description as $field){ ?>
                <li>((<?php echo $field; ?>))</li>
              <?php } ?>
            </ul>
            <h4>Массив данных товара (с уже примененными заменами и генерациями)</h4>
            <ul>
              <li>{product_id} - id товара</li>
              <li>{name} - название</li>
              <li>{url} - ссылка</li>
              <li>{price} - цена</li>
              <li>{special} - акция</li>
              <li>{image} - фото</li>
              <li>{category} - категория</li>
              <li>{manufacturer} - бренд</li>
              <li>{quantity} - количество</li>
              <li>{description} - описание</li>
              <li>и другие поля которые где-то указаны в настройках модуля. Например в генерации названия если указали location то поле будет в массиве товаров</li>
            </ul>
          </div>
        </div>

        <p>Поле которого не будет в базе - не выводится</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="uxmShort" tabindex="-1" role="dialog" aria-labelledby="uxmShortTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="uxmShortTitle">Доступность полей базы данных</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3>Можно использовать поля:</h3>
        <div class="row">
          <div class="col-sm-6">
            <h4>Таблица product</h4>
            <ul>
              <?php foreach($fields_product as $field){ ?>
                <li><?php echo $field; ?></li>
              <?php } ?>
            </ul>
          </div>
          <div class="col-sm-6">
            <h4>Таблица product_description</h4>
            <ul>
              <?php foreach($fields_product_description as $field){ ?>
                <li><?php echo $field; ?></li>
              <?php } ?>
            </ul>
          </div>
        </div>

        <p>Поле которого не будет в базе - не будет забираться</p>
      </div>
    </div>
  </div>
</div>

<?php } ?>


<div class="modal fade" id="import_to_markup" tabindex="-1" role="dialog" aria-labelledby="import_to_markupTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="import_to_markupTitle">Импорт товаров в группу скидки</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3>Вставьте товары через разделитель:</h3>
        <input type="hidden" id="import_feed">
        <input type="hidden" id="import_row">
        <textarea id="import_textarea" class="form-control" style="height:200px;"></textarea><br>
        <div class="row">
          <div class="col-sm-6">Разделитель: <input id="import_serapator" class="form-control" style="display:inline-block;" placeholder="Если с новой строки - не заполняйте"></div>
          <div class="col-sm-6">Значение это поле в таблице product: <input id="import_field" class="form-control" style="display:inline-block;" placeholder="Напр: model sku product_id и т.п"></div>
        </div>
        <div id="import_stat" style="color:green;height:20px;line-height:20px;"></div>
        <div class="row">
          <div class="col-sm-6" style="padding-top:10px;">
            <input type="checkbox" style="position:absolute;" checked="checked" data-feed="price" class="checkbox_exp" id="clear_old" name="clear_old" value="price">
            <label for="clear_old">Перезаписать товары</label>
          </div>
          <div class="col-sm-6"><button id="import_start" style="width:100%;" class="btn btn-success"><i class="fa fa-play" aria-hidden="true"></i> Поехали!</button></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).on('click', '.importMarkup', function(e){
    e.preventDefault();
    $('#import_feed').val($(this).data('feed'));
    $('#import_row').val($(this).data('row'));
    $('#import_to_markup').modal('show');
  });

  $(document).on('click', '#import_start', function(){
    $('#import_stat').html('Импортирую...');
    feed = $('#import_feed').val();
    row = $('#import_row').val();
    $.ajax({
      url: 'index.php?route=<?php echo $path; ?>/import_product&token=<?php echo $token; ?>',
      method: 'post',
      dataType: 'json',
      data: {'products' : $('#import_textarea').val(), 'import_serapator' : $('#import_serapator').val(), 'import_field' : $('#import_field').val()},
      success: function(json) {
        if(json['error']){
          $('#import_stat').html('<div style="color:red;">' + json['error'] + '</div>');
        }else{
          if(!json['success']){
            $('#import_stat').html('<div style="color:red;">К сожалению ничего не импортировано из ' + json['count'] + ' распознанных :(</div>');
          }else{
            $('#import_stat').html('Успешно импортировано товаров: ' + json['success'] + ' из ' + json['count'] + ' распознанных. Можно закрыть это окно.');
          }
          if(json['products']){
            if($('#clear_old').prop('checked')){
              alert('#unixml_' + feed + '_markup_products' + row);
              $('#unixml_' + feed + '_markup_products' + row).html("");
            }
            $.each(json['products'], function (index, value) {
              $('#unixml_' + feed + '_markup_products' + row + '-' + value['product_id']).remove();
              $('#unixml_' + feed + '_markup_products' + row).append('<div id="unixml_' + feed + '_markup_products' + row + '-' + value['product_id'] + '"><i class="fa fa-minus-circle"></i> ' + value['name'] + '<input type="hidden" name="unixml_' + feed + '_product_markup[' + row + '][products][]" value="' + value['product_id'] + '" /></div>');
            });
          }
        }
      }
    });
  });

  $('.category_search').keyup(function(){
    feed = $(this).data('feed');
    search = $(this).val();
    $.ajax({
      url: 'index.php?route=<?php echo $path; ?>/getCategories&token=<?php echo $token; ?>&search=' + search,
      method: 'post',
      dataType: 'json',
      success: function(json) {
        showed = 0;
        $('#unixml_' + feed + '_categories>div').hide();
        $.each(json, function (index, value) {
          showed++;
          $('#unixml_' + feed + '_categories>div[data-id="' + value + '"]').show();
        });
        $('#' + feed + '_category_counter').text(showed);
      }
    });
  });

  $(document).on('click', '.upload_file', function() {
    feed = $(this).data('feed');
  	$('#form-upload').remove();
  	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
  	$('#form-upload input[name=\'file\']').trigger('click');
  	if (typeof timer != 'undefined') {clearInterval(timer);}
  	timer = setInterval(function() {
  		if ($('#form-upload input[name=\'file\']').val() != '') {
        $('#tab-' + feed + ' .exp-imp .text-right').prepend('<div class="imp-success"><i class="fa fa-hourglass-start" aria-hidden="true"></i> Запускаю процесс<br>импортирования для ' + feed + '...</div>');
  			clearInterval(timer);
  			$.ajax({
  				url: 'index.php?route=<?php echo $path; ?>/import_setting&token=<?php echo $token; ?>&feed=' + feed,
  				type: 'post',
  				dataType: 'json',
  				data: new FormData($('#form-upload')[0]),
  				cache: false,
  				contentType: false,
  				processData: false,
  				success: function(json) {
            if(json['error']){
              alert(json['error']);
            }else{
              $('.panel-body').load('index.php?route=<?php echo $path; ?>&token=<?php echo $token; ?>' + ' .panel-body > *', function(){
                $('a[href="#tab-' + feed + '"]').click();
                $('#tab-' + feed + ' .exp-imp .text-right').prepend('<div class="imp-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Импорт успешно завершен!<br>Сохраните настройки.</div>');
                $('#unixml_active_tab').val('#tab-' + feed);
                setTimeout(function(){
                  $('.imp-success').remove();
                }, 5000);
              });
            }
  				}
  			});
  		}
  	}, 500);
  });
</script>
<?php echo $footer; ?>
