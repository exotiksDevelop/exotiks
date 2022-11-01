<?php
// Heading
$_['heading_title']						= 'СДЭК Интеграция обмена';
$_['heading_title_main']				= 'СДЭК: Интеграция обмена';
$_['heading_title_bk_main']				= 'СДЭК интегратор';
$_['heading_title_option']				= 'Настройки';
$_['heading_title_order']				= 'Заказы на отгрузку';
$_['heading_title_dispatch']			= 'Отгрузки';
$_['heading_title_new_order']			= 'Новая отгрузка';

// Text
$_['text_order_n']						= 'Заказ №';
$_['text_view']							= 'Просмотр';
$_['text_package_n']					= 'Упаковка №';
$_['text_module']						= 'Модули';
$_['text_today']						= 'Сегодня';
$_['text_delete']						= 'Удалить';
$_['text_yesterday']					= 'Вчера';
$_['text_success']						= 'Изменения сохранены!';
$_['text_order_date']					= 'Дата отгрузки';
$_['text_order_count_items']			= 'Количество заказов в документе';
$_['text_city']							= 'Город отправления';
$_['text_column_right']					= 'Правая колонка';
$_['text_order_id']						= 'Номер заказа';
$_['text_order_total']					= 'Стоимость заказа';
$_['text_customer_shipping_method']		= 'Способ доставки клиента';
$_['text_shipping_address']				= 'Адрес доставки';
$_['text_customer_shipping_address']	= 'Адрес доставки покупателя';
$_['text_from']							= 'c';
$_['text_to']							= 'до';
$_['text_courier']						= 'Приезд курьера';
$_['text_courier_address']				='Адрес отправителя';
$_['text_short_length']					= 'Д';
$_['text_short_width']					= 'Ш';
$_['text_short_height']					= 'В';
$_['text_attention']					= 'Внимание!';
$_['text_courier_day']					= 'На один день возможно не более одного вызова курьера на один адрес';
$_['text_courier_hour_range']			= 'Диапазон времени для приезда курьера не должен быть меньше 3 часов';
$_['text_title_schedule']				= 'Расписание времени доставки';
$_['text_title_orders'] 				= 'Заказы';
$_['text_help_shedule']					= 'Данные заполняются только если ИМ запрашивает у получателя расписание для доставки/забора отправления (Определено в договоре).';
$_['text_help_shedule_detail']			= '<ul class="help"><li>На одну дату по одному заказу может быть только одно расписание;</li><li>В один день возможен один временной интервал не менее 3 часов;</li><li>Расписание можно задать только на будущую дату;</li><li>Расписание может быть передано позже.</li></ul>';
$_['text_sync']							= 'Синхронизация';
$_['text_user_comment']					= 'Комментрий покупателя:';
$_['text_weight_fixed']					= 'Грамм';
$_['text_weight_all']					= '% от веса заказа';
$_['text_tokens']						= 'Токены';
$_['text_token_dispatch_number']		= 'Номер отправления';
$_['text_token_order_id']				= 'Номер заказа';
$_['text_help_status_rule']				= 'Позволяет создать список правил для обновления статуса заказа клиента на основе статуса отправления.';
$_['text_testing_api_keys']				= 'Тестовые API ключи: <br><b>Учетная запись:</b> %s <br> <b>Секретный код:</b> %s';

// Entry
$_['entry_city']						= '<span data-toggle="tooltip" title="Автокомплит">Город отправления</span>';
$_['entry_copy_count']					= 'Число копий одной квитанции на листе';
$_['entry_city_default']				= '<span data-toggle="tooltip" title="Если отключено, то для каждого заказа в отгрузке можно задать свой город отправления.">Основной город отправления</span>';
$_['entry_status']						= 'Статус:';
$_['entry_sort_order']					= 'Порядок сортировки:';
$_['entry_tariff']						= 'Тариф';
$_['entry_delivery_recipient_cost']		= '<span data-toggle="tooltip" title="В случае, если услуги доставки СДЭК оплачивает не получатель, а ИМ. Значение параметра отображается в квитанции к заказу в поле «Стоимость доставки», но при этом входит в сумму наложенного платежа и обрабатывается как наложенный платеж.">Дополнительный сбор за доставку, который ИМ берет с получателя</span>';

$_['entry_delivery_recipient_vat_rate']	= '<span data-toggle="tooltip" title="В зависимости от этого значения рассчитывается сумма НДС за Доп. сбор за доставку, которую ИМ берет с получателя">Ставка НДС РФ</span>';

$_['entry_delivery_recipient_vat_sum']	= '<span data-toggle="tooltip" title="рассчитывается как произведение ставки НДС и Доп. сбор за доставку, которую ИМ берет с получателя">Сумма НДС за единицу товара</span>';

$_['entry_seller_name']					= '<span data-toggle="tooltip" title="Используется при печати заказов для отображения настоящего продавца товара, либо торгового названия.">Истинный продавец</span>';
$_['entry_comment']						= '<span data-toggle="tooltip" title="Максимальное количество символов 255">Комментарий</span>';
$_['entry_recipient_name']				= 'Имя получателя';
$_['entry_recipient_telephone']			= '<span data-toggle="tooltip" title="Можно задать несколько через запятую">Телефон получателя</span>';
$_['entry_recipient_email']				= '<span data-toggle="tooltip" title="Используется для рассылки уведомлений о движении заказа и в случае если до получателя не удалось дозвонится.">Email получателя</span>';
$_['entry_recipient_city']				= '<span data-toggle="tooltip" title="Автокомплит">Город получателя</span>';
$_['entry_street']						= 'Улица';
$_['entry_house']						= 'Дом, корпус, строение';
$_['entry_flat']						= 'Квартира/Офис';
$_['entry_pvz']							= 'ПВЗ';
$_['entry_brcode']						= '<span data-toggle="tooltip" title="Параметр используется для оперирования грузом на складах СДЭК.">Штрих-код упаковки</span>';
$_['entry_pack']						= 'Упаковано в коробку';
$_['entry_package']						= '<span data-toggle="tooltip" title="Указывается в сантиметрах.">Габариты упаковки</span>';
$_['entry_order_weight']				= '<span data-toggle="tooltip" title="Указывается в граммах">Вес заказа</span>';
$_['entry_courier_call']				= 'Вызвать курьера';
$_['entry_courier_date']				= 'Дата ожидания курьера';
$_['entry_courier_time']				= 'Время ожидания курьера';
$_['entry_courier_lunch']				= '<span data-toggle="tooltip" title="Если входит во временной диапазон ожидания курьера">Время обеда</span>';
$_['entry_courier_send_phone']			= 'Контактный телефон отправителя';
$_['entry_courier_sender_name']			= 'Отправитель (ФИО)';
$_['entry_add_service']					= 'Дополнительные услуги';
$_['entry_attempt_new_address']			= '<span data-toggle="tooltip" title="Если требуется изменить">Новый адрес доставки</span>';
$_['entry_attempt_recipient_name']		= '<span data-toggle="tooltip" title="Если требуется изменить">Новый получатель</span>';
$_['entry_attempt_phone']				= '<span data-toggle="tooltip" title="Если требуется изменить">Новый номер телефона получателя</span>';
$_['entry_weight_class_id']				= 'Единица измерения веса в граммах';
$_['entry_length_class_id']				= 'Единица измерения длины в миллиметрах';
$_['entry_account']						= 'Учетная запись';
$_['entry_secure_password']				= 'Cекретный код';
$_['entry_new_order_status_id']			= '<span data-toggle="tooltip" title="В очередь на отгрузку покадут только заказы с выбранными статусами. Если не выбрано то в список попадут все заказы.">Статусы заказов для отгрузки</span>';
$_['entry_new_order']					= '<span data-toggle="tooltip" title="Количество дней, в течение которых заказ считается актуальным и попадет список на отгрузку">Актуальность заказа</span>';
$_['entry_shipping_methods']			= 'Фильтр по способу доставки';
$_['entry_payment_methods']				= 'Фильтр по способу оплаты';
$_['entry_packing_min_weight']			= 'Минимальный вес упаковки';
$_['entry_packing_additional_weight']	= '<span data-toggle="tooltip" title="Значение будет добавлен к суммарному весу заказа">Дополнительный вес</span>';
$_['entry_cod']							= 'Наложенный платеж';
$_['entry_cod_default']					= '<span data-toggle="tooltip" title="Значение будет установлено по умолчанию">Наложенный платеж</span>';
$_['entry_replace_items']				= 'Заменить позиции для отгрузки';
$_['entry_replace_item_name']			= 'Название позиции';
$_['entry_replace_item_cost']			= '<span data-toggle="tooltip" title="По умолчанию">Цена за единицу</span>';
$_['entry_replace_item_payment']		= '<span data-toggle="tooltip" title="По умолчанию. Используется если не выбран наложенный платеж">Оплата при получении</span>';
$_['entry_replace_item_amount']			= '<span data-toggle="tooltip" title="По умолчанию: 1">Количество</span>';
$_['entry_use_cron']					= 'Использовать cron для автоматического обновления отправлений';
$_['entry_currency']					= 'Валюта объявленной стоимости заказа (всех вложений)';
$_['entry_currency_agreement']			= '<span data-toggle="tooltip" title="Валюта взаиморасчетов">Валюта договора</span>';
$_['entry_currency_cod']				= '<span data-toggle="tooltip" title="Валюта платежа клиента">Валюта наложенного платежа</span>';

// Button
$_['button_apply']						= 'Применить';
$_['button_create']						= 'Создать отгрузку';
$_['button_new_order']					= 'Заказы на отгрузку';
$_['button_option']						= 'Настройки';
$_['button_send']						= 'Отгрузить';
$_['button_add_attempt']				= 'Добавить расписание';
$_['button_print']						= 'Загрузить';
$_['button_sync']						= 'Синхронизовать';

// Column
$_['column_order_id']					= '№ заказа';
$_['column_customer']					= 'Имя покупателя';
$_['column_status']						= 'Статус';
$_['column_date_added']					= 'Дата добавления';
$_['column_total']						= 'Итого';
$_['column_price']						= 'Цена за единицу';
$_['column_action']						= 'Действие';
$_['column_title']						= 'Наименование';
$_['column_weight']						= '<span data-toggle="tooltip" title="Указывается за единицу товара в граммах.">Вес</span>';
$_['column_payment']					= '<span data-toggle="tooltip" title="Указывается за единицу товара.">Оплата при получении</span>';
$_['column_amount']						= 'Количество';
$_['column_cost']						= 'Стоимость';
$_['column_date']						= '<span data-toggle="tooltip" title="yyyy-mm-dd">Дата доставки</span>';
$_['column_dispatch_number']			= '№ акта/ТТН';
$_['column_dispatch_date']				= 'Дата отгрузки';
$_['column_dispatch_total_orders']		= 'Количество заказов в отправлении';
$_['column_time']						= '<span data-toggle="tooltip" title="Время получателя">Время доставки</span>';
$_['column_additional']					= 'Дополнительно';
$_['column_token']						= 'Токен';
$_['column_value']						= 'Значение';
$_['column_cdek_status']				= 'Статус заказа в системе СДЭК';
$_['column_new_status']					= 'Новый статус заказа';
$_['column_notify']						= 'Уведомить покупателя';
$_['column_comment']					= 'Коментарий';

// Tab
$_['tab_auth']							= 'Авторизация';

$_['tab_order']							= 'Фильтр заказов на отгрузку';
$_['tab_recipient']						= 'Получатель';
$_['tab_package']						= 'Вложения';
$_['tab_schedule']						= 'Расписание доставки';
$_['tab_courier']						= 'Курьер';
$_['tab_additional']					= 'Дополнительно';
$_['tab_currency']						= 'Валюта';
$_['tab_additional_weight']				= 'Дополнительный вес';
$_['tab_status']						= 'Статус заказа';

// Error
$_['error_warning']						= 'Внимательно проверьте форму на ошибки!';
$_['error_permission']					= 'У Вас нет прав для изменения модуля!';
$_['error_load_pvz']					= 'Не удалось загрузить список ПВЗ. Возможно произошел сбой при получении данных. Если после перезагрузки проблема не будет решена, то скорее всего у вас есть ограничения хостинг провайдера!';
$_['error_empty']						= 'Значение не заполнено!';
$_['error_email']						= 'Email заполнен не верно!';
$_['error_domain']						= 'Домен «%s» не найден!';
$_['error_numeric']						= 'Значение должно быть числом!';
$_['error_positive_numeric']			= 'Значение должно быть больше нуля!';
$_['error_tariff_id']					= 'Тариф не выбран!';
$_['error_date']						= 'Дата заполнена не верно!';
$_['error_time']						= 'Время заполнено не верно!';
$_['error_attempt_date_exists']			= 'На указанную дату уже назначено расписание!';
$_['error_time_interval_3']				= 'Временной интервал должен быть не менее 3 часов!';
$_['error_maxlength_255']				= 'Сообщение не должно превышать 255 символов!';
$_['error_date_futured']				= 'Расписание можно указать только на будущую дату!';
$_['error_empty_order_list']			= 'Список заказов пуст!';
$_['error_order_dubl_exists']			= 'Заказ с номером #%s уже отгружен!';
$_['error_invalid_srvicecode']			= 'Услуга не найдена!';
$_['error_sendcitycode']				= 'Город отсутствует в базе СДЭК!';
$_['error_database']					= 'Ошибка обращения к базе данных СДЭК!';
$_['error_auth']						= 'Интернет-магазин не идентифицирован!';
$_['error_cdek_error']					= 'Заказ номер #%s: %s!';
$_['error_not_found_tarifftypecode']	= 'Тариф по переданному направлению не существует!';
$_['error_callcourier_city']			= 'Вызов курьера в выбранном городе невозможен!';
$_['error_callcourier_datetime']		= 'Вызов курьера возможно сделать только на будущую дату!';
$_['error_callcourier_date_dubl']		= 'Дата вызова курьера дублируется в отгрузке!';
$_['error_callcourier_date_exists']		= 'На текущую дату уже есть вызов курьера!';
$_['error_callcourier_time']			= 'Временной диапазон ожидания приезда курьера указан некорректно!';
$_['error_callcourier_timelunch']		= 'Временной диапазон обеденного перерыва указан некорректно!';
$_['error_callcourier_time_interval']	= 'Интервал ожидания курьера должен составлять не менее 3 непрерывных часов!';
$_['error_call_dubl']					= 'Дублирование вызова курьера на одну дату!';
$_['error_cash_no']						= 'Получение наложенного платежа в городе невозможно!';
$_['error_invalid_address_delivery']	= 'Неверный адрес доставки!';
$_['error_invalid_size']				= 'Невалидное значение габаритов!';
$_['error_pvz_weigt_limit']				= 'Ограничение по весу в выбранном ПВЗ!';
$_['error_currency_cod']				= 'Валюта наложенного платежа должна совпадать с валютой договора!';
?>