<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta name="robots" content="none"/>
    <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
    <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <script src="catalog/view/javascript/jquery/datetimepicker/moment.js"></script>
    <script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <title>Начисления WB</title>
  </head>
  <body>
    <form action="<?php echo $url_statistics; ?>" method="post" enctype="multipart/form-data">
      <div class="col-sm-12" style="margin-top:0px;background-color:#3a0078 !important;padding-top:5px;padding-bottom:5px;">
        <?php if (!empty($warning_message)) { ?>
          <div class="alert alert-danger" role="alert"><?php echo $warning_message; ?></div>
        <?php } ?>
        <div class="col-sm-2">
    			<input name="datefrom" class="form-control date" data-date-format="DD-MM-YYYY" value="<?php echo !empty($date_from) ? $date_from : ''; ?>" />
    		</div>
        <div class="col-sm-2">
    			<input name="dateto" class="form-control date" data-date-format="DD-MM-YYYY" value="<?php echo !empty($date_to) ? $date_to : ''; ?>" />
    		</div>
        <div class="col-sm-2">
          <button type="submit" class="btn btn-primary">Получить</button>
        </div>
        <?php if (!empty($btn_rid)) { ?>
          <?php echo $btn_rid; ?>
        <?php } ?>
        <?php if (!empty($btn_payment)) { ?>
          <?php echo $btn_payment; ?>
        <?php } ?>
        <?php if (!empty($btn_fbo_return)) { ?>
          <?php echo $btn_fbo_return; ?>
        <?php } ?>
        <?php if (!empty($btn_fbo_oc)) { ?>
          <?php echo $btn_fbo_oc; ?>
        <?php } ?>
      </div>
    </form>
    <div class="col-sm-12" style="margin-top:0px;background-color:#3a0078 !important;padding-top:5px;padding-bottom:5px;color:#fff;padding-left:30px;">
      <?php if (!empty($sales)) { ?>
        Продажи: <?php echo $sales; ?>  |  К перечислению за товар без логистики: <?php echo $summa; ?>  |  Комиссии: <?php echo $komissions; ?>  |  Возвраты: <?php echo $returns; ?>  |  Ср.чек: <?php echo $counts; ?>  |  <span style="color:#86e300;">Итого к оплате: <?php echo $pay; ?> (-) хранение</span>  |  <span style="color:#f00;">Ваши затраты: <?php echo $costs; ?> + хранение</span>
      <?php } else { ?>
        Выберите даты начала и конца отчета, чтобы получить информацию по начислениям.
      <?php } ?>
    </div>
    <div class="row"></div>
    <div class="col-sm-12">
      <div class="col-sm-6">
        <table class="table">
          <thead>
            <tr>
              <td>Заказ FBS</td>
              <td>Дата заказа</td>
              <td>№ операции</td>
              <td>Тип</td>
              <td>Цена</td>
              <td>Перечисленно</td>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($fbs_orders)) { ?>
              <?php foreach ($fbs_orders as $fbs_order) { ?>
                <tr<?php echo $fbs_order['doc_type_name'] == 'Возврат' ? ' style="color:red;"' : ''; ?>>
                  <td<?php echo $fbs_order['order_id'] == 'Rid заказа не найден' ? ' style="background-color:red;"' : ''; ?>><?php echo $fbs_order['stat'] ? '<span style="color:#00c500">&#x25CF;</span>' . ' ' . $fbs_order['order_id'] : '<span style="color:#565656">&#x25CB;</span>' . ' ' . $fbs_order['order_id']; ?></td>
                  <td><?php echo $fbs_order['order_dt']; ?></td>
                  <td><?php echo $fbs_order['rrd_id']; ?></td>
                  <td<?php echo $fbs_order['doc_type_name'] == 'Возврат' ? ' style="color:red;"' : ''; ?>><?php echo $fbs_order['doc_type_name']; ?></td>
                  <td><?php echo $fbs_order['retail_amount']; ?></td>
                  <td><?php echo $fbs_order['ppvz_for_pay']; ?></td>
                </tr>
              <?php } ?>
            <?php } else { ?>
              <tr><td colspan="6">Нет FBS заказов</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="col-sm-6">
        <table class="table">
          <thead>
            <tr>
              <td>Заказ FBO</td>
              <td>Дата заказа</td>
              <td>№ операции</td>
              <td>Тип</td>
              <td>Цена</td>
              <td>Перечисленно</td>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($fbo_orders)) { ?>
              <?php foreach ($fbo_orders as $fbo_order) { ?>
                <tr>
                  <td><?php echo $fbo_order['stat'] ? '<span style="color:#00c500">&#x25CF;</span>' . ' ' . $fbo_order['order_id'] : '<span style="color:#565656">&#x25CB;</span>' . ' ' . $fbo_order['order_id']; ?></td>
                  <td><?php echo $fbo_order['order_dt']; ?></td>
                  <td><?php echo $fbo_order['rrd_id']; ?></td>
                  <td<?php echo $fbo_order['doc_type_name'] == 'Возврат' ? ' style="color:red;"' : ''; ?>><?php echo $fbo_order['doc_type_name']; ?></td>
                  <td><?php echo $fbo_order['retail_amount']; ?></td>
                  <td><?php echo $fbo_order['ppvz_for_pay']; ?></td>
                </tr>
              <?php } ?>
            <?php } else { ?>
              <tr><td colspan="6">Нет FBO заказов</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
<script>
  $('.date').datetimepicker({
    pickTime: false
  });

  // Обработать заказы в МС
  $('.payment').on('click', function() {
    var rids = '<?php echo json_encode($rid_orders); ?>';
    var date = $('input[name=\'dateto\']').val();
  	$.ajax({
  		url: '<?php echo $url_payment; ?>&date=' + date,
      contentType: 'application/json',
  		type: 'post',
  		data: rids,
      beforeSend: function() {
        $('.payment').button('loading');
        alert('Процесс выполняется на сервере и может занимать продолжительное время. Закройте это уведомление и ожидайте окончания.');
      },
  		success: function(html) {
        $('.payment').button('reset');
        alert(html);
  		},
  		error: function(xhr, ajaxOptions, thrownError) {
  	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  	  }
  	});
  });

  // Создать FBO заказы в OC
  $('.fbo').on('click', function() {
    var orders = '<?php echo json_encode($fbo_new_orders); ?>';
  	$.ajax({
  		url: '<?php echo $url_create_fbo; ?>',
      contentType: 'application/json',
  		type: 'post',
      data: orders,
      beforeSend: function() {
        $('.fbo').button('loading');
      },
  		success: function() {
        $('.fbo').button('reset');
        alert('Перезагрузите отчет');
  		},
  		error: function(xhr, ajaxOptions, thrownError) {
  	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  	  }
  	});
  });
  // Обработать FBO возвраты
  $('.fbo-return').on('click', function() {
    var orders = '<?php echo json_encode($fbo_return); ?>';
  	$.ajax({
  		url: '<?php echo $url_fbo_retuns; ?>',
      contentType: 'application/json',
  		type: 'post',
      data: orders,
      beforeSend: function() {
        $('.fbo-return').button('loading');
      },
  		success: function(html) {
        $('.fbo-return').button('reset');
        alert('Получено возвратов: ' + html + '\r\nПерезагрузите отчет');
  		},
  		error: function(xhr, ajaxOptions, thrownError) {
  	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  	  }
  	});
  });
</script>
