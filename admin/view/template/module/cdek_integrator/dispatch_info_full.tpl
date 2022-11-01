<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $sync; ?>" class="btn btn-primary sync"><?php echo $button_sync; ?></a>
        <a href="<?php echo $cancel; ?>" class="btn btn-primary"><?php echo $button_cancel; ?></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <span class="help sync">Последняя cинхронизация: <strong><?php echo $last_exchange; ?></strong></span><br>
        <table class="form table">
          <tbody>
            <tr>
              <td>Номер заказа</td>
              <td>
                <?php echo $dispatch_info['order_id']; ?>
              </td>
            </tr>
            <tr>
              <td>Квитанция</td>
              <td class="pdf-invoice">
                <div class="pdf-invoice-wrapper">
                  <?php if (empty($pdf)) { ?>
                  <a href="<?php echo $print; ?>" class="load"><?php echo $button_print; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $pdf; ?>" target="_blank">Смотреть</a><a href="<?php echo $print; ?>" class="load" title="Загрузить повторно"><img src="view/image/cdek_integrator/reload.png" /></a>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td>Дата отгрузки</td>
              <td><?php echo $date; ?></td>
            </tr>
            <tr>
              <td>Номер отправления СДЭК</td>
              <td><?php echo $dispatch_info['number']; ?></td>
            </tr>
            <tr>
              <td>Номер акта приема-передачи</td>
              <td>
                <?php if ($dispatch_info['act_number']) { ?>
                <?php echo $dispatch_info['act_number']; ?>
                <?php } else { ?>
                <a class="js sync-row">Синхронизовать</a>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td>Тариф</td>
              <td><?php echo $tariff['title']; ?></td>
            </tr>
            <?php if ($dispatch_info['seller_name']) { ?>
            <tr>
              <td>Истинный продавец<span class="help">Используется при печати заказов для отображения настоящего продавца товара, либо торгового названия</span></td>
              <td><?php echo $dispatch_info['seller_name']; ?></td>
            </tr>
            <?php } ?>
            <?php if (!empty($delivery_recipient_cost)) { ?>
            <tr>
              <td>Дополнительный сбор за доставку, который ИМ берет с получателя</td>
              <td><?php echo $delivery_recipient_cost; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td>Итого</td>
              <td>
                <?php if ($delivery_cost) { ?>
                <strong><?php echo $delivery_cost; ?></strong>
                <?php if (!empty($delivery_last_change)) { ?>
                <span class="help">
                <?php echo $delivery_last_change; ?>
                </span>
                <?php } ?>
                <?php } else { ?>
                <a class="js sync-row">Синхронизовать</a>
                <?php } ?>
              </td>
            </tr>
            <tr class="item-slider row-status">
              <td>Статус</td>
              <td>
                <strong><?php echo $status['title']; ?></strong>&nbsp;&nbsp;<a class="js" data-title="История изменений">История изменений</a>
                <div class="reason-status"><?php if (!empty($reason_status)) echo $reason_status; ?></div>
                <span class="help"><?php echo $status['date']; ?></span>
              </td>
            </tr>
            <tr class="item-slider children row-status" style="display: none">
              <td></td>
              <td>
                <br />
                <table class="list table">
                  <thead>
                    <tr>
                      <td class="left">id</td>
                      <td class="left">Статус</td>
                      <td class="left">Дата</td>
                      <td class="left">Город</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($status_history as $row => $status_info) { ?>
                    <tr <?php if ($row == 0) echo 'class="row-status"'; ?>>
                      <td class="left"><?php echo $status_info['status_id']; ?></td>
                      <td class="left">
                        <?php echo $status_info['name']; ?>
                        <?php if ($status_info['description']) { ?>
                        <span class="help"><?php echo $status_info['description']; ?></span>
                        <?php } ?>
                      </td>
                      <td class="left"><?php echo $status_info['date']; ?></td>
                      <td class="left"><?php echo $status_info['city']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <?php if (!empty($delay_history)) { ?>
            <tr class="item-slider row-warning">
              <td>Причина задержки</td>
              <td>
                <?php if (!empty($delay)) { ?>
                <strong><?php echo $delay['title']; ?></strong><?php if (count($delay_history) > 1) { ?> <a class="js" data-title="История причин задержек">История причин задержек</a><?php } ?><span class="help"><?php echo $delay['date']; ?></span>
                <?php } else { ?>
                <a class="js" data-title="История изменений">История причин задержек</a>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <?php if (count($delay_history) > 1) { ?>
            <tr class="item-slider children row-warning" style="display: none">
              <td></td>
              <td>
                <br />
                <table class="list">
                  <thead>
                    <tr>
                      <td class="left">id</td>
                      <td class="left">Статус</td>
                      <td class="left">Дата</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($delay_history as $row => $delay_info) { ?>
                    <tr>
                      <td class="left"><?php echo $delay_info['delay_id']; ?></td>
                      <td class="left"><?php echo $delay_info['name']; ?></td>
                      <td class="left"><?php echo $delay_info['date']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <?php } ?>
            <tr>
              <td colspan="2">
                <h2>Отправитель</h2>
              </td>
            </tr>
            <tr>
              <td>Город</td>
              <td><?php echo $dispatch_info['city_name']; ?></td>
            </tr>
            <tr>
              <td>Почтовый индекс</td>
              <td>
                <?php if ($dispatch_info['city_postcode']) { ?>
                <?php echo $dispatch_info['city_postcode']; ?>
                <?php } else { ?>
                <a class="js sync-row">Синхронизовать</a>
                <?php } ?>
              </td>
            </tr>
            <?php if (!empty($courier)) { ?>
            <tr class="item-slider row-status">
              <td>Ожидание курьера</td>
              <td><?php echo $courier['date']; ?> c <?php echo $courier['time_beg']; ?> до <?php echo $courier['time_end']; ?> <a class="js">Подробнее</a></td>
            </tr>
            <tr class="item-slider children row-status" style="display: none">
              <td></td>
              <td>
                <?php if ($courier['lunch_beg'] && $courier['lunch_end']) { ?>
                Обед: с <?php echo $courier['lunch_beg']; ?> по <?php echo $courier['lunch_end']?><br />
                <?php } ?>
                <br />
                Город: <?php echo $courier['city_name']; ?><br />
                Улица: <?php echo $courier['address_street']; ?><br />
                Дом, корпус, строение: <?php echo $courier['address_house']; ?><br />
                Квартира/Офис: <?php echo $courier['address_flat']; ?><br />
                <br />
                Отправитель: <?php echo $courier['sender_name']; ?><br />
                Телефон: <?php echo $courier['send_phone']; ?><br />
                <?php if ($courier['comment'] != '') { ?>
                <br />
                Комментарий: <?php echo $courier['comment']; ?>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <tr>
              <td colspan="2">
                <h2>Получатель</h2>
              </td>
            </tr>
            <tr>
              <td>ФИО</td>
              <td><?php echo $dispatch_info['recipient_name']; ?></td>
            </tr>
            <tr>
              <td>Телефон</td>
              <td><?php echo $dispatch_info['phone']; ?></td>
            </tr>
            <?php if ($dispatch_info['recipient_email']) { ?>
            <tr>
              <td>Электронный адрес</td>
              <td><?php echo $dispatch_info['recipient_email']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td>Город</td>
              <td><?php echo $dispatch_info['recipient_city_name']; ?></td>
            </tr>
            <tr>
              <td>Почтовый индекс</td>
              <td>
                <?php if ($dispatch_info['recipient_city_postcode']) { ?>
                <?php echo $dispatch_info['recipient_city_postcode']; ?>
                <?php } else { ?>
                <a class="js sync-row">Синхронизовать</a>
                <?php } ?>
              </td>
            </tr>
            <?php if (!empty($tariff['mode_id'])) { ?>
            <?php if (in_array((int)$tariff['mode_id'], array(1,3))) { ?>
            <tr>
              <td>Улица</td>
              <td><?php echo $dispatch_info['address_street']; ?></td>
            </tr>
            <tr>
              <td>Дом, корпус, строение</td>
              <td><?php echo $dispatch_info['address_house']; ?></td>
            </tr>
            <tr>
              <td>Квартира/Офис</td>
              <td><?php echo $dispatch_info['address_flat']; ?></td>
            </tr>
            <?php } else { ?>
            <?php if (!empty($dispatch_info['pvz_info'])) { ?>
            <tr>
              <td>
                Пункт выдачи
              </td>
              <td>
                <p><?php echo $dispatch_info['pvz_info']['Name']; ?><?php if ($dispatch_info['pvz_info']['x']) { ?> (<a href="http://maps.google.ru/maps?q=<?php echo $dispatch_info['pvz_info']['y']; ?>,<?php echo $dispatch_info['pvz_info']['x']; ?>" target="_blank">на карте</a>)<?php } ?></p>
                <span class="help">
                <strong>Адрес</strong>: <?php echo $dispatch_info['pvz_info']['Address']; ?><br />
                <?php if (!empty($dispatch_info['pvz_info']['Phone']) && trim($dispatch_info['pvz_info']['Phone']) != '-') {?>
                <strong>Телефон</strong>: <?php echo $dispatch_info['pvz_info']['Phone']; ?><br />
                <?php } ?>
                <?php if (!empty( $dispatch_info['pvz_info']['WorkTime']) && trim($dispatch_info['pvz_info']['WorkTime']) != '-') { ?>
                <strong>Режим работы</strong>: <?php echo $dispatch_info['pvz_info']['WorkTime']; ?>
                <?php } ?>
                
                </span>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if (!empty($cod) || !empty($cod_fact)) { ?>
            <tr>
              <td colspan="2">
                <h2>Наложенный платеж</h2>
              </td>
            </tr>
            <tr>
              <td>Валюта</td>
              <td><?php echo $currency_cod; ?></td>
            </tr>
            
            
            <?php if (!empty($cod)) { ?>
            <tr>
              <td>Заявленная сумма</td>
              <td><?php echo $cod; ?></td>
            </tr>
            <?php } ?>
            <?php if (!empty($cod_fact)) { ?>
            <tr>
              <td>Сумма, которую взяли с покупателя</td>
              <td><?php echo $cod; ?></td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <?php if (!empty($schedule)) { ?>
        <table class="table">
          <tbody>
            <tr>
              <td colspan="2">
                <h2>Расписание времени доставки</h2>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="form table">
          <tbody>
            <?php foreach ($schedule as $row => $attempt_info) { ?>
            <tr class="item-slider schedule row-<?php echo ($attempt_info['delay'] != '' ? 'warning' : 'status'); ?>">
              <td>
                <strong>#<?php echo ++$row; ?></strong>
              </td>
              <td>
                <span class="month"><?php echo $attempt_info['date']; ?></span> c <?php echo $attempt_info['time_beg']; ?> до <?php echo $attempt_info['time_end']; ?><?php if ($attempt_info['delay'] != '') echo ' (задержан)'; ?><?php if ($attempt_info['show_more']) { ?> <a class="js">Подробнее</a><?php } ?>
              </td>
            </tr>
            <?php if ($attempt_info['show_more']) { ?>
            <tr class="item-slider schedule children hidden row-<?php echo ($attempt_info['delay'] != '' ? 'warning' : 'status'); ?>">
              <td></td>
              <td>
                <?php if ($attempt_info['delay'] != '') { ?>
                Причина задержки: <strong><?php echo $attempt_info['delay']; ?></strong><br /><br />
                <?php } ?>
                <?php if (!empty($attempt_info['recipient_info'])) { ?>
                  <?php if (!empty($attempt_info['recipient_info']['name'])) { ?>
                  Новый получатель: <?php echo $attempt_info['recipient_info']['name'] ?><br />
                  <?php } ?>
                  <?php if (!empty($attempt_info['recipient_info']['phone'])) { ?>
                  Новый телефон получателя: <?php echo $attempt_info['recipient_info']['phone'] ?><br />
                  <?php } ?>
                  <br />                
                <?php } ?>
                <?php if (!empty($tariff['mode_id']) && !empty($attempt_info['address_info'])) { ?>
                  <div class="title-new-address">Новый адрес доставки</div>
                  <?php if (in_array($tariff['mode_id'], array(1,3))) { ?>
                    Улица: <?php echo $attempt_info['address_info']['street']; ?><br />
                    Дом, корпус, строение: <?php echo $attempt_info['address_info']['house']; ?><br />
                    <?php if (!empty($attempt_info['address_info']['flat'])) { ?>
                    Квартира/Офис: <?php echo $attempt_info['address_info']['flat']; ?><br />
                    <?php } ?>
                    
                  <?php } elseif (isset($attempt_info['address_info']['pvz_info'])) { ?>
                  <?php $pvz_info = $attempt_info['address_info']['pvz_info']; ?>
                  <div class="attempt-pvz-wrapper">
                    <div class="attempt-pvz-label">ПВЗ:</div>
                    <div class="attempt-pvz-value">
                      <?php echo $pvz_info['Address']; ?>
                      <?php if ((!empty($pvz_info['WorkTime']) && trim($pvz_info['WorkTime']) != '-') || (!empty($pvz_info['Phone']) && trim($pvz_info['Phone']) != '-')) { ?>
                      <span class="help">
                        <?php if (!empty($pvz_info['WorkTime']) && trim($pvz_info['WorkTime']) != '-') { ?>
                        <?php echo 'Режим работы: <strong>' . $pvz_info['WorkTime'] . '</strong><br />'; ?>
                        <?php } ?>
                        <?php if (!empty($pvz_info['Phone']) && trim($pvz_info['Phone']) != '-') {?>
                        <?php echo 'Телефон: <strong>' . $pvz_info['Phone'] . '</strong>'; ?>
                        <?php } ?>
                      </span>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  <br />
                <?php } ?>
                <?php if ($attempt_info['comment'] != '') { ?>
                Комментарий: <?php echo $attempt_info['comment']; ?>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
        <?php if (!empty($call_history)) { ?>
        <table class="table">
          <tbody>
            <tr>
              <td colspan="2">
                <h2>История прозвона получателя</h2>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="form table">
          <tbody>
            <?php if (!empty($call_history['good'])) { ?>
            <tr class="item-slider row-status">
              <td>История удачных прозвонов</td>
              <td><a class="js" data-title="Показать">Показать</a></td>
            </tr>
            <tr class="item-slider children row-status" style="display: none">
              <td></td>
              <td>
                <br />
                <table class="list">
                  <thead>
                    <tr>
                      <td class="left">Дата прозвона</td>
                      <td class="left">Дата, на которую договорились о доставке/самозаборе</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($call_history['good'] as $row => $call_history_info) { ?>
                    <tr class="row-status">
                      <td class="left"><?php echo $call_history_info['date']; ?></td>
                      <td class="left"><?php echo $call_history_info['date_deliv']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <?php } ?>
            <?php if (!empty($call_history['fail'])) { ?>
            <tr class="item-slider row-status">
              <td>История неудачных прозвонов</td>
              <td><a class="js" data-title="Показать">Показать</a></td>
            </tr>
            <tr class="item-slider children row-status" style="display: none">
              <td></td>
              <td>
                <br />
                <table class="list">
                  <thead>
                    <tr>
                      <td class="left">id</td>
                      <td class="left">Дата прозвона</td>
                      <td class="left">Причина неудачного прозвона</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($call_history['fail'] as $row => $call_history_info) { ?>
                    <tr class="row-status">
                      <td class="left"><?php echo $call_history_info['fail_id']; ?></td>
                      <td class="left"><?php echo $call_history_info['date']; ?></td>
                      <td class="left"><?php echo $call_history_info['description']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <?php } ?>
            <?php if (!empty($call_history['delay'])) { ?>
            <tr class="item-slider row-status">
              <td>История переносов прозвона</td>
              <td><a class="js" data-title="Показать">Показать</a></td>
            </tr>
            <tr class="item-slider children row-status" style="display: none">
              <td></td>
              <td>
                <br />
                <table class="list table">
                  <thead>
                    <tr>
                      <td class="left">Дата прозвона</td>
                      <td class="left">Дата, на которую перенесен прозвон</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($call_history['delay'] as $row => $call_history_info) { ?>
                    <tr class="row-status">
                      <td class="left"><?php echo $call_history_info['date']; ?></td>
                      <td class="left"><?php echo $call_history_info['date_next']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
        <table class="table">
          <tbody>
            <tr>
              <td colspan="2">
                <h2>Товары</h2>
              </td>
            </tr>
          </tbody>
        </table>
        <?php foreach ($packages as $package_info) { ?>
        <table class="form table">
          <tbody>
            <tr>
              <td>Валюта вложения</td>
              <td><?php echo $currency; ?></td>
            </tr>
            <tr>
              <td>Номер места</td>
              <td><?php echo $package_info['number']; ?></td>
            </tr>
            <?php if (!empty($package_info['package_size'])) { ?>
            <tr>
              <td>Габариты места<span class="help">(<?php echo $length_class; ?>)</span></td>
              <td><?php echo $package_info['package_size']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td>Вес места<span class="help">(<?php echo $weight_class; ?>)</span></td>
              <td><?php echo $package_info['weight']; ?></td>
            </tr>
          </tbody>
        </table>
        <table class="list table">
          <thead>
            <tr>
              <td class="left">Код товара</td>
              <td class="left">Наименование товара</td>
              <td class="left">Вес единицы<span class="help">(<?php echo $weight_class; ?>)</span></td>
              <td class="left">Цена за единицу</td>
              <td class="left">Оплата с получателя за единицу</td>
              <td class="left">Количество</td>
              <td class="left">Стоимость</td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($package_info['items'] as $package_item) { ?>
            <tr>
              <td class="left"><?php echo $package_item['ware_key']; ?></td>
              <td class="left"><?php echo $package_item['comment']; ?></td>
              <td class="left"><?php echo $package_item['weight']; ?></td>
              <td class="left"><?php echo $package_item['cost']; ?></td>
              <td class="left"><?php echo $package_item['payment']; ?></td>
              <td class="left"><?php echo (int)$package_item['amount']; ?></td>
              <td class="left"><?php echo $package_item['total']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
        <?php if (!empty($add_service)) { ?>
        <table class="table">
          <tbody>
            <tr>
              <td colspan="2">
                <h2>Дополнительные услуги и сборы</h2>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="list table">
          <thead>
            <tr>
              <td class="left">Код услуги</td>
              <td class="left">Название</td>
              <td class="left">Стоимость</td>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($add_service as $service_info) { ?>
          <tr>
            <td class="left"><?php echo $service_info['service_id']; ?></td>
            <td class="left">
              <?php echo $service_info['description']; ?>
              <?php if (!empty($service_info['service_description'])) { ?>
              <span class="help"><?php echo $service_info['service_description']; ?></span>
              <?php } ?>
            </td>
            <td class="left"><?php echo $service_info['price']; ?></td>
          </tr>
          <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left" ><strong><?php echo $add_service_total; ?></strong></td>
            </tr>
          </tfoot>
        </table>
        <?php } ?>
        <table class="form table">
          <tbody>
            <?php if ($dispatch_info['comment']) { ?>
            <tr>
              <td>Комментарий</td>
              <td><?php echo $dispatch_info['comment']; ?></td>
            </tr>
            <?php } ?>
            <tr class="row-status">
              <td>Итого</td>
              <td>
                <?php if ($delivery_cost) { ?>
                <strong><?php echo $delivery_cost; ?></strong>
                <?php if (!empty($delivery_last_change)) { ?>
                <span class="help">
                <?php echo $delivery_last_change; ?>
                </span>
                <?php } ?>
                <?php } else { ?>
                <a class="js sync-row">Синхронизовать</a>
                <?php } ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

$('.item-slider a').click(function(event){
  
  var parent = $(this).closest('tr.item-slider');
  var container = $(parent).next('tr.item-slider.children');
  
  var action = $(container).is(':visible');
  
  if (action) {
    
    $(parent).removeClass('active');
    
    var title = $(this).data('title');
    
    if (!title) title = 'Подробнее';
    
    $(this).text(title);
    
    $(container).hide();
    
  } else {
    
    $(parent).addClass('active');
    $(this).text('Скрыть');
    $(container).show();
    
  }
  
});

$(".pdf-invoice a.load").on('click', function(event){
  
  event.preventDefault();
  
  var self = this;
  var url = $(self).attr('href');
  var context = $(self).closest('.pdf-invoice-wrapper');
  var saved = $(context).html();
  
  if ($(self).data('is_active') == 1) return FALSE;

  $.ajax({
    url: url,
    dataType: "json",
    beforeSend: function(jqXHR, settings){
      
      $('.success, .warning, .attention, .error').remove();
      
      if (!$('.loader', context).length) $(context).html('<img class="loader" src="view/image/cdek_integrator/loader.gif" alt="Загрузка..." title="Загрузка..." />');
      
      $(self).data('is_active', 1);
      
    },
    complete: function(jqXHR, textStatus) {
      $(self).data('is_active', 0);
    },
    success: function(json) {
      
      var type = 'success';
      
      if (json.status == 'error') {
        
        type = 'warning';
        
        $(context).html(saved);
        
      } else {
        $(context).html('<a href="' + json.file + '" target="_blank">Смотреть</a><a href="' + url + '" class="load" title="Загрузить повторно"><img src="view/image/cdek_integrator/reload.png" /></a>');
      }
      
      $('.box').before('<div class="' + type + '">' + json.message + '</div>');
      
    }
  });
  
});

$(".btn.sync").on('click', function(event){
  
  event.preventDefault();
  
  var self = this;
  var url = $(self).attr('href');
  var context = $(self).closest('.box');
  
  if ($(self).data('is_active') == 1) return FALSE;

  $.ajax({
    url: url,
    dataType: "json",
    beforeSend: function(jqXHR, settings){
      
      $('.success, .warning, .attention, .error').remove();
      
      $(self).text('Синхронизация');
      
      if (!$('.loader', context).length) $(self).append('<img class="loader" src="view/image/cdek_integrator/loader.gif" alt="Загрузка..." title="Загрузка..." />');
      
      $(self).data('is_active', 1);
      
    },
    complete: function(jqXHR, textStatus) {
      
      $(self).text('Синхронизовать');
      
      $('.loader', self).remove();
      
      $(self).data('is_active', 0);
      
    },
    success: function(json) {
      
      var type = 'success';
      
      if (json.status == 'error') {
        type = 'warning';
      } else {
        $(context).html(json.content);
      }
      
      $('.box').before('<div class="' + type + '">' + json.message + '</div>');
      
    }
  });

});

$('a.js.sync-row').on('click', function(event){
  $(".btn.sync").trigger('click');
});

//--></script>
<?php echo $footer; ?>