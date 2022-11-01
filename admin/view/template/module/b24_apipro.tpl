<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php if ($connectB24) { ?>
					<a data-toggle="tooltip" title="Проверка соединения с Битрикс 24" class="btn btn-success disabled"><i class="fa fa-check-circle"></i> Соединение установлено</a>
				<?php } else { ?>
					<a data-toggle="tooltip" title="Проверка соединения с Битрикс 24" class="btn btn-danger disabled"><i class="fa fa-ban"></i> Соединение не установлено</a>
				<?php } ?>
				
				<button type="submit" name='save-config' value='save-config' form="form-html" data-toggle="tooltip" title="Сохранить данные" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Сохранить</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?> (v2.2.1)</h1>
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
		<?php if (!empty($success)) { ?>
			<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">
					<div class="tab-pane">
						<ul class="nav nav-tabs" id="language">
							<li><a href="#b24_auth_tab" data-toggle="tab">Авторизация</a></li>
							<?php if(!empty($connectB24)) {?>
							<li><a href="#b24_order_tab" data-toggle="tab">Заказы</a></li> 
							<li><a href="#b24_customer_tab" data-toggle="tab">Клиенты</a></li>  
							<li><a href="#b24_product_tab" data-toggle="tab">Товары</a></li>
							<li><a href="#b24_status_tab" data-toggle="tab">Настройка статусов</a></li>
							<?php }?>
							<li><a href="#support-tab" data-toggle="tab">Поддержка</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="b24_auth_tab">
								<legend>Входящий вебхук</legend>
								<div class="form-group">
									<label for="b24_key_domain" class="col-sm-2 control-label">Входящий вебхук</label>
									<div class="col-sm-5">
										<input type="text" name="b24_in_hook" class="form-control" value="<?php echo $b24_in_hook; ?>" />
									</div>
									<div class="col-sm-5 ">
										<div class="bg-info message">Вставьте ссылку полученную на странице создания входящего вебхука</div>
									</div>
								</div>
								<?php if($connectB24) {?>
									<legend>Исходящие вебхуки</legend>							
									<?php foreach($out_hooks as $hooks_name => $hooks_desc){ ?>
										<div class="form-group">
											<label for="b24_out_hooks[<?php echo $hooks_name;?>]" class="col-sm-2 control-label"><?php echo $hooks_desc;?></label>
											<div class="col-sm-5">
												<input type="text" name="b24_out_hooks[<?php echo $hooks_name;?>]" class="form-control" value="<?php echo isset($b24_out_hooks[$hooks_name]) ? $b24_out_hooks[$hooks_name] : ''; ?>" />
											</div>
										</div>
									<?php }?>
								<?php }?>
								
							</div>
							<div class="tab-pane" id="b24_order_tab">
								<div class="form-group">
										<label class="col-sm-3 control-label">Отправка заказов в Битрикс 24</label>
										<div class="col-sm-3 ">
											<p><a class="btn btn-primary btn-block <?= $getOrderForSync != 0 && isset($connectB24) ? '' : 'disabled'; ?>" href="<?php echo $SendOrderToBitrix; ?>"><i class="fa fa-refresh"></i> Перенести заказы в Битрикс</a></p>
										</div>
										<div class="col-sm-6 ">
											<div class="bg-info message">В Битрикс 24 будут добавлены заказы из Opencart. Заказы от гостей в Лиды, заказы от клиентов в Сделки. Вы можете перенести выбранные заказы на <a href="<?php echo $catalog;?>/admin/index.php?route=sale/order&token=<?php echo $token;?>" target="_blank">странице заказов</a></div>
										</div>
								</div>
								<div class="form-group">
									<label for="manager" class="col-sm-3 control-label">Создатель заказов в Битрикс24</label>
									<div class="col-sm-3">
										<select name="b24_manager[created]" id="manager" class="form-control">
											<?php
												foreach($user_list as $manager)
												{
													$name = $manager['LAST_NAME'] .' '. $manager['NAME'];
													$managerId = $manager['ID'];
													$selected = $managerId == $created_id ? 'selected' : '';
													echo "<option value='$managerId' $selected>$name</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-6">
										<p class="bg-info message">Выбранный менеджер будет создателем заказов, клиентов.</p>
									</div>
								</div>
								<div class="form-group">
									<label for="manager" class="col-sm-3 control-label">Менеджер заказов</label>
									<div class="col-sm-3">
										<select name="b24_manager[manager]" id="manager" class="form-control">
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
									</div>
									<div class="col-sm-6">
										<p class="bg-info message">Выбранный менеджер будет получать нераспределённые лиды, сделки, клиенты.</p>
									</div>
								</div>
								<div class="form-group">
											<label for="b24_place_comment" class="col-sm-3 col-lg-3 control-label">Заказы видны всем менеджерам</label>
											<div class="col-sm-3 col-lg-3">
												<select name="b24_manager[order_open]" class="form-control">
													<option value='Y' <?= (isset($order_open) && $order_open == 'Y') ? 'selected' : ''; ?>>Да</option>
													<option value='N' <?= (isset($order_open) && $order_open == 'N') ? 'selected' : ''; ?>>Нет</option>
												</select>
											</div>
											<div class="col-sm-6 col-lg-6">
											<p class="bg-info message">Настройки видимости каждого заказа могут быть изменены в процессе его редактирования.</p>
											</div>
								</div>
								<div class="form-group">
											<label class="col-sm-3 col-lg-3 control-label">Отправить письмо клиенту при смене статуса заказа</label>
											<div class="col-sm-3 col-lg-3">
												<select name="b24_order[order_notify]" class="form-control">
													<option value='1' <?= (isset($b24_order['order_notify']) && $b24_order['order_notify'] == 1) ? 'selected' : ''; ?>>Да</option>
													<option value='0' <?= (isset($b24_order['order_notify']) && $b24_order['order_notify'] == 0) ? 'selected' : ''; ?>>Нет</option>
												</select>
											</div>
											<div class="col-sm-6 col-lg-6">
											<p class="bg-info message">Настройки видимости каждого заказа могут быть изменены в процессе его редактирования.</p>
											</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Источник заказа</label>
									<div class="col-sm-3">
										<select name="b24_order[source]" id="source" class="form-control">
											<?php
												foreach($b24_source as $source)
												{
													$sourceName = $source['NAME'];
													$sourceId = $source['STATUS_ID'];
													$selected = $sourceId == $order_source_id ? 'selected' : '';
													echo "<option value='$sourceId' $selected>$sourceName</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-6">
										<p class="bg-info message">Выбранный источник будет назначен заказам.</p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Пользовательское поле для "Способ оплаты"</label>
									<div class="col-sm-3">
										<label for="feldpay_lead">Лиды</label>
										<select name="b24_order[fieldpay][lead]" id="feldpay_lead" class="form-control">
											<?php
												foreach ($b24_uf_lists['lead_uf_list'] as $k => $v_lead) {
													$fieldName = $v_lead['LABEL'];
													$fieldId = $v_lead['NAME'];
													$selected = $fieldId == $pay_field['lead'] ? 'selected' : '';
													echo "<option value='$fieldId' $selected>$fieldName</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="feldpay_deal">Сделки</label>
										<select name="b24_order[fieldpay][deal]" id="feldpay_deal" class="form-control">
											<?php
												foreach ($b24_uf_lists['deal_uf_list'] as $k => $v_deal) {
													$fieldName = $v_deal['LABEL'];
													$fieldId = $v_deal['NAME'];
													$selected = $fieldId == $pay_field['deal'] ? 'selected' : '';
													echo "<option value='$fieldId' $selected>$fieldName</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<p class="bg-info message">В выбранное поле будет записан способ оплаты.</p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Выгрузить заказ по ID</label>
									<div class="col-md-3">
										<input type="text" placeholder="Введите ID заказа Opencart" name="order_id" class="form-control" /> 
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6">
										<button type="button" id="export_order" class="btn btn-success"><i class="fa fa-download"></i> Выгрузить в Битрикс 24</button>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="b24_customer_tab">
								<legend>Синхронизация клиентов</legend>
								<div class="form-group">
										<div class="col-sm-3 ">
											<a class="btn btn-primary btn-block <?= $getContactForSync != 0 && isset($connectB24) ? '' : 'disabled'; ?>" href="<?php echo $button_synchronizationcontacts; ?>"><i class="fa fa-refresh"></i> Синхронизировать (<?php echo $getContactForSync;?> клиентов)</a>
										</div>
										<div class="col-sm-9 ">
											<div class="bg-info message">Функция синхронизирует всех клиентов из Opencart и Битрикс 24. Это необходимо сделать один раз в процессе настройки модуля.</div>
										</div>
								</div>	
								<legend>Настройки клиентов</legend>		
								<div class="form-group">
									<label class="col-sm-3 control-label">Источник клиента</label>
									<div class="col-sm-3">
										<select name="b24_customer[settings][SOURCE]" id="source" class="form-control">
											<?php
												foreach($b24_source as $source)
												{
													$sourceName = $source['NAME'];
													$sourceId = $source['STATUS_ID'];
													$selected = $sourceId == $customer_source_id ? 'selected' : '';
													echo "<option value='$sourceId' $selected>$sourceName</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-6">
										<p class="bg-info message">Выбранный источник будет назначен клиентам.</p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Тип клиента</label>
									<div class="col-sm-3">
										<select name="b24_customer[settings][RETAIL]" id="source" class="form-control">
											<?php
												foreach($b24_contact_type as $type)
												{
													$sourceName = $type['NAME'];
													$sourceId = $type['STATUS_ID'];
													$selected = $sourceId == $retail_id ? 'selected' : '';
													echo "<option value='$sourceId' $selected>$sourceName</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-6">
										<p class="bg-info message">Выбранный источник будет назначен заказам и клиентам.</p>
									</div>
								</div>
								<div class="form-group">
											<label for="b24_place_comment" class="col-sm-3 col-lg-3 control-label">Клиенты видны всем менеджерам</label>
											<div class="col-sm-3 col-lg-3">
												<select name="b24_customer[settings][customer_open]" class="form-control">
													<option value='Y' <?= (isset($customer_open) && $customer_open == 'Y') ? 'selected' : ''; ?>>Да</option>
													<option value='N' <?= (isset($customer_open) && $customer_open == 'N') ? 'selected' : ''; ?>>Нет</option>
												</select>
											</div>
											<div class="col-sm-6 col-lg-6">
											<p class="bg-info message">Настройки видимости каждого заказа могут быть изменены в процессе его редактирования.</p>
											</div>
								</div>
							</div>
							<div class="tab-pane" id="b24_status_tab">
								<?php if (!empty($connectB24)) { ?>
								<div class="col-sm-3 col-lg-3">
									<nav class="nav-sidebar">
										<ul class="nav nav-pills nav-stacked">
											<li class="active"><a href="#sett_status_b24_opencart" data-toggle="tab" aria-expanded="true">Статусы Битрикс 24 <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Opencart</a></li>
											<li><a href="#sett_status_opencart_b24" data-toggle="tab" aria-expanded="true">Статусы Opencart <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Битрикс 24</a></li>
										</ul>
									</nav>
							</div>
							<div class="tab-content col-sm-9 col-lg-9">
								<div class="tab-pane active" id="sett_status_b24_opencart">
									<?php if (!empty($connectB24)) { ?>
									<legend>Статусы лидов</legend>
									<a href="/admin/index.php?route=localisation/order_status&token=<?php echo $token;?>" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> Перейти на страницу добавления статусов в Opencart</a>
									<p>Настройки статусов которые назначаются при изменении лидов или сделок в Битрикс 24. Статусы изменяются в Opencart.</p>
									<?php foreach ($b24_statuses as $b24_status) { ?>
										<div class="form-group">
												<label class="col-sm-4 col-lg-4 control-label"><?php echo $b24_status['NAME']; ?>  (B24) <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></label>
											<div class="col-sm-8 col-lg-8">
												<select name="b24_status[in][lead][<?= $b24_status['STATUS_ID']; ?>]" id="status-<?= $b24_status['STATUS_ID']; ?>" class="form-control">
												<option value='0' <?= (isset($statuses['in']['lead'][$b24_status['STATUS_ID']]) && $statuses['in']['lead'][$b24_status['STATUS_ID']] == 0) ? 'selected' : ''; ?>>! - Не выбран</option>
													<?php
														foreach($oc_statuses as $oc_status){ ?>
														<option value='<?= $oc_status['order_status_id']; ?>' <?= (isset($statuses['in']['lead'][$b24_status['STATUS_ID']]) && $statuses['in']['lead'][$b24_status['STATUS_ID']] == $oc_status['order_status_id']) ? 'selected' : ''; ?>><?= $oc_status['name']; ?> (opencart)</option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
									<legend>Стадии сделки</legend>
									<?php foreach ($b24_stages as $b24_stage) { ?>
										<div class="form-group">
											<label class="col-sm-4 col-lg-4 control-label"><?php echo $b24_stage['NAME']; ?>  (B24) <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></label>
											<div class="col-sm-8 col-lg-8">
												<select name="b24_status[in][deal][<?= $b24_stage['STATUS_ID']; ?>]" id="stage-<?= $b24_stage['STATUS_ID']; ?>" class="form-control">
												<option value='0' <?= (isset($statuses['in']['deal'][$b24_stage['STATUS_ID']]) && $statuses['in']['deal'][$b24_stage['STATUS_ID']] == 0) ? 'selected' : ''; ?>>! - Не выбран</option>
												<?php foreach($oc_statuses as $oc_status){ ?>
														<option value="<?= $oc_status['order_status_id']; ?>" <?= (isset($statuses['in']['deal'][$b24_stage['STATUS_ID']]) && $statuses['in']['deal'][$b24_stage['STATUS_ID']] == $oc_status['order_status_id']) ? 'selected' : ''; ?>><?= $oc_status['name']; ?> (opencart)</option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
								</div>
								
								<div class="tab-pane" id="sett_status_opencart_b24">
									<?php if (!empty($connectB24)) { ?>
									<legend>Статусы лидов</legend>
									<a href="/admin/index.php?route=localisation/order_status&token=<?php echo $token;?>" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> Перейти на страницу добавления статусов в Opencart</a>
									<p>Настройки статусов Битрикс 24, которые назначаются при создании или изменении заказов в Opencart. Статусы при создании изменяются модулями оплаты.</p>
									<?php foreach ($oc_statuses as $oc_status) { ?>
										<div class="form-group">
												<label class="col-sm-4 col-lg-4 control-label"><?= $oc_status['name']; ?> (OC) <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></label>
											<div class="col-sm-8 col-lg-8">
												<select name="b24_status[out][lead][<?= $oc_status['order_status_id']; ?>]" id="status-<?= $oc_status['order_status_id']; ?>" class="form-control">
												<option value='0' <?= (isset($statuses['out']['lead'][$oc_status['order_status_id']]) && $statuses['out']['lead'][$oc_status['order_status_id']] == 0) ? 'selected' : ''; ?>>! - Не выбран</option>
													<?php foreach($b24_statuses as $b24_status){ ?>
														<option value='<?= $b24_status['STATUS_ID']; ?>' <?= (isset($statuses['out']['lead'][$oc_status['order_status_id']]) && $statuses['out']['lead'][$oc_status['order_status_id']] == $b24_status['STATUS_ID']) ? 'selected' : ''; ?>><?php echo $b24_status['NAME']; ?> (битрикс)</option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
									<legend>Стадии сделки</legend>
									<?php foreach ($oc_statuses as $oc_status) { ?>
										<div class="form-group">
											<label class="col-sm-4 col-lg-4 control-label"><?= $oc_status['name']; ?> (OC) <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></label>
											<div class="col-sm-8 col-lg-8">
												<select name="b24_status[out][deal][<?= $oc_status['order_status_id']; ?>]" id="stage-<?= $oc_status['order_status_id']; ?>" class="form-control">
												<option value='0' <?= (isset($statuses['out']['deal'][$oc_status['order_status_id']]) && $statuses['out']['deal'][$oc_status['order_status_id']] == 0) ? 'selected' : ''; ?>>! - Не выбран</option>
												<?php
														foreach($b24_stages as $b24_stage){ ?>
														<option value='<?= $b24_stage['STATUS_ID']; ?>' <?= (isset($statuses['out']['deal'][$oc_status['order_status_id']]) && $statuses['out']['deal'][$oc_status['order_status_id']] == $b24_stage['STATUS_ID']) ? 'selected' : ''; ?>><?php echo $b24_stage['NAME']; ?> (битрикс)</option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
								<?php } ?>
								</div>
							</div>
							</div>
							<div class="tab-pane" id="b24_product_tab">
								<legend>Синхронизация каталога товаров</legend>
								<div class="form-group">
										<div class="col-sm-3 ">
											<p><a class="btn btn-primary btn-block <?= $getProductForSync != 0 && isset($connectB24) ? '' : 'disabled'; ?>" href="<?php echo $button_synchronizationproducts; ?>"><i class="fa fa-refresh"></i> Синхронизировать (<?php echo $getProductForSync;?> товаров)</a></p>
											<p><a class="btn btn-warning btn-block" href="<?php echo $button_updateproduct; ?>"><i class="fa fa-trash-o"></i> Обновить товары в Б24</a></p>
										</div>
										<div class="col-sm-9 ">
											<div class="bg-info message"><p>Кнопка <b>"Синхронизировать"</b> добавляет товары в Битрикс 24 которых ещё нет в каталоге Битрикса.</p><p>При этом используется информация из временной таблицы Opencart. </p><p>Товары добавляются со скоростью 50 шт в секунду. За один раз можно загрузить не более 1000 товаров. Для загрузки большего количества товаров повторите операцию после завершения.</p>
											<p>Кнопка <b>"Обновить товары"</b> обновляет информацию в товарах, которые были обновлены в Opencart.</p></div>
										</div>
										</div>
										<legend>Свойства товаров</legend>
										<?php foreach ($oc_propertys as $name => $oc_property) { ?>
										<div class="form-group">
											<label for="" class="col-sm-3 control-label"><?= $oc_property;?></label>
											<div class="col-sm-3">
												<select name="b24_productprops[<?= $name;?>]" id="<?= $name; ?>" class="form-control">
													<option value='0'>Не выбрано</option>
													<?php foreach ($b24_propertys as $key => $b24_property) { ?>
														<option value='<?= $b24_property['ID']; ?>'<?= (isset($b24_productprops[$name]) && $b24_productprops[$name] == $b24_property['ID']) ? 'selected' : ''; ?>>Битрикс 24 - <?= $b24_property['NAME']; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-sm-3">Выберите свойство соответствующее "<?= $oc_property;?>"</div>
										</div>
										<?php } ?>
							</div>
							<div class="tab-pane" id="support-tab">
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
					.message{
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
		
		// Экспорт одного заказа
		$('#export_order').on('click', function() {
        var order_id = $('input[name=\'order_id\']').val();
        if (order_id && order_id > 0) {
            $.ajax({
                url: '<?php echo $catalog; ?>' + 'admin/index.php?route=extension/module/b24_apipro/exportOneOrder&token=' + '<?php echo $token; ?>' + '&order_id=' + order_id,
                beforeSend: function() {
                    $('#export_order').button('loading');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                },
                success: function(data, textStatus, jqXHR) {
                    response = JSON.parse(jqXHR['responseText']);
                    if (response['status_code'] == '400') {
                        $('.alert-danger').remove();
                        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + response['error_msg'] + '</div>');
                        $('#export_order').button('reset');
                    } else {
                        $('.alert-success').remove();
                        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> Заказ успешно отправлен в Битрикс 24. ' + response['success_id'] + '</div>');
                        $('#export_order').button('reset');
                        $('input[name=\'order_id\']').val('');
                    }
                }
            });
        } else {
            $('.alert-danger').remove();
            $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Введите номер заказа для отправки в Битрикс 24</div>');
            $('#export_order').button('reset');
        }
    });
	</script>
	<style>
	.nav-sidebar{
		width: 100%;
		padding: 0px 0;
		border-right: none;
	}
	</style>
	</div><?php echo $footer; ?>