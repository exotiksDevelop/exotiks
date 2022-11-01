<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	
	<div class="page-header">
		
		<div class="container-fluid">
			
			<div class="pull-right">
				<!--<button type="submit" name='save-config' value='save-config' form="form-html" data-toggle="tooltip" title="Сохранить данные" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить</button>-->
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
		
		<?php if (!empty($error_warning)) { ?>
			
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			
		<?php } ?>
		
		<?php if(!empty($connect_error)) { ?>
			
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $connect_error; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			
		<?php } ?>
		
		<?php if(!empty($connect_success)) { ?>
			
			<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $connect_success; ?>
				<button type="button" class="close" data-dismiss="success">&times;</button>
			</div>
			
		<?php } ?>
		
		<div class="panel panel-default">
			
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">
					<!-- Статус установки ключа -->
							<div class="form-group">
								<?php if ($b24_hook_key_api) { ?>
									<div class="col-sm-12 message">
										<p class="bg-success"><i class="fa fa-check-circle"></i> Связь с Битрикс24 установлена. Все данные успешно передаются и принимаются.</p>
									</div>
									<?php } else { ?>
									<div class="col-sm-12 message">
										<p class="bg-danger"><i class="fa fa-exclamation-circle"></i> Связь с Битрикс24 не установлена. Данные не отправляются и не принимаются.</p>
									</div>
								<?php } ?>
							</div>
					<div class="tab-pane">
						<ul class="nav nav-tabs" id="language">
							<li><a href="#setting-b24-hook-tab" data-toggle="tab">Авторизация</a></li>
							<li><a href="#product-b24-tab" data-toggle="tab">Начальная синхронизация</a></li>
							<li><a href="#statuses-b24-tab" data-toggle="tab">Настройка статусов</a></li>
							<li><a href="#customer-b24-tab" data-toggle="tab">Менеджер заказов</a></li>
							<li><a href="#product-b24-support" data-toggle="tab">Поддержка</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="setting-b24-hook-tab">
								<div class="form-group">
									<label for="b24_hook_key_domain" class="col-sm-2 control-label">Домен bitrix24:</label>
									<div class="col-sm-3">
										<input type="text" name="b24_hook_key_domain" class="form-control" value="<?php echo $b24_hook_key_domain ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Введите домен BITRIX24 без https:// и закрывающего / </div>
									</div>
								</div>
								<!-- Входящий хук -->
								<div class="form-group">
									<label for="b24_hook_key_api" class="col-sm-2 control-label">Ключ API:</label>
									<div class="col-sm-3">
										<input type="text" name="b24_hook_key_api" class="form-control" value="<?php echo $b24_hook_key_api ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Введите Код входящего вебхука. Для этого создайте входящий вебхук на странице Приложения - Вебхуки. </div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_hook_key_id" class="col-sm-2 control-label">ID администратора Вебхука:</label>
									<div class="col-sm-3">
										<input type="text" name="b24_hook_key_id" class="form-control" value="<?php echo $b24_hook_key_id ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Введите ID администратора вебхука. Он указан в URL вебхука /rest/<b style="color:red;">1</b>/</div>
									</div>
								</div>
								<!-- Входящий хук -->
								<div class="form-group">
									<label for="save-hooks" class="col-sm-2 control-label"></label>
									<div class="col-sm-3">
										<input type="submit" value="Сохранить" class="btn btn-success" name="save-hooks">
									</div>
									<div class="col-sm-7 message">

									</div>
								</div>
								<!-- Исходящие хуки -->
								<div class="col-sm-12"><h3>Настройка исходящих Вебхуков</h3></div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMINVOICEADD]" class="col-sm-2 control-label">Создание счета</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMINVOICEADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMINVOICEADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMINVOICEADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания счёта. Срабатывает когда в Битрикс24 создаётся счёт</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMINVOICEUPDATE]" class="col-sm-2 control-label">Обновление счета</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMINVOICEUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMINVOICEUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMINVOICEUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления счёта. Срабатывает когда в Битрикс24 обновляется счёт</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMINVOICEDELETE]" class="col-sm-2 control-label">Удаление счета</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMINVOICEDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMINVOICEDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMINVOICEDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления счёта. Срабатывает когда в Битрикс24 удаляется счёт</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMINVOICESETSTATUS]" class="col-sm-2 control-label">Обновление статуса счета</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMINVOICESETSTATUS]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMINVOICESETSTATUS']) ? $b24_out_hooks['b24_out_hooks_ONCRMINVOICESETSTATUS'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления статуса счёта. Срабатывает когда в Битрикс24 обновляется статус счёт</div>
									</div>
								</div>
								

								<div class="col-sm-12"><legend>Вебхуки заказов</legend></div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMLEADADD]" class="col-sm-2 control-label">Создание лида</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMLEADADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMLEADADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMLEADADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания лида. Срабатывает когда в Битрикс24 создаётся лид</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMLEADUPDATE]" class="col-sm-2 control-label">Обновление лида</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMLEADUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMLEADUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMLEADUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления лида. Срабатывает когда в Битрикс24 обновляется лид</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMLEADDELETE]" class="col-sm-2 control-label">Удаление лида</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMLEADDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMLEADDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMLEADDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления лида. Срабатывает когда в Битрикс24 удаляется лид</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMDEALADD]" class="col-sm-2 control-label">Создание сделки</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMDEALADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMDEALADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMDEALADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания сделки. Срабатывает когда в Битрикс24 создаётся сделка</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMDEALUPDATE]" class="col-sm-2 control-label">Обновление сделки</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMDEALUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMDEALUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMDEALUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления сделки. Срабатывает когда в Битрикс24 обновляется сделка</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMDEALDELETE]" class="col-sm-2 control-label">Удаление сделки</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMDEALDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMDEALDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMDEALDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления сделки. Срабатывает когда в Битрикс24 удаляется сделка</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCOMPANYADD]" class="col-sm-2 control-label">Создание компании</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCOMPANYADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCOMPANYADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMCOMPANYADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания компании. Срабатывает когда в Битрикс24 создаётся компания</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCOMPANYUPDATE]" class="col-sm-2 control-label">Обновление компании</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCOMPANYUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCOMPANYUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMCOMPANYUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления компании. Срабатывает когда в Битрикс24 обновляется компания</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCOMPANYDELETE]" class="col-sm-2 control-label">Удаление компании</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCOMPANYDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCOMPANYDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMCOMPANYDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления компании. Срабатывает когда в Битрикс24 удаляется компания</div>
									</div>
								</div>

								<div class="col-sm-12"><legend>Вебхуки контактов</legend></div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCONTACTADD]" class="col-sm-2 control-label">Создание контакта</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCONTACTADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCONTACTADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMCONTACTADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания контакта. Срабатывает когда в Битрикс24 создаётся контакт</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCONTACTUPDATE]" class="col-sm-2 control-label">Обновление контакта</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCONTACTUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCONTACTUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMCONTACTUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления контакта. Срабатывает когда в Битрикс24 обновляется контакт</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCONTACTDELETE]" class="col-sm-2 control-label">Удаление контакта</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCONTACTDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCONTACTDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMCONTACTDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления контакта. Срабатывает когда в Битрикс24 удаления контакт</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCURRENCYADD]" class="col-sm-2 control-label">Создание валюты</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCURRENCYADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCURRENCYADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMCURRENCYADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания валюты. Срабатывает когда в Битрикс24 создаётся валюта</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCURRENCYUPDATE]" class="col-sm-2 control-label">Обновление валюты</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCURRENCYUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCURRENCYUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMCURRENCYUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления валюты. Срабатывает когда в Битрикс24 обновляется валюта</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMCURRENCYDELETE]" class="col-sm-2 control-label">Удаление валюты</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMCURRENCYDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMCURRENCYDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMCURRENCYDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления валюты. Срабатывает когда в Битрикс24 удаляется валюта</div>
									</div>
								</div>
								<div class="col-sm-12"><legend>Вебхуки товаров</legend></div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMPRODUCTADD]" class="col-sm-2 control-label">Создание товара</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMPRODUCTADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMPRODUCTADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMPRODUCTADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания товара. Срабатывает когда в Битрикс24 создаётся товар</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMPRODUCTUPDATE]" class="col-sm-2 control-label">Обновление товара</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMPRODUCTUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMPRODUCTUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMPRODUCTUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления товара. Срабатывает когда в Битрикс24 обновляется товар</div>
									</div>
								</div>
								<div class="form-group">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMPRODUCTDELETE]" class="col-sm-2 control-label">Удаление товара</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMPRODUCTDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMPRODUCTDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMPRODUCTDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления товара. Срабатывает когда в Битрикс24 удаляется товар</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYADD]" class="col-sm-2 control-label">Создание дела</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYADD]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMACTIVITYADD']) ? $b24_out_hooks['b24_out_hooks_ONCRMACTIVITYADD'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для создания дела. Срабатывает когда в Битрикс24 создаётся дело</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYUPDATE]" class="col-sm-2 control-label">Обновление дела</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYUPDATE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMACTIVITYUPDATE']) ? $b24_out_hooks['b24_out_hooks_ONCRMACTIVITYUPDATE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для обновления дела. Срабатывает когда в Битрикс24 обновляется дело</div>
									</div>
								</div>
								<div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
									<label for="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYDELETE]" class="col-sm-2 control-label">Удаление дела</label>
									<div class="col-sm-3">
										<input type="text" name="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYDELETE]" class="form-control" value="<?php echo isset($b24_out_hooks['b24_out_hooks_ONCRMACTIVITYDELETE']) ? $b24_out_hooks['b24_out_hooks_ONCRMACTIVITYDELETE'] : ''; ?>" />
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Вебхук для удаления дела. Срабатывает когда в Битрикс24 удаляется дело</div>
									</div>
								</div>
								<!-- Исходящие хуки -->
								<div class="form-group">
									<label for="save-hooks" class="col-sm-2 control-label"></label>
									<div class="col-sm-3">
										<input type="submit" value="Сохранить" class="btn btn-success" name="save-hooks">
									</div>
									<div class="col-sm-7 message">

									</div>
								</div>
							</div>
							<div class="tab-pane" id="out-hooks-tab">
								
							</div>
							<div class="tab-pane" id="statuses-b24-tab">
								<?php if ($b24_hook_key_api) { ?>
									<legend>Статусы лидов</legend>
									<?php foreach ($b24_statuses as $b24_status) { ?>
										<div class="form-group">
												<label for="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYDELETE]" class="col-sm-2 col-lg-2 control-label"><?php echo $b24_status['NAME']; ?>  (B24)</label>
											<div class="col-sm-3 col-lg-3">
												<select name="statuses[<?= $b24_status['STATUS_ID']; ?>]" id="status-<?= $b24_status['STATUS_ID']; ?>" class="form-control">
													<?php
														foreach($oc_statuses as $oc_status){ ?>
														<option value='<?= $oc_status['order_status_id']; ?>' <?= (isset($statuses['statuses'][$b24_status['STATUS_ID']]) && $statuses['statuses'][$b24_status['STATUS_ID']] == $oc_status['order_status_id']) ? 'selected' : ''; ?>><?= $oc_status['name']; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-sm-7 col-lg-7">
											</div>
										</div>
									<?php } ?>
									<legend>Стадии сделки</legend>
									<?php foreach ($b24_stages as $b24_stage) { ?>
										<div class="form-group">
											<label for="b24_out_hooks[b24_out_hooks_ONCRMACTIVITYDELETE]" class="col-sm-2 col-lg-2 control-label"><?php echo $b24_stage['NAME']; ?>  (B24)</label>
											<div class="col-sm-3 col-lg-3">
												<select name="stages[<?= $b24_stage['STATUS_ID']; ?>]" id="stage-<?= $b24_stage['STATUS_ID']; ?>" class="form-control">
												<?php
														foreach($oc_statuses as $oc_status){ ?>
														<option value='<?= $oc_status['order_status_id']; ?>' <?= (isset($statuses['stages'][$b24_stage['STATUS_ID']]) && $statuses['stages'][$b24_stage['STATUS_ID']] == $oc_status['order_status_id']) ? 'selected' : ''; ?>><?= $oc_status['name']; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-sm-7 col-lg-7">
											</div>
										</div>
									<?php } ?>
									<div class="form-group">
										<label for="save-hooks" class="col-sm-2 control-label"></label>
										<div class="col-sm-3">
											<input type="submit" value="Сохранить статусы" class="btn btn-success" name="save-statuses">
										</div>
										<div class="col-sm-7 message">
										</div>
								</div>
								<?php } ?>
							</div>
							<div class="tab-pane" id="customer-b24-tab">
								<div class="form-group">
									<label for="manager" class="col-sm-2 control-label">Менеджер по умолчанию</label>
									<div class="col-sm-3">
										<select name="manager" id="manager" class="form-control">
											<?php
												foreach($user_list as $manager)
												{
													$name = $manager['LAST_NAME'] .' '. $manager['NAME'];
													$managerId = $manager['ID'];
													$selected = $managerId == $manager_id ? 'selected' : '';
													echo "<option value='$managerId' $selected>$name</option>";
												}
											?>
										</select>
										<hr>
										<button type="submit" class="btn btn-success" name="refresh-user-list" value="refresh"><i class="fa fa-refresh"></i> Обновить список</button>
										<button type="submit" class="btn btn-success" name="set-manager" value="set-manager"><i class="fa fa-plug" aria-hidden="true"></i> Назначить менеджера</button>
									</div>
									<div class="col-sm-7 message">
										<p class="bg-warning">Выбранный менеджер будет получать нераспределённые лиды, сделки, клиенты.</p>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="product-b24-tab">
								<?php if (true == $synchronizationcategories && true == $synchronizationproducts) { ?>
									<div class="form-group">
										<label for="client_id" class="col-sm-2 control-label">Статус синхронизации</label>
										<div class="col-sm-10 message">
											<div class="bg-success">Все товары и категории синхронизированы с Битрикс24</div>
										</div>
									</div>
									<?php } elseif (false == $synchronizationcategories) { ?>
									<div class="form-group">
										<label for="client_id" class="col-sm-2 control-label">Синхронизация категорий с Битрикс24</label>
										<div class="col-sm-3">
											<a class="btn btn-success" href="<?php echo $button_synchronizationcategories; ?>">Синхронизировать</a>
										</div>
										<div class="col-sm-7 message">
											<div class="bg-warning">Нажимая на кнопку "Синхронизировать" вы производите импорт всех категорий из Opencart в Битрикс24. </div>
										</div>
									</div>
									<?php } elseif (false == $synchronizationproducts) { ?>
									<div class="form-group">
										<label for="client_id" class="col-sm-2 control-label">Синхронизация товаров с Битрикс24</label>
										<div class="col-sm-3">
											<a class="btn btn-success" href="<?php echo $button_synchronizationproducts; ?>">Синхронизировать</a>
										</div>
										<div class="col-sm-7 message">
											<div class="bg-warning">Нажимая на кнопку "Синхронизировать" вы производите импорт всех товаров из Opencart в Битрикс24.</div>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label for="client_id" class="col-sm-2 control-label">Синхронизация контактов с Битрикс24</label>
									<div class="col-sm-3">
										<a class="btn btn-success" href="<?php echo $button_synchronizationcontacts; ?>">Синхронизировать</a>
									</div>
									<div class="col-sm-7 message">
										<div class="bg-warning">Нажимая на кнопку "Синхронизировать" вы производите импорт в Opencart информации о всех контактах Битрикс24.</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="product-b24-support">
								<div class="col-sm-2 col-lg-2">
									<p><strong>Автор модуля</strong></p>
								</div>
								<div class="col-sm-10 col-lg-10">
									<p> @apipro</p>
								</div>
								<div class="col-sm-2 col-lg-2">
									<p><strong>Сайт</strong></p>
								</div>
								<div class="col-sm-10 col-lg-10">
									<p><a href="http://api-pro.ru" target="_blank">api-pro.ru</a></p>
								</div>
								<div class="col-sm-2 col-lg-2">
									<p><strong>Телеграмм</strong></p>
								</div>
								<div class="col-sm-10 col-lg-10">
									<p><a href="https://t.me/api4pro" target="_blank">https://t.me/api4pro</a></p>
								</div>
								<div class="col-sm-2 col-lg-2">
									<p><strong>Email</strong></p>
								</div>
								<div class="col-sm-10 col-lg-10">
									<p><a href="mailto:api2pro@yandex.ru" target="_blank">api2pro@yandex.ru</a></p>
								</div>
							</div>
							<?php //} ?>
						</div>
					</div>
				</form>
				<style>
					.message .bg-danger, .message .bg-success, .message .bg-warning{
					padding: 1em;
					border-radius: 3px;
					}
				</style>
			</div>
			
		</div>
		
	</div>
	
	<script type="text/javascript">
		
		$('#language a:first').tab('show');
		
		function selectedTab() {
			
			var tabId = localStorage.getItem('tab_b24_api_pro');
			
			if(!tabId)
			
			{
				tabId = $('#language a:first').attr('href');
			}
			
			return tabId;
			
		}
		
		$('#language a').click(function () {
			
			$this = $(this);
			
			var tabId = $this.attr('href');
			
			localStorage.setItem('tab_b24_api_pro', tabId);
			
		});
		
		$('#language a[href=' + selectedTab() +' ]').click();
		
	</script></div><?php echo $footer; ?>