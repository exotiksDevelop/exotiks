<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta name="robots" content="none"/>
    <link href="catalog/view/theme/default/stylesheet/cdl_wildberries.css" rel="stylesheet" />
    <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
    <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <title><?php echo $text_supplies; ?></title>
  </head>
  <body>
    <audio id="ok" src="_ok.mp3" preload="auto"></audio>
    <audio id="danger" src="_danger.mp3" preload="auto"></audio>
    <div class="content">
      <div class="panel">
        <input autofocus type="text" class="form-control search" placeholder="<?php echo $text_barcode; ?>" value="" />
        <div class="info"></div>
        <hr>
        <?php echo $text_skaning; ?>
        <div class="count-posting"></div>
        <hr>
        <?php if (!empty($supplies)) { ?>
          <button type="button" class="btn btn-success btn-lg btn-block add-to-supplie"><?php echo $text_add_to_supplies; ?></button>
          <hr>
          <button type="button" class="btn btn-warning btn-lg btn-block clouse-supplie"><?php echo $text_clouse_supplies; ?></button>
        <?php } else { ?>
          <button type="button" class="btn btn-success btn-lg btn-block create-supplie"><?php echo $text_create_supplies; ?></button>
        <?php } ?>
      </div>
      <div class="orders">
        <div class="col-sm-5">
          <form id="wb_order" action="" method="post">
            <table class="table">
              <thead>
                <tr>
                  <th><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                  <th><?php echo $text_packing_orders; ?></th>
                  <th><?php echo $text_hk; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($packing_orders)) { ?>
                  <?php foreach ($packing_orders as $packing_order) { ?>
                    <tr data-tr="<?php echo $packing_order['sticker_bc']; ?>">
                      <td><input type="checkbox" name="selected[]" value="<?php echo $packing_order['wb_order_id']; ?>" data-checkbox="<?php echo $packing_order['sticker_bc']; ?>" /></td>
                      <td><?php echo $packing_order['wb_order_id']; ?></td>
                      <td><?php echo $packing_order['sticker']; ?></td>
                    </tr>
                  <?php } ?>
                <?php } ?>
              </tbody>
            </table>
            <input type="hidden" name="id" value="<?php echo $supplies; ?>">
          </form>
        </div>
        <div class="col-sm-4">
          <table class="table">
            <thead><tr><th>
              <?php if (!empty($supplies)) { ?>
                <?php echo $text_orders_supplies . $supplies; ?>
              <?php } else { ?>
                <?php echo $text_no_supplies; ?>
              <?php } ?>
              </th></tr></thead>
            <tbody>
              <?php if (!empty($supplies_orders['orders'])) { ?>
                <?php foreach ($supplies_orders['orders'] as $supplies_order) { ?>
                  <tr class="find" data-tr="<?php echo $supplies_order['sticker_bc']; ?>" data-supplies="<?php echo $supplies_order['orderId']; ?>">
                    <td><?php echo $supplies_order['orderId'] . ' (' . $supplies_order['sticker'] . ')'; ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="col-sm-3">
          <table class="table"><thead><tr><th><?php echo $text_log; ?></th></tr></thead></table>
          <div class="log"></div>
        </div>
      </div>
    </body>
  </html>
  <script>
    // Создать поставку
    $('.create-supplie').on('click', function() {
    	$.ajax({
    		url: '<?php echo $url_create_supplie; ?>',
    		beforeSend: function() {
    			$('.create-supplie').button('loading');
    		},
    		success: function() {
    		    location.reload();
    		}
    	});
    });

    // Сканирование
    $('.search').each(function() {
      var elem = $(this);
      elem.data('oldVal', elem.val());
      elem.bind("propertychange change click keyup input paste", function(event){
        if (elem.data('oldVal') != elem.val()) {
          elem.data('oldVal', elem.val());
          // Do action
          // текущее время
          var date = new Date();
          if (elem.val().length == 8) {
            // отправление
            if ($("[data-tr=\'" + elem.val() + "\']").text()) {
              posting = $("[data-checkbox=\'" + elem.val() + "\']").val();
            }
            // проверим существования класса find у элемента
            if ($($("[data-tr=\'" + elem.val() + "\']")).hasClass('find')) {
              document.getElementById('danger').play();
              if (!posting) {
                posting = $("[data-tr=\'" + elem.val() + "\']").attr("data-supplies");
              }
              $('div.info').empty();
              $('.info').append('<div class=\"error\">' + posting + ' повтор!</div>');
              $('.log').prepend('<div class=\"error\">[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + posting + ' уже было отсканировано</div>');
              $('.search').val('');
            } else {
              $("[data-tr=\'" + elem.val() + "\']").addClass('find');
              $("[data-checkbox=\'" + elem.val() + "\']").prop('checked', true);
              if ($($("[data-tr=\'" + elem.val() + "\']")).hasClass('find')) {
                var result = true;
              }
              if (result) {
                document.getElementById('ok').play();
                $('div.info').empty();
                $('.info').append('<div class=\"success\">' + posting + '</div>');
                $('.log').prepend('<div class=\"success\">[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] ' + posting + ' ОК</div>');
              } else if (!result) {
                document.getElementById('danger').play();
                $('div.info').empty();
                $('.info').append('<div class=\"error\"> штрих-код ' + elem.val() + ' не найден!</div>');
                $('.log').prepend('<div class=\"error\">[' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + '] штрих-код ' + elem.val() + ' не найден!</div>');
              }
              $('.search').val('');
              // счетчик
              $('div.count-posting').empty();
              $('.count-posting').append($('.find').length);
              if ($('.find').length === $('[data-tr]').length) {
                let timerId = setInterval(() => { document.getElementById('ok').play(); }, 100);
                setTimeout(() => { clearInterval(timerId); }, 2000);
                $('div.count-posting').empty();
                $('.count-posting').prepend('<div class=\"success\">' + $('.find').length + ' из ' + $('[data-tr]').length + '</div>');
              }
            }
          }
        }
      });
    });

    // Добавить к поставке
    $(document).on('click', '.add-to-supplie', function() {
      data = $('#wb_order').serialize();
    	$.ajax({
    		url: '<?php echo $url_add_supplie; ?>&' + data,
    		success: function() {
    			location.reload();
    		},
    		error: function(xhr, ajaxOptions, thrownError) {
    	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    	  }
    	});
    });
    // Закрыть поставку
    $(document).on('click', '.clouse-supplie', function() {
    	$.ajax({
    		url: '<?php echo $url_clouse_supplie; ?>',
    		success: function() {
    			location.reload();
    		},
    		error: function(xhr, ajaxOptions, thrownError) {
    	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    	  }
    	});
    });
  </script>
