<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
  <style>
  .checkbox_exp {position: absolute;z-index: -1;opacity: 0;margin: 10px 0 0 20px;}
  .checkbox_exp + label {position: relative;padding: 0 0 0 60px;cursor: pointer;margin-bottom:11px;}
  .checkbox_exp + label:before {content: '';position: absolute;top: -4px;left: 0;width: 50px;height: 26px;border-radius: 13px;background: #CDD1DA;box-shadow: inset 0 2px 3px rgba(0,0,0,.2);transition: .2s;}
  .checkbox_exp + label:after {content: '';position: absolute;top: -2px;left: 2px;width: 22px;height: 22px;border-radius: 10px;background: #FFF;box-shadow: 0 2px 5px rgba(0,0,0,.3);transition: .2s;}
  .checkbox_exp:checked + label:before {background:#9FD468;}
  .checkbox_exp:checked + label:after {left: 26px;}
  .checkbox_exp:focus + label:before {box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);}
  #succ{background:#fff;z-index:999999;display:none;position:fixed;top:20px;right:20px;padding:20px 30px;font-size:24px;color:green;border:2px solid green;box-shadow:0 0 10px green;}
  .status .btn-danger{margin-left:10px;}
  #stat_label.off{color:red;}
  #off_block{text-align:center;color:red;font-size:20px;}
  .off input[type="text"]{opacity:.4;}
  #load_items tr td{transition: background 2s ease;}
  #load_items tr.new td{background:#FFE4E1;}
  </style>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Вернуться в модули" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <?php if(!$a){ ?>
    <div>
      <?php echo $message; ?>
    </div>
  <?php }else{ ?>
    <div class="container-fluid">
      <div class="panel panel-default form-horizontal">
        <div class="panel-body">
          <div class="form-group">
            <div class="col-sm-2 text-center" style="padding-top:4px;">
              <label for="redirector_status"><strong style="line-height:26px;vertical-align:top;" id="stat_label">Статус</strong></label>
              <input type="checkbox" <?php if ($status) { ?>checked="checked"<?php } ?> class="checkbox_exp" id="redirector_status" name="redirector_status" value="1"/>
              <label for="redirector_status"></label>
            </div>
            <div class="col-sm-7">
              <input type="text" autocomplete="off" placeholder="Для поиска начинайте вводить любую часть ссылки Откуда или Куда" name="search" id="redirector_search" class="form-control" style="border:2px solid #9FD468;">
            </div>
            <div class="col-sm-3">
              <a style="width:100%;" id="add" href="#" class="btn btn-success text-center" style="background:#9FD468;"><i class="fa fa-plus"></i> Добавить 301 редирект</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid" id="main_block" <?php if (!$status) { ?>style="display:none;"<?php } ?>>

      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-main" data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i> Редиректы</a></li>
        <li><a href="#tab-service" data-toggle="tab"><i class="fa fa-download" aria-hidden="true"></i> Импорт/Экспорт</a></li>
        <li><a href="#tab-info" data-toggle="tab"><i class="fa fa-info-circle" aria-hidden="true"></i> Информация</a></li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="tab-main">
          <div id="list_loading">
            <table class="table table-striped table-hover redirect-list">
              <thead>
                <tr>
                  <th class="text-left select_td"scope="col"><?php echo $text_url_from; ?></th>
                  <th class="text-left select_td" scope="col"><?php echo $text_url_to; ?></th>
                  <th class="text-right" scope="col" style="width:150px;">Статус / Удалить</th>
                </tr>
              </thead>
              <tbody id="load_items">
                <?php if($redirects){ ?>
                  <?php foreach($redirects as $key => $redirect){ ?>
                    <tr id="<?php echo $redirect['redirect_id'] ?>" <?php if(!$redirect['status']){ ?>class="off"<?php } ?>>
                      <td class="url_from"><input placeholder="Ссылка с которой перенаправляем (без домена)" class="form-control url-input" id="from-<?php echo $redirect['redirect_id']; ?>" type="text" value="<?php echo $redirect['url_from']; ?>" /></td>
                      <td class="url_to"><input placeholder="Ссылка куда перенаправляем (без домена)" class="form-control url-input" id="to-<?php echo $redirect['redirect_id']; ?>" type="text" value="<?php echo $redirect['url_to']; ?>" /></td>
                      <td class="text-right status">
                        <input id="status-<?php echo $redirect['redirect_id']; ?>" class="status-checkbox checkbox_exp" type="checkbox" <?php if($redirect['status']){ ?>checked="checked"<?php } ?>>
                        <label for="status-<?php echo $redirect['redirect_id']; ?>"></label>
                        <a class="btn btn-danger delete_list"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                      </td>
                    </tr>
                  <?php } ?>
                <?php } ?>

                <?php if($redirect_current >= $redirect_all){ ?>
                  <style>#more_row{display:none;}</style>
                <?php } ?>

              </tbody>
            </table>
            <div class="text-center"><a class="btn btn-success" id="more_row"><?php echo $text_load_more; ?> (<span id="current_all"><?php echo $redirect_current; ?> из <?php echo $redirect_all; ?></span>)</a></div>
          </div>
        </div>
        <div class="tab-pane" id="tab-service">
          <h3 id="import-h3">Импортировать редиректы</h3>
          <small>Каждый редирект с новой строки, разделитель между Откуда и Куда можно настроить ниже</small>
          <textarea style="height:auto;min-height:300px;max-height:800px;" class="form-control" id="import-data" placeholder="Каждый редирект с новой строки"></textarea>
          <div class="row" style="margin-top:15px;">
            <div class="col-sm-6">
              <input type="text" id="import-separ" class="form-control" placeholder="Разделитель между Откуда и Куда, может быть пробел, любой символ или их набор">
            </div>
            <div class="col-sm-6">
              <a style="width:100%;" id="import" href="#" class="btn btn-success text-center" style="background:#9FD468;"><i class="fa fa-sign-in" aria-hidden="true"></i> Поехали!</a>
            </div>
          </div>
          <hr>
          <div style="opacity:.5;">
            <h3>Скачать редиректы в txt формате</h3>
            <small>Функционал будет доступен в следующем обновлении...</small>
          </div>
        </div>
        <div class="tab-pane" id="tab-info">
          <h3>Спасибо за использование модуля 301 редиректов</h3>
          <p>Меня зовут Николай, я автор этого модуля. Выражаю благодарность за покупку дополнения и пусть оно облегчит вам работу с сайтом :)</p>
          <p>По вопросам работы модуля пишите в поддержку info@microdata.pro</p>
          <h3>Как добавить редиректы</h3>
          <p><strong>Вручную.</strong> В первой вкладке достаточно нажать кнопку справа вверху Добавить 301 редирект и в появившейся строке ввести Откуда и Куда. После этого редирект уже будет работать на сайте. Его можно удалить или выключить. Редиректы надо вводить без домена, однако если так хочется можете и с доменом, модуль все поймет.</p>
          <p><strong>Автоматически.</strong> Во второй вкладке в текстовое поле можно ввести/вставить редиректы. Каждый редирект в новой строки. Ниже надо ввести разделитель между Откуда и Куда. Например у нас 100 000 редиректов в формате old_url==new_url в разделитель пишите <b>==</b>. По умолчанию разделитель пробел.</p>
          <h3>Как проверить что все работает</h3>
          <p>Самый простой способ это перейти по ссылке Откуда и должны попасть Куда. Второй вариант более надежный это через специальные сервисы по запросу "проверка ответа сервера".</p>
          <p>Помните что браузер кеширует 301 редирект. Это означает что если поменяли ссылку Куда и заходили по ссылке Откуда то браузер некоторое время будет перекидывать на старый адрес. Но поисковый робот уже увидит новый адрес т.к. у него нет такого кеша.</p>
          <hr><br>
          <?php echo $more_info; ?>
        </div>
      </div>

    </div>
    <div id="off_block" <?php if ($status) { ?>style="display:none;"<?php } ?>>Включите модуль слева вверху :)</div>

  <?php } ?>

</div>

<div id="succ">Сохранено</div>

<script>
  page = 1;

  //Статус модуля
  $('#redirector_status').on('change',function(){
    $('#main_block').slideToggle(300);
    $('#off_block').slideToggle(300);
    $('#stat_label').toggleClass('off');
    $.post("index.php?route=<?php echo $module; ?>/changeStatus&token=<?php echo $token; ?>", {status:this.value}).done(function(data) {succ();});});

  //Поиск
  $('#redirector_search').keyup(function(){
    $('.nav.nav-tabs>li:first-child a').click();
    $('#load_items').load("index.php?route=<?php echo $module; ?>&token=<?php echo $token; ?>&search=" + $(this).val() + " #load_items>*", function(){
      //после загрузки ничего не делаем, но это заготовка
    });
  });

  //Добавить редирект
  $(document).on('click', '#add', function(e){
   e.preventDefault();
    $('.nav.nav-tabs>li:first-child a').click();
    $.ajax({
      url: 'index.php?route=<?php echo $module; ?>/addRow&token=<?php echo $token; ?>',
      dataType: 'json',
      method: 'post',
      success: function(data) {
        html = '<tr class="new" style="display:none;" id="'+data+'">';
          html+= '<td class="url_from"><input placeholder="Ссылка с которой перенаправляем (без домена)" class="form-control url-input" name="url_from" id="from-'+data+'" type="text" value="" /></td>';
          html+= '<td class="url_to"><input placeholder="Ссылка куда перенаправляем (без домена)" class="form-control url-input" id="to-'+data+'" type="text" value="" /></td>';
          html+= '<td class="text-right status" ><input checked="checked" id="status-'+data+'" class="status-checkbox checkbox_exp" type="checkbox"><label for="status-'+data+'"></label> <a class="btn btn-danger delete_list"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';
        html+= '</tr>';
        $('tbody').prepend(html);
        $('.new input[name="url_from"]').focus();
        $('.new').show(300);
        setTimeout(function(){
          $('.new').removeClass('new');
        }, 1000);
        succ();
      }
    });
  });

  //Удалить редирект
  $(document).on('click', '.redirect-list tbody tr .delete_list', function(e){
    e.preventDefault();
    this_parent = $(this).parent().parent();
    $.post("index.php?route=<?php echo $module; ?>/removeRow&token=<?php echo $token; ?>", {"redirect_id":this_parent.attr('id')}).done(function(data) {
      this_parent.remove();
      succ();
    });
  });

  //Сохранение редиректа
  $(document).on('keyup', '.redirect-list tbody tr input', function(){
    updateRow($(this).parent().parent().attr('id'));
  });
  $(document).on('click', '.redirect-list tbody tr .status label', function(){
    id = $(this).parent().parent().attr('id');
    $('#' + id).toggleClass('off');
    updateRow(id);
  });

  //Показать больше
  $(document).on('click', '#more_row', function(){
    page++;
    $('#content').append('<div id="tmp" style="display:none;"></div>');
    $('#tmp').load("index.php?route=<?php echo $module; ?>&token=<?php echo $token; ?>&page=" + page + " #load_items>*", function(){
      $('#load_items').append($('#tmp').html());
      $('#tmp').remove();
    });
  });

  //Обновить редирект
  function updateRow(redirect_id){
    setTimeout(function(){
      $.post("index.php?route=<?php echo $module; ?>/updateRow&token=<?php echo $token; ?>",
        {"redirect_id":redirect_id,"url_from":$('#from-' + redirect_id).val(),"url_to":$('#to-' + redirect_id).val(),"status":$('#status-' + redirect_id).is(":checked")}).done(function(data) {
        succ();
      });
    }, 200);
  }

  //Импортировать редиректы
  $(document).on('click', '#import', function(e){
    e.preventDefault();
    $('#import-h3').html("Импортирую...");
    $.ajax({
      url: 'index.php?route=<?php echo $module; ?>/import&token=<?php echo $token; ?>',
      dataType: 'json',
      data: {'import_data':$('#import-data').val(), 'import_separ':$('#import-separ').val()},
      method: 'post',
      success: function(data) {
        succ();
        if(data != '---'){
          result_text = "<span style='color:#8fbb6c;'>Отлично! Импортировано редиректов: " + data + " :)</span>";
          $('#load_items').load("index.php?route=<?php echo $module; ?>&token=<?php echo $token; ?> #load_items>*", function(){});
          setTimeout(function(){
            $('#import-h3').text("Импортировать редиректы");
          },5000);
        }else{
          result_text = "<span style='color:red;'>Что-то пошло не так :( Проверьте редиректы, разделитель и попробуйте заново!</span>";
        }
        $('#import-h3').html(result_text);
      }
    });
  });

  //Сохранено
  function succ(){$('#succ').show();setTimeout(function(){$("#succ").fadeOut(); },1500);}
</script>

<?php echo $footer; ?>
