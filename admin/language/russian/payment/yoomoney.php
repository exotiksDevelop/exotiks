<?php

$_['module_title']             = 'ЮMoney';
$_['heading_title']            = $_['module_title'];
$_['text_yoomoney']            = '<a target="_blank" href="https://yookassa.ru"><img src="../image/payment/yoomoney/yoomoney_logo.png" alt="ЮMoney от ЮKassa" /></a>';
$_['kassa_header_description'] = 'Работая с модулем, вы автоматически соглашаетесь с <a href="https://yoomoney.ru/doc.xml?id=527132">условиями его использования</a>.';
$_['kassa_version_string']     = 'Версия модуля';

$_['kassa_breadcrumbs_extension']     = 'Расширения';
$_['kassa_breadcrumbs_home']          = 'Главная';
$_['kassa_breadcrumbs_logs']          = 'Журнал сообщений';
$_['kassa_text_success']              = 'Success';
$_['kassa_text_success_message']      = 'Настройки были сохранены';
$_['kassa_page_title']                = 'Настройки ЮKassa';
$_['kassa_breadcrumbs_heading_title'] = 'Журнал сообщений платежного модуля ЮMoney';
$_['kassa_test_mode_description']     = 'Вы включили тестовый режим приема платежей. Проверьте, как проходит оплата, и напишите менеджеру ЮKassa. Он выдаст рабочие shopId и Секретный ключ. <a href="https://yookassa.ru/docs/support/payments/onboarding/integration" target="_blank">Инструкция</a>';

$_['kassa_enable_label'] = 'Включить приём платежей через ЮKassa';

$_['kassa_shop_id_label']             = 'shopId';
$_['kassa_shop_id_description']       = 'Скопируйте shopId из личного кабинета ЮKassa';
$_['kassa_shop_id_error_required']    = 'Необходимо указать shopId из личного кабинета ЮKassa';
$_['kassa_error_invalid_credentials'] = '<b>Не получилось связать сайт с личным кабинетом</b><br>Войдите в ЮKassa, чтобы сюда автоматически подгрузились правильные данные. Если не получилось, обратитесь в техподдержку.';

$_['kassa_password_label']          = 'Секретный ключ';
$_['kassa_password_description']    = 'Выпустите и активируйте секретный ключ в <a href="https://yookassa.ru/my" target="_blank">личном кабинете ЮKassa</a>. Потом скопируйте его сюда.';
$_['kassa_password_error_required'] = 'Необходимо указать секретный ключ из личного кабинета ЮKassa';

$_['kassa_payment_mode_label']            = 'Выбор способа оплаты';
$_['kassa_payment_mode_kassa_label']      = 'На стороне ЮKassa';
$_['kassa_use_installments_button_label'] = 'Добавить кнопку «Заплатить по частям» на страницу оформления заказа';
$_['kassa_add_installments_block_label']  = 'Добавить блок «Заплатить по частям» в карточки товаров';
$_['kassa_payment_mode_shop_label']       = 'На стороне магазина';

$_['kassa_payment_method_bank_card']    = 'Банковские карты';
$_['kassa_payment_method_sberbank']     = 'SberPay';
$_['kassa_payment_method_cash']         = 'Наличные через терминалы';
$_['kassa_payment_method_qiwi']         = 'QIWI Wallet';
$_['kassa_payment_method_alfabank']     = 'Альфа-Клик';
$_['kassa_payment_method_webmoney']     = 'Webmoney';
$_['kassa_payment_method_yoo_money']    = 'ЮMoney';
$_['kassa_payment_method_mobile']       = 'Баланс мобильного';
$_['kassa_payment_method_installments'] = 'Заплатить по частям';
$_['kassa_payment_method_tinkoff_bank'] = 'Интернет-банк Тинькофф';
$_['kassa_payment_method_widget']       = 'Платёжный виджет ЮKassa (карты, Apple Pay и Google Pay)';

$_['kassa_payment_method_error_required'] = 'Пожалуйста, выберите хотя бы один способ из списка';

$_['kassa_display_name_label']       = 'Название платежного сервиса';
$_['kassa_display_name_description'] = 'Это название увидит пользователь';
$_['kassa_default_display_name']     = 'ЮKassa (банковские карты, электронные деньги и другое)';

$_['kassa_currency']                     = 'Валюта платежа в ЮKassa';
$_['kassa_currency_convert']             = 'Конвертировать сумму из текущей валюты магазина';
$_['kassa_currency_help']                = 'Валюты в ЮKassa и в магазине должны совпадать';
$_['kassa_currency_convert_help']        = 'Используется значение из списка валют магазина. Если валюты нет в списке – курс ЦБ РФ.';

$_['kassa_send_receipt_label']           = 'Отправлять в ЮKassa данные для чеков (54-ФЗ)';
$_['kassa_send_receipt_tax_rate_title']  = 'НДС';
$_['kassa_second_receipt_header']        = 'Второй чек';
$_['kassa_second_receipt_enable']        = 'Включен';
$_['kassa_second_receipt_disable']       = 'Отключен';
$_['kassa_second_receipt_description']   = 'Два чека нужно формировать, если покупатель вносит предоплату и потом получает товар или услугу. Первый чек — когда деньги поступают вам на счёт, второй — при отгрузке товаров или выполнении услуг.<br> <a href="#">Читать про второй чек в ЮKassa</a>';
$_['kassa_second_receipt_enable_label']  = 'Формировать второй чек при переходе заказа в статус';
$_['kassa_second_receipt_help_info']     = 'Если в заказе будут позиции с признаками «Полная предоплата» — второй чек отправится автоматически, когда заказ перейдёт в выбранный статус.';
$_['kassa_second_receipt_history_info']  = 'Отправлен второй чек. Сумма %s рублей.';
$_['kassa_tax_system_default_label']     = 'Система налогообложения по умолчанию';
$_['kassa_tax_system_default_description'] = 'Выберите систему налогообложения по умолчанию. Параметр необходим, только если у вас несколько систем налогообложения, в остальных случаях не передается.';
$_['kassa_tax_system_1_label']           = 'Общая система налогообложения';
$_['kassa_tax_system_2_label']           = 'Упрощенная (УСН, доходы)';
$_['kassa_tax_system_3_label']           = 'Упрощенная (УСН, доходы минус расходы)';
$_['kassa_tax_system_4_label']           = 'Единый налог на вмененный доход (ЕНВД)';
$_['kassa_tax_system_5_label']           = 'Единый сельскохозяйственный налог (ЕСН)';
$_['kassa_tax_system_6_label']           = 'Патентная система налогообложения';
$_['kassa_tax_rate_default_label']       = 'Ставка по умолчанию';
$_['kassa_tax_rate_default_description'] = 'Ставка по умолчанию будет в чеке, если в карточке товара не указана другая ставка.';
$_['kassa_tax_rate_1_label']             = 'Без НДС';
$_['kassa_tax_rate_2_label']             = '0%';
$_['kassa_tax_rate_3_label']             = '10%';
$_['kassa_tax_rate_4_label']             = '20%';
$_['kassa_tax_rate_5_label']             = 'Расчетная ставка 10/110';
$_['kassa_tax_rate_6_label']             = 'Расчетная ставка 20/120';
$_['kassa_tax_rate_table_caption']       = 'Сопоставьте ставки';
$_['kassa_shop_tax_rate_header']         = 'Ставка в вашем магазине';
$_['kassa_kassa_tax_rate_header']        = 'Ставка для чека в налоговую';

$_['kassa_notification_url_label']       = 'Адрес для уведомлений';
$_['kassa_notification_url_description'] = 'Этот адрес понадобится, только если его попросят специалисты ЮKassa';

$_['kassa_before_redirect_label'] = 'Когда пользователь переходит к оплате';
$_['kassa_create_order_label']    = 'Создать неоплаченный заказ в панели управления';
$_['kassa_clear_cart_label']      = 'Удалить товары из корзины';

$_['kassa_success_order_status_label']       = 'Статус заказа после оплаты';
$_['kassa_success_order_status_description'] = '';

$_['kassa_minimum_payment_amount_label']       = 'Минимальная сумма заказа';
$_['kassa_minimum_payment_amount_description'] = 'Сумма заказа при которой можно провести платёж с помощью ЮKassa';

$_['kassa_geo_zone_label']       = 'Регион отображения';
$_['kassa_geo_zone_description'] = 'Геозона в которой будет отображаться способ оплаты';
$_['kassa_any_geo_zone']         = 'Любая зона';

$_['kassa_debug_log_label']       = 'Debug log';
$_['kassa_debug_log_description'] = 'Подробное логгирование процесса проведения оплаты';
$_['kassa_debug_log_off']         = 'Выключить';
$_['kassa_debug_log_on']          = 'Включить';
$_['kassa_view_logs']             = 'Просмотр журнала сообщений';

$_['kassa_sort_order_label']       = 'Сортировка';
$_['kassa_sort_order_description'] = '';

$_['kassa_invoice_label'] = 'Выставление счетов по электронной почте';

$_['kassa_invoice_heading_label']       = 'Шаблон письма';
$_['kassa_invoice_subject_label']       = 'Тема';
$_['kassa_invoice_subject_default']     = 'Оплата заказа %order_id%';
$_['kassa_invoice_subject_description'] = 'Номер заказа (значение %order_id%) подставится автоматически';
$_['kassa_invoice_message_label']       = 'Дополнительный текст';
$_['kassa_invoice_message_description'] = 'Этот текст появится в письме после суммы и кнопки "Заплатить": напишите здесь важную для покупателя информацию или оставьте поле пустым';

$_['kassa_invoice_logo_label'] = 'Добавить к письму логотип магазина';

$_['kassa_invoices_kassa_disabled']   = 'Этот функционал доступен только для оплаты через ЮKassa';
$_['kassa_invoices_disabled']         = 'Этот функционал отключен в настройках модуля ЮKassa';
$_['kassa_invoices_invalid_order_id'] = 'Идентификатор заказа не был передан или не валиден';
$_['kassa_invoices_order_not_exists'] = 'Указанный заказ не найден';

$_['kassa_refund_status_pending_label']   = 'В ожидании';
$_['kassa_refund_status_succeeded_label'] = 'Проведён';
$_['kassa_refund_status_canceled_label']  = 'Отменён';
$_['kassa_refund_sum_error_int']          = 'Сумма должна быть числом';
$_['kassa_refund_sum_error']              = 'Не верная сумма возврата';
$_['kassa_refund_comment_error']          = 'Укажите комментарий к возврату';
$_['kassa_refund_failed']                 = 'Не удалось провести возврат';

$_['kassa_breadcrumbs_payments']    = 'Список платежей через модуль ЮKassa';
$_['kassa_payments_page_title']     = 'Список платежей через модуль ЮKassa';
$_['kassa_payments_update_button']  = 'Обновить список';
$_['kassa_payments_capture_button'] = 'Провести все платежи';
$_['kassa_payment_list_label']      = 'Список платежей через модуль ЮKassa';
$_['kassa_payment_list_link']       = 'Открыть список';

$_['kassa_tab_header']   = 'ЮKassa';
$_['wallet_tab_header']  = 'ЮMoney';

$_['wallet_page_title']         = 'Настройки ЮMoney';
$_['wallet_header_description'] = 'Для работы с модулем нужно открыть <a href=\'https://yoomoney.ru/new\' target=\'_blank\'>кошелек</a> ЮMoney.';
$_['wallet_version_string']     = 'Версия модуля';

$_['wallet_enable_label']              = 'Включить прием платежей в кошелек ЮMoney';
$_['wallet_account_id_label']          = 'Номер кошелька';
$_['wallet_account_id_description']    = '';
$_['wallet_account_id_error_required'] = 'Укажите номер кошелька';

$_['wallet_password_label']          = 'Секретное слово';
$_['wallet_password_description']    = 'Секретное слово нужно скопировать <a href=\'https://yoomoney.ru/transfer/myservices/http-notification\' target=\'_blank\'>со страницы настройки уведомлений</a> на сайте ЮMoney';
$_['wallet_password_error_required'] = 'Укажите секретное слово';

$_['wallet_display_name_label']       = 'Название платежного сервиса';
$_['wallet_display_name_description'] = 'Это название увидит пользователь';
$_['wallet_default_display_name']     = 'ЮMoney (банковские карты, кошелек)';

$_['wallet_notification_url_label']       = 'RedirectURL';
$_['wallet_notification_url_description'] = 'Скопируйте эту ссылку в поле Redirect URI <a href=\'https://yoomoney.ru/transfer/myservices/http-notification\' target=\'_blank\'>со страницы настройки уведомлений</a> на сайте ЮMoney';

$_['wallet_before_redirect_label'] = 'Когда пользователь переходит к оплате';
$_['wallet_create_order_label']    = 'Создать неоплаченный заказ в панели управления';
$_['wallet_clear_cart_label']      = 'Удалить товары из корзины';

$_['wallet_success_order_status_label']       = 'Статус заказа после оплаты';
$_['wallet_success_order_status_description'] = '';

$_['wallet_minimum_payment_amount_label']       = 'Минимальная сумма заказа';
$_['wallet_minimum_payment_amount_description'] = 'Сумма заказа, при которой можно провести платёж';

$_['wallet_geo_zone_label']       = 'Регион отображения';
$_['wallet_geo_zone_description'] = 'Геозона в которой будет отображаться способ оплаты';
$_['wallet_any_geo_zone']         = 'Любая зона';

$_['wallet_sort_order_label']       = 'Сортировка';
$_['wallet_sort_order_description'] = '';

$_['text_success']                 = 'Настройки сохранены';
$_['ok'] = 'OK';
$_['cancel'] = 'Cancel';
$_['delete'] = 'Удалить';

// market
$_['p2p_sv']                = 'Сохранить';
$_['p2p_text_connect']      = "Для работы с модулем нужно <a href='https://yoomoney.ru/new' target='_blank'>открыть кошелек</a> ЮMoney и <a href='https://yoomoney.ru/myservices/new' target='_blank'>зарегистрировать приложение</a> на сайте ЮMoney";
$_['p2p_text_enable']       = "Включить прием платежей в кошелек ЮMoney";
$_['p2p_text_url_help']     = "Скопируйте эту ссылку в поле Redirect URI на <a href='https://yoomoney.ru/myservices/new' target='_blank'>странице регистрации приложения</a>";
$_['p2p_text_setting_head'] = "Настройки приема платежей";
$_['p2p_text_account']      = "Номер кошелька";
$_['p2p_text_appId']        = "Id приложения";
$_['p2p_text_appWord']      = "Секретное слово";
$_['p2p_text_app_help']     = "ID и секретное слово вы получите после регистрации приложения на сайте ЮMoney";
$_['p2p_text_extra_head']   = "Дополнительные настройки для администратора";
$_['p2p_text_debug']        = "Запись отладочной информации";
$_['p2p_text_off']          = "Отключена";
$_['p2p_text_on']           = "Включена";
$_['p2p_text_debug_help']   = "Настройку нужно будет поменять, только если попросят специалисты ЮMoney";
$_['p2p_text_status']       = "Статус заказа после оплаты";
// MWS
$_['lbl_mws_main']    = 'Настройка взаимодействия по протоколу MWS (<a target="_blank" href="https://yookassa.ru/docs/payment-solution/payment-management/basics">Merchant Web Services</a>)';
$_['txt_mws_main']    = 'Для работы с MWS необходимо получить в ЮMoney специальный сертификат и загрузить его в приложении.';
$_['lbl_mws_crt']     = 'Сертификат';
$_['lbl_mws_connect'] = 'Как получить сертификат';
$_['txt_mws_connect'] = 'Скачайте <a href="%s">готовый запрос на сертификат</a> (файл в формате .csr).';
$_['lbl_mws_doc']     = 'Данные для заполнения заявки';
$_['txt_mws_doc']     = 'Скачайте <a target="_blank"  href="https://yoomoney.ru/i/html-letters/SSL_Cert_Form.doc">заявку на сертификат</a>. Ее нужно заполнить, распечатать, поставить подпись и печать. Внизу страницы — таблица с данными для заявки, просто скопируйте их. Отправьте файл запроса вместе со сканом готовой заявки менеджеру ЮMoney на <a href="mailto:merchants@yoomoney.ru">merchants@yoomoney.ru</a>.';
$_['txt_mws_cer']     = 'Загрузите сертификат, который пришлет вам менеджер, наверху этой страницы.';

$_['lbl_mws_cn']    = 'CN';
$_['lbl_mws_email'] = 'Email техника';

$_['tab_mws_before']  = 'Скопируйте эти данные в таблицу. Остальные строчки заполните самостоятельно.';
$_['tab_row_sign']    = 'Электронная подпись на сертификат';
$_['tab_row_cause']   = 'Причина запроса';
$_['tab_row_primary'] = 'Первоначальный';


$_['btn_mws_gen']      = 'Сформировать запрос на сертификат (CSR)';
$_['btn_mws_csr']      = 'Скачать запрос на сертификат (CSR)';
$_['btn_mws_doc']      = 'Скачать для заполнения';
$_['btn_mws_crt']      = 'Обзор';
$_['btn_mws_crt_load'] = 'Загрузить';

$_['success_mws_alert'] = "<p class='alert alert-success'>Модуль настроен для работы с платежами и возвратами. Сертификат загружен.</p>
    <p>Посмотреть информацию о платеже или сделать возврат можно в <a href='%s' target='blank'>списке заказов</a></p>
    <p><a href='%s' id='mws_csr_gen'>Сбросить настройки</a></p>";
$_['lbl_mws_alert']     = "Все настройки для работы с MWS будут стерты. Сертификат нужно будет запросить повторно. Вы действительно хотите сбросить настройки MWS?";
$_['ext_mws_openssl']   = 'Отсутствует расширения openssl';
$_['err_mws_kassa']     = 'Отключен модуль ЮKassa';
$_['err_mws_shopid']    = 'Отсутствует идентификатор магазина (shopId)';


// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
$_['active_on']        = 'Включено';
$_['active_off']       = 'Выключено';
$_['active']           = 'Активность';
$_['mod_off']          = '<a href="%s">Установите модуль "ЮMoney"</a> на вкладке модулей оплаты';
$_['error_install_widget'] = 'Чтобы покупатели могли заплатить вам через Apple Pay, <a href="https://yookassa.ru/docs/merchant.ru.yandex.kassa">скачайте файл apple-developer-merchantid-domain-association</a> и добавьте его в папку ./well-known на вашем сайте. Если не знаете, как это сделать, обратитесь к администратору сайта или в поддержку хостинга. Не забудьте также подключить оплату через Apple Pay <a href="https://yookassa.ru/my/payment-methods/settings#applePay">в личном кабинете ЮKassa</a>. <a href="https://yookassa.ru/developers/payment-forms/widget#apple-pay-configuration">Почитать о подключении Apple Pay в документации ЮKassa</a>';
$_['error_enabled_type_payment'] = 'Невозможно одновременно включить оплату через ЮMoney и ЮKassa';


//Updater
$_['update_tab_header']                  = 'Обновление модуля';
$_['updater_success_message']            = 'Версия модуля %s была успешно загружена и установлена';
$_['updater_error_unpack_failed']        = 'Не удалось распаковать загруженный архив %s, подробную информацию о произошедшей ошибке можно найти в <a href="">логах модуля</a>';
$_['updater_error_backup_create_failed'] = 'Не удалось создать бэкап установленной версии модуля, подробную информацию о произошедшей ошибке можно найти в <a href="%s">логах модуля</a>';
$_['updater_error_archive_load']         = 'Не удалось загрузить архив с новой версией, подробную информацию о произошедшей ошибке можно найти в <a href="%s">логах модуля</a>';
$_['updater_restore_backup_message']     = 'Версия модуля %s была успешно восстановлена из бэкапа %s';
$_['updater_error_restore_backup']       = 'Не удалось восстановить данные из бэкапа, подробную информацию о произошедшей ошибке можно найти в <a href="%s">логах модуля</a>';
$_['updater_backup_deleted_message']     = 'Бэкап %s был успешно удалён';
$_['updater_error_delete_backup']        = 'Не удалось удалить бэкап %s, подробную информацию о произошедшей ошибке можно найти в <a href="%s">логах модуля</a>';
$_['updater_error_create_directory']     = 'Не удалось создать директорию %s';
$_['updater_error_load']                 = 'Не удалось загрузить архив с обновлением';
$_['updater_header_text']                = 'Здесь будут появляться новые версии модуля — с новыми возможностями или с исправленными ошибками. Чтобы установить новую версию модуля, нажмите кнопку «Обновить».';
$_['updater_about_title']                = 'О модуле';
$_['updater_current_version']            = 'Установленная версия модуля';
$_['updater_last_version']               = 'Последняя доступная версия модуля';
$_['updater_last_check_date']            = 'Дата проверки наличия новой версии';
$_['updater_check_updates']              = 'Проверить наличие обновлений';
$_['updater_history_title']              = 'История изменений:';
$_['updater_update']                     = 'Обновить модуль';
$_['updater_error_load']                 = 'Не удалось загрузить архив с обновлением';
$_['updater_last_version_installed']     = 'Установлена последняя версия модуля.';
$_['updater_backups_title']              = 'Резервные копии';
$_['updater_module_version']             = 'Версия модуля';
$_['updater_date_create']                = 'Дата создания';
$_['updater_file_name']                  = 'Имя файла';
$_['updater_file_size']                  = 'Размер файла';
$_['updater_restore']                    = 'Восстановить';
$_['updater_delete']                     = 'Удалить';
$_['updater_delete_message']             = 'Вы действительно хотите удалить бэкап модуля версии ';
$_['updater_restore_message']            = 'Вы действительно хотите восстановить модуль из бэкапа версии';

$_['invoice_sum_text']         = 'К оплате %s руб.';
$_['invoice_greeting']         = 'Здравствуйте';
$_['invoice_thanks']           = 'Магазин %s благодарит вас за заказ и просит оплатить счет №&nbsp;%s.';
$_['invoice_receipt_header']   = 'В вашем заказе';
$_['invoice_currency']         = 'руб';
$_['invoice_yoomoney_text']    = 'Заплатить через ЮMoney';
$_['invoice_sum_label']        = 'К оплате';
$_['invoice_footer_text']      = 'Счет выставлен через';
$_['invoice_footer_text_yoomoney'] = 'модуль ЮMoney';

$_['payments_list_header_id']            = 'ID заказа';
$_['payments_list_header_payment_id']    = 'ID платежа';
$_['payments_list_header_sum']           = 'Сумма';
$_['payments_list_header_paid']          = 'Оплачен';
$_['payments_list_header_status']        = 'Статус';
$_['payments_list_header_date_create']   = 'Дата создания';
$_['payments_list_header_date_captured'] = 'Дата подтверждения';

$_['log_title']    = 'Журнал сообщений платежного модуля ЮMoney';
$_['log_download'] = 'Скачать файл сообщений';
$_['log_clear']    = 'Очистить файл сообщений';
$_['log_empty']    = 'Журнал сообщений пуст.';

$_['refunds_title']              = 'Возвраты';
$_['refunds_new']                = 'Новый возврат';
$_['refunds_payment_data']       = 'Данные платежа';
$_['refunds_payment_id']         = 'Номер транзакции в ЮKassa';
$_['refunds_order_id']           = 'Номер заказа';
$_['refunds_payment_method']     = 'Способ оплаты';
$_['refunds_payment_sum']        = 'Сумма платежа';
$_['refunds_refund_data']        = 'Данные возврата';
$_['refunds_refund_sum']         = 'Сумма возврата';
$_['refunds_refund_cause']       = 'Причина возврата';
$_['refunds_refund_create']      = 'Создать возврат';
$_['refunds_history']            = 'История возвратов';
$_['refunds_history_empty']      = 'Для заказа №%s возвраты не проводились.';
$_['refunds_header_id']          = 'ID возврата';
$_['refunds_header_status']      = 'Статус';
$_['refunds_header_date_create'] = 'Дата создания';
$_['refunds_header_sum']         = 'Сумма';

$_['kassa_show_url_link']             = 'Показывать ссылку на сайт ЮKassa';
$_['kassa_show_url_link_description'] = 'Ссылка будет отображаться в подвале вашего сайта.';
$_['text_payment_on_hold']            = 'Платеж на удержании';

//Подтверждение платежа
$_['kassa_hold_setting_label']         = 'Включить отложенную оплату';
$_['kassa_hold_setting_description']   = 'Если опция включена, платежи с карт проходят в 2 этапа: у клиента сумма замораживается, и вам вручную нужно подтвердить её списание – через панель администратора';
$_['kassa_statuses_description_label'] = 'Какой статус присваивать заказу, если он:';
$_['kassa_hold_order_status_label']    = 'ожидает подтверждения';
$_['kassa_cancel_order_status_label']  = 'отменён';

$_['captures_title']                  = 'Подтверждение платежа';
$_['captures_expires_date']           = 'Подтвердить до';
$_['captures_new']                    = 'Подтверждение платежа';
$_['captures_payment_data']           = 'Данные платежа';
$_['captures_payment_id']             = 'Номер транзакции в ЮKassa';
$_['captures_order_id']               = 'Номер заказа';
$_['captures_payment_method']         = 'Способ оплаты';
$_['captures_payment_sum']            = 'Сумма платежа';
$_['captures_capture_data']           = '';
$_['captures_capture_sum']            = 'Сумма подтверждения';
$_['captures_capture_create']         = 'Подтвердить платеж';
$_['cancel_payment_button']           = 'Отменить платеж';
$_['capture_payment_success_message'] = 'Платеж подтвержден успешно';
$_['capture_payment_fail_message']    = 'Ошибка подтверждения платежа';
$_['cancel_payment_success_message']  = 'Платеж отменен успешно';
$_['cancel_payment_fail_message']     = 'Ошибка отмены платежа';

$_['column_product']  = 'Наименование товара';
$_['column_quantity'] = 'Количество товара';
$_['column_price']    = 'Цена товара';
$_['column_total']    = 'Итого';

$_['kassa_payment_description_label']       = 'Описание платежа';
$_['kassa_payment_description_description'] = 'Это описание транзакции, которое пользователь увидит при оплате, а вы — в личном кабинете ЮKassa. Например, «Оплата заказа №72». Чтобы в описание подставлялся номер заказа (как в примере), поставьте на его месте %order_id% (Оплата заказа %order_id%). Ограничение для описания — 128 символов.';
$_['kassa_default_payment_description']     = 'Оплата заказа №%order_id%';

$_['nps_text'] = 'Помогите нам улучшить модуль ЮKassa — ответьте на %s один вопрос %s';

$_['b2b_sberbank_label']             = 'Включить платежи через Сбербанк Бизнес Онлайн';
$_['b2b_sberbank_on_label']          = 'Если эта опция включена, вы можете принимать онлайн-платежи от юрлиц. Подробнее — <a href="https://yookassa.ru">на сайте ЮKassa.</a>';
$_['b2b_sberbank_template_label']    = 'Шаблон для назначения платежа';
$_['b2b_sberbank_vat_default_label'] = 'Ставка НДС по умолчанию';
$_['b2b_sberbank_template_help']     = 'Это назначение платежа будет в платёжном поручении.';
$_['b2b_sberbank_vat_default_help']  = 'Эта ставка передаётся в Сбербанк Бизнес Онлайн, если в карточке товара не указана другая ставка.';
$_['b2b_sberbank_vat_label']         = 'Сопоставьте ставки НДС в вашем магазине со ставками для Сбербанка Бизнес Онлайн';
$_['b2b_sberbank_vat_cms_label']     = 'Ставка НДС в вашем магазине';
$_['b2b_sberbank_vat_sbbol_label']   = 'Ставка НДС для Сбербанк Бизнес Онлайн';
$_['b2b_tax_rate_untaxed_label']     = 'Без НДС';
$_['b2b_tax_rate_7_label']           = '7%';
$_['b2b_tax_rate_10_label']          = '10%';
$_['b2b_tax_rate_18_label']          = '18%';
$_['b2b_tax_rate_20_label']          = '20%';
$_['b2b_sberbank_tax_message']       = 'При оплате через Сбербанк Бизнес Онлайн есть ограничение: в одном чеке могут быть только товары с одинаковой ставкой НДС. Если клиент захочет оплатить за один раз товары с разными ставками — мы покажем ему сообщение, что так сделать не получится.';

$_['kassa_default_payment_mode_label']             = 'Признак способа расчета';
$_['kassa_default_payment_subject_label']          = 'Признак предмета расчета';
$_['kassa_default_delivery_payment_mode_label']    = 'Признак способа расчета для доставки';
$_['kassa_default_delivery_payment_subject_label'] = 'Признак предмета расчета для доставки';

$_['kassa_voucher_payment_mode_default_label']    = 'Признак способа расчета для сертификата';
$_['kassa_voucher_payment_subject_default_label'] = 'Признак предмета расчета для сертификата';
$_['kassa_voucher_default_description']           = 'Признаки предмета расчёта и способа расчёта для сертификатов будут браться из следующих полей:';

$_['kassa_auth_connect_title']     = 'Свяжите ваш сайт на OpenCart с личным кабинетом ЮKassa';
$_['kassa_auth_connect_error']     = 'Что-то пошло не так. Перезагрузите страницу и попробуйте ещё раз.';
$_['kassa_auth_connect_btn_title'] = 'Подключить магазин';
$_['kassa_auth_change_btn_title']  = 'Сменить магазин';
$_['kassa_auth_test_shop']         = 'Тестовый магазин';
$_['kassa_auth_real_shop']         = 'Боевой магазин';
$_['kassa_auth_help']              = '<b>Где найти ShopID и секретный ключ</b><br>Данные автоматически подтянутся сюда из&nbsp;личного кабинета. Для этого нажмите на&nbsp;<b>Сменить магазин</b>:<br>&mdash;&nbsp;во&nbsp;всплывающем окне войдите в&nbsp;ЮKassa<br>&mdash;&nbsp;разрешите поделиться данными с&nbsp;OpenCart';
$_['kassa_auth_enable_54fz_title'] = 'Чтобы онлайн-касса заработала, включите отправку в ЮKassa данных для чеков';
$_['kassa_auth_enable_54fz']       = '<b>Важно</b>: если в личном кабинете ЮKassa вы выбрали не связывать платёж и чек, то ставить галочку не нужно.';
$_['kassa_auth_switch_mode']       = '<b>Чтобы перейти с тестового магазина на настоящий, нажмите на кнопку «Сменить магазин»</b><br/> Во всплывающем окне войдите в личный кабинет, разрешите доступ к ЮKassa и выберите нужный магазин.';
$_['kassa_auth_connection_error']  = '<b>Не получилось связать сайт с личным кабинетом</b><br/>Переподключите ЮKassa. Если не получилось, обратитесь в техподдержку.';
$_['kassa_auth_connect_to_kassa']  = 'Переподключить ЮKassa';

$_['kassa_logs_heading_title'] = 'Журнал сообщений платежного модуля ЮMoney';
$_['log_download'] = 'Скачать файл сообщений';
$_['log_clear']    = 'Очистить файл сообщений';
$_['log_empty']    = 'Журнал сообщений пуст.';