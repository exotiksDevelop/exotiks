<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
       <?php if($is_connected) {?>
					<a data-toggle="tooltip" title="Проверка соединения с Wildberries" class="btn btn-success disabled"><i class="fa fa-check-circle"></i> Соединение установлено</a>
				<?php } else {?>
					<a data-toggle="tooltip" title="Проверка соединения с Wildberries" class="btn btn-danger disabled"><i class="fa fa-ban"></i> Соединение не установлено</a>
				<?php };?>
				<button type="submit" name="submit" value="save_wb_data" form="form-wb" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Сохранить</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
	  <div class="tab-pane">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-wb" class="form-horizontal">
		<ul class="nav nav-tabs" id="amo_tabs">
				<?php if($is_connected) {?>
					<li class="active"><a href="#wb_auth_tab" data-toggle="tab">Авторизация</a></li>	
					<li><a href="#wb_order_tab" data-toggle="tab">Заказы</a></li> 
					<li><a href="#wb_product_tab" data-toggle="tab">Товары</a></li>
					<li><a href="#wb_status_tab" data-toggle="tab">Настройка статусов</a></li>
					<li><a href="#wb_log_tab" data-toggle="tab">Журнал</a></li>
				<?php }?>
					<li <?= !$is_connected ? 'class="active"' : '' ;?> ><a href="#wb_registration_tab" data-toggle="tab">Лицензия</a></li>
					<li><a href="#wb_support-tab" data-toggle="tab">Поддержка</a></li>
			</ul>
			<div class="tab-content">
			<?php if($is_connected) : ?>
				<div class="tab-pane active" id="wb_auth_tab">
					<legend><?php echo $auth_head; ?></legend>
					<?php include DIR_APPLICATION . implode(DIRECTORY_SEPARATOR, ['view', 'template', 'module', 'wildberries_accounts.tpl']);?>
				</div>
				<div class="tab-pane" id="wb_product_tab">
					<legend><?php echo $product_sync_head; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="wb_import_profile"><span data-toggle="tooltip" title="<?php echo $entry_import_profile; ?>"><?php echo $entry_import_profile; ?></span></label>
						<div class="col-sm-2">
							<select name="wb_import_profile" id="wb_import_profile" class="form-control">
								<?php foreach($wb_settings as $index => $setting) : ?>
									<option value="<?= $setting['wb_uuid'];?>" <?= $index === 0 ? 'selected="selected"' : '' ;?> ><?= $setting['wb_store_name'];?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<legend></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="wb_import_compare_field"><span data-toggle="tooltip" title="<?php echo $entry_import_compare_field; ?>"><?php echo $entry_import_compare_field; ?></span></label>
						<div class="col-sm-1">
							<select name="wb_import_compare_field" id="wb_import_compare_field" class="form-control">
								<?php if (empty($wb_import_compare_field)) $wb_import_compare_field = 'sku';?>
								<?php foreach(['sku', 'id', 'model', 'isbn', 'jan', 'ean', 'upc'] as $mark) : ?>
									<option value="<?= $mark?>" <?= $wb_import_compare_field === $mark ? 'selected="selected"' : '' ;?> ><?= $mark?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<legend></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="wb_import_excel_compare_field"><span data-toggle="tooltip" title="<?php echo $entry_import_excel_compare_field; ?>"><?php echo $entry_import_excel_compare_field; ?></span></label>
						<div class="col-sm-1">
							<select name="wb_import_excel_compare_field" id="wb_import_excel_compare_field" class="form-control">
								<?php if (empty($wb_import_excel_compare_field)) $wb_import_excel_compare_field = 5;?>
								<?php foreach(range(1, 10) as $mark) : ?>
									<option value="<?= $mark?>" <?= $wb_import_excel_compare_field == $mark ? 'selected="selected"' : '' ;?> ><?= $mark?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<legend></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="wb_state_attribute"><span data-toggle="tooltip" title="<?php echo $entry_state_attribute; ?>"><?php echo $entry_state_attribute; ?></span></label>
						<div class="col-sm-1">
							<select name="wb_state_attribute" id="wb_state_attribute" class="form-control">
								<?php foreach($attributes as $attr) : ?>
									<option value="<?= $attr['name']; ?>" <?= $wb_state_attribute == $attr['name'] ? 'selected="selected"' : '' ;?> ><?= $attr['name'];?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<legend></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="button-upload"><span data-toggle="tooltip" title="<?php echo $entry_upload_sync_product; ?>"><?php echo $entry_upload_sync_product; ?></span></label>
						<div class="col-sm-2">
							<div class="input-group">
								<button type="button" id="button-upload" data-loading-text="Загрузка..." class="btn btn-primary"><i class="fa fa-upload"></i> Загрузить</button>
							</div>
						</div>
						<div class="col-sm-8">Скачать товары можно <a href="https://suppliers.wildberries.ru/analytics/nomenclatures" target="_blank">здесь</a> </div>
					</div>
					<legend></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-percent-product"><span data-toggle="tooltip" title="<?php echo $entry_percent_product; ?>"><?php echo $entry_percent_product; ?></span></label>
							<div class="col-sm-5">
								<div class="input-group">
									<input type="text" name="wb_percent_product" value="<?php echo $wb_percent_product; ?>" id="entry-percent-product" class="form-control"/>
									<div class="input-group-addon">процентов</div>
								</div>
							</div>
							 <div class="col-sm-5">
							При обновлении товара цена может быть изменена на величину процента указанного в поле</br>
						</div>
					</div>
					<legend></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-upload-product"><span data-toggle="tooltip" title="<?php echo $entry_upload_product; ?>"><?php echo $entry_upload_product; ?></span></label>
							<div class="col-sm-5">
								<div class="input-group">
									<input type="text" name="wb_upload_product" value="<?php echo $wb_upload_product; ?>" id="entry-upload-product" class="form-control"/>
									<div class="input-group-addon">минут</div>
								</div>
							</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-product-cron"><span data-toggle="tooltip" title="<?php echo $entry_product_cron; ?>"><?php echo $entry_product_cron; ?></span></label>
							<div class="col-sm-5">
									<input type="text" name="wb_product_cron" readonly value="<?php echo $wb_product_cron; ?>" placeholder="<?php echo $entry_product_cron; ?>" id="entry-product-cron" class="form-control"/>
							</div>
							<div class="col-sm-5">
							Команда синхронизирует товарные остатки и цену товара. </br>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-sync-cron"><span data-toggle="tooltip" title="<?php echo $entry_sync_cron; ?>"><?php echo $entry_sync_cron; ?></span></label>
							<div class="col-sm-5">
									<input type="text" name="wb_sync_cron" readonly value="<?php echo $wb_sync_cron; ?>" placeholder="<?php echo $entry_sync_cron; ?>" id="entry-sync-cron" class="form-control"/>
							</div>
							<div class="col-sm-5">
							При создании нового товара в спецификации Weldberries не сразу возвращает его ID. </br>Для того чтобы получить ID для синхронизации необходимо настроить регулярное задание на хостинге. </br>Интервал выполнения регулярного задания не от настройки "Интервал обновления товаров в WB". Желательно интервал установить в 1 минуту.</br>
						</div> 
					</div>
				</div>
				<div class="tab-pane" id="wb_order_tab">
					<legend><?php echo $order_sync_head; ?></legend>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-upload-order"><span data-toggle="tooltip" title="<?php echo $entry_upload_order; ?>"><?php echo $entry_upload_order; ?></span></label>
							<div class="col-sm-5">
								<div class="input-group">
									<input type="text" name="wb_upload_order" value="<?php echo $wb_upload_order; ?>" placeholder="<?php echo $entry_upload_order; ?>" id="entry-upload-order" class="form-control"/>
									<div class="input-group-addon">минут</div>
								</div>
							</div>
							<div class="col-sm-5">
							Настройка позволяет вне зависимости от настроек планировщика CRON запланировать регулярность загрузки новых заказов из WB</br>
							</div> 
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-order-cron"><span data-toggle="tooltip" title="<?php echo $entry_order_cron; ?>"><?php echo $entry_order_cron; ?></span></label>
							<div class="col-sm-5">
									<input type="text" name="wb_order_cron" readonly value="<?php echo $wb_order_cron; ?>" placeholder="<?php echo $entry_order_cron; ?>" id="entry-order-cron" class="form-control"/>
							</div>
							<div class="col-sm-5">
							Команда для настройки CRON на хостинге. Желательно поставить интервал 1 минута.</br>
							</div> 
					</div>
					
				</div>
				
				<div class="tab-pane" id="wb_log_tab">
					<legend><?php echo $order_log_head; ?></legend>

					<p class="pull-right"><a onclick="confirm('Вы уверены что хотите очистить?') ? location.href='<?php echo $clear; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i></a></p>

					<div class="form-group">
						<div class="col-sm-12">
							<textarea wrap="off" rows="15" readonly class="form-control"><?php echo $order_log; ?></textarea>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="wb_status_tab">
					<legend><?php echo $order_status_list; ?></legend>
					<div class="form-group">
						<div class="col-sm-12" >
							<table id="status" class="table table-striped table-bordered table-hover">
							  <thead>
								<tr>
								  <td class="text-left"><?php echo $entry_order_status_id; ?></td>
								  <td class="text-left"><?php echo $entry_order_status_name; ?></td>
								  <td></td>
								</tr>
							  </thead>
							  <tbody>
								<?php $status_row = 0; ?>
								<?php foreach ($wb_order_status as $status) { ?>
								<tr id="status-row<?php echo $status_row; ?>">
								  <td class="text-left"><input type="text" name="wb_order_status[<?php echo $status_row; ?>][id]" value="<?php echo $status['id']; ?>" placeholder="ID статуса" id="input-status<?php echo $status_row; ?>" class="form-control" /></td>
								  <td class="text-left"><input type="text" name="wb_order_status[<?php echo $status_row; ?>][name]" value="<?php echo $status['name']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
								  <td class="text-left"><button type="button" onclick="removeStatus(this);" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
								</tr>
								<?php $status_row++; ?>
								<?php } ?>
							  </tbody>
							  <tfoot>
								<tr>
								  <td colspan="2"></td>
								  <td class="text-left"><button type="button" onclick="addStatus();" data-toggle="tooltip" title="<?php echo $button_status_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
								</tr>
							  </tfoot>
							</table>

						</div>
					</div>
				</div>
			<?php endif;?>
				<div class="tab-pane <?= !$is_connected ? 'active' : '' ;?>" id="wb_registration_tab">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-registration-token"><span data-toggle="tooltip" title="<?php echo $entry_registration_token; ?>"><?php echo $entry_registration_token; ?></span></label>
						<div class="col-sm-6">
							<input type="text" name="wb_registration_token" value="<?php echo $wb_registration_token; ?>" id="entry-registration-token" class="form-control"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="entry-registration-email"><span data-toggle="tooltip" title="<?php echo $entry_registration_email; ?>"><?php echo $entry_registration_email; ?></span></label>
						<div class="col-sm-6">
							<input type="text" name="wb_registration_email" value="<?php echo $wb_registration_email; ?>" id="entry-registration-email" class="form-control"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-1">
							<div class="input-group">
								<button type="button" id="button-save" onclick="applyLicense();" class="btn btn-primary"><i class="fa fa-save"></i> Применить</button>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="wb_support-tab">
					<div class="form-group">
						<label class="col-sm-2">Автор модуля</label>
						<div class="col-sm-10">@apipro</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">Сайт</label>
						<div class="col-sm-10"><p><a href="http://api-pro.ru" target="_blank">https://api-pro.ru</a></p></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">Телеграмм</label>
						<div class="col-sm-10"><p><a href="https://t.me/api4pro" target="_blank">https://t.me/api4pro</a></p></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">Email</label>
						<div class="col-sm-10"><p><a href="mailto:api2pro@yandex.ru" target="_blank">api2pro@yandex.ru</a></p></div>
					</div>
				</div>
			</div>
		</form>
		</div>
        
          
        
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js" type="text/javascript"></script>
  <script type="text/javascript"><!--
var status_row = <?php echo isset($status_row) ? $status_row : '0'; ?>;
//$(".phone_mask").mask("79999999999");
function setToken(e, index) {
	e.preventDefault();
	var wb_phone = $(e.target).closest('.form-group').find('input.phone_mask').val();
	$.get('<?= htmlspecialchars_decode($auth); ?>' + "&wb_phone=" + wb_phone + "&wb_index=" + index, function( data ) {
		if (data.status) {
			html  = '<div id="modal-inf" class="modal">';
            html += '  <div class="modal-dialog">';
            html += '    <div class="modal-content">';
            html += '      <div class="modal-header">';
            html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html += '        <h4 class="modal-title">' + '<?= $entry_phone_code; ?>' + '</h4>';
            html += '      </div>';
			html += '	   <input type="hidden" id="wb_index_status" value="' + index + ' " />';
			html += '      <div class="modal-body">';
			html += '		 <div style="margin-bottom: 10px;">'
			html += '		   <input type="number" name="wb_phone_code" value="<?= $wb_phone_code; ?>" placeholder="<?= $entry_phone_code; ?>" id="entry-phone-code" class="form-control"/>';
			html += '		 </div>';
			html += '		 <div style="display: flex;justify-content: space-between;">';
			html += '		 	<div id="counterWrapper">Повторная отправка СМС возможна через <span id="counterResend"></span></div>'
			html += '		 	<div >';
			html += '		   		<a href="<?= $auth_code; ?>" onclick="sendCode(event, ' + index + ', ' + wb_phone.toString() + ')" data-toggle="tooltip" title="<?= $button_auth_phone_code; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?= $button_auth_phone_code; ?></a>';
			html += '	 	 	</div>';
			html += '		 </div>'
			html += '      </div>';
            html += '    </div';
            html += '  </div>';
            html += '</div>';

            $('body').append(html);

			$('#modal-inf').modal('show');
			if (data.till_next_request) {
				timerResend(data.till_next_request, index, wb_phone);
			}
			if (data.phone_token_value) {
				$('#tab' + index + ' [name=phone_token_value_' + index + ']').val(data.phone_token_value);
			}
		}
	});
}
function resetToken(e, index, wb_phone) {
	e.preventDefault();
	$.get('<?= htmlspecialchars_decode($auth); ?>' + "&wb_phone=" + wb_phone + '&wb_index=' + index, function( data ) {
		if (data.status) {
			$('#counterWrapper').html('Повторная отправка СМС возможна через <p id="counterResend"></p>');
			if (data.till_next_request) {
				timerResend(data.till_next_request)
			}
			if (data.phone_token_value) {
				$('#tab' + index + ' [name=phone_token_value_' + index + ']').val(data.phone_token_value);
			}
		}
	});
}
function timerResend(ms, index, phone) {
	var dd = document.getElementById('counterResend');
	var time = ms;
	dd.dataset.time = time;
	dd.innerText = msToTime(time);

	var timeCounter = setInterval(function(){
		var timer = dd.dataset.time
		if (timer <= 1000) {
			clearInterval(timeCounter);
			var new_html = '<a href="<?= $auth; ?>" onclick="resetToken(event, ' + index + ',' + phone.toString() + ')" data-toggle="tooltip" title="<?= $button_reauth_phone; ?>" class="btn btn-info"><i class="fa fa-refresh"></i> <?= $button_reauth_phone; ?></a>';
			$('#counterWrapper').html(new_html);
		}
		timer = timer - 1000;
		dd.dataset.time = timer;
		dd.innerText = msToTime(timer);
	}, 1000);
}
function msToTime(s) {
  var ms = s % 1000;
  s = (s - ms) / 1000;
  var secs = s % 60;
  s = (s - secs) / 60;
  var mins = s % 60;
  if (secs < 10) secs = '0' + secs;
  return mins + ':' + secs;
}
function sendCode(e, index, phone) {
	e.preventDefault();
	var wb_phone_code = $('#entry-phone-code[name=wb_phone_code]').val();
	var wb_phone_token_value = $('#tab' + index + ' [name=phone_token_value_' + index + ']').val();
	var wb_index_status = index;
	$.get('<?= htmlspecialchars_decode($auth_code); ?>' + '&wb_phone_code=' + wb_phone_code + '&wb_index=' + wb_index_status + '&wb_phone_token_value=' + wb_phone_token_value, function( data ) {
		if (data) {
			Object.entries(data).forEach(function(key, val) {
				$('#tab' + index + ' [name=' + key[0] + '_' + index + ']').val(key[1]);
			});
		}
		$('[form="form-wb"]').click();
		console.log(data)
	});
}
function addStatus() {
	html  = '<tr id="status-row' + status_row + '">';
	html += '  <td class="text-left"><input type="text" name="wb_order_status[' + status_row + '][id]" value="" id="input-image' + status_row + '" placeholder="ID статуса" class="form-control" /></td>';
	html += '  <td class="text-left"><input type="text" name="wb_order_status[' + status_row + '][name]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="removeStatus(this);" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#status tbody').append(html);

	status_row++;
}
function removeStatus(that) {
	$(that).closest('tr').remove();
}

function applyLicense(that) {
	$('[value="save_wb_data"]').click();
}

function refreshToken(event, wb_uuid) {
	event.preventDefault();
	$.ajax({
		url: '<?= htmlspecialchars_decode($r_token);?>',
		type: 'post',
		dataType: 'json',
		data: JSON.stringify({wb_uuid: wb_uuid}),
		cache: false,
		contentType: false,
		processData: false,
		success: function(json) {
			if(json.status === true) {
				alert('Токен успешно обновлен');
			} else {
				alert('Произошла ошибка. Свяжитесь с администратором');
			}
		},
		error: function(e) {
			alert('Произошла ошибка. Свяжитесь с администратором');
		}
	});
}

$('#button-upload').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			// Reset everything
			$('.alert').remove();

			$.ajax({
				url: '<?= htmlspecialchars_decode($import);?>' + '&wb_import_compare_field=' + $('#wb_import_compare_field').val() + '&wb_import_excel_compare_field=' + $('#wb_import_excel_compare_field').val() + '&wb_profile=' + $('#wb_import_profile').val(),
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},
				success: function(json) {
					if (json.status) {
						$('#button-upload').after('<span id="import-message" style="color:green;    padding-left: 20px;">Данные успешно импортированы</span>');
					} else {
						$('#button-upload').after('<span id="import-message" style="color:red;    padding-left: 20px;">Неверная структура документа или не выбран профиль</span>');
					}
					setTimeout(function(){$('#import-message').remove()}, 5000);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
