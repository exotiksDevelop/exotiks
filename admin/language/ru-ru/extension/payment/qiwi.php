<?php

/** @noinspection HtmlUnknownTarget */

// Heading
$_['heading_title']         = 'QIWI Касса';

// Text
$_['text_qiwi']             = '<a target="_blank" href="https://kassa.qiwi.com/"><img src="view/image/payment/qiwi.png" alt="QIWI Касса" title="QIWI Касса" style="border: 1px solid #eee;"/></a>';
$_['text_supports']         = 'Оплата через: VISA, MasterCard, МИР, Баланс телефона, QIWI Кошелек';
$_['text_description']      = '<p>Для начала работы с сервисом QIWI Касса необходима <a href="https://kassa.qiwi.com/" target="_blank">регистрация магазина</a>.</p>'
                            . '<p>Так же, для вас доступен <a href="https://developer.qiwi.com/demo/" target="_blank">демонстрационный стенд</a>.</p>';
$_['text_extension']        = 'Расширения';
$_['text_success']          = 'Успех: Вы изменили платежный модуль QIWI Касса!';
$_['text_edit']             = 'Настроить QIWI Касса';
$_['text_enabled']          = 'Включен';
$_['text_disabled']         = 'Отключен';
$_['text_popup_enabled']    = 'На странице магазина, через popup';
$_['text_popup_disabled']   = 'На внешнем сайте, по ссылке';
$_['text_payment_info']     = 'Платежные данные';
$_['text_from']             = 'С:';
$_['text_to']               = 'По:';
$_['text_bill_title']       = 'Счет';
$_['text_bill_amount']      = 'Сумма';
$_['text_bill_status']      = 'Статус';
$_['text_bill_date']        = 'Срок жизни';
$_['text_bill_action']      = 'Действия';
$_['text_reject']           = 'Отменить';
$_['text_reject_success']   = 'Будет отмена по счету.';
$_['text_refund']           = 'Возврат';
$_['text_refund_success']   = 'Будет возврат по счету.';

// Tabs
$_['tab_general']           = 'Общие настройки';
$_['tab_qiwi']              = 'Интеграция Qiwi Касса';
$_['tab_order_status']      = 'Статус заказа';

// Entry
$_['entry_status']          = 'Статус';
$_['entry_title']           = 'Название';
$_['entry_description']     = 'Описание';
$_['entry_sort_order']      = 'Порядок сортировки';
$_['entry_total']           = 'Всего';
$_['entry_geo_zone']        = 'Гео-зона';
$_['entry_notification']    = 'Адрес для уведомлений';
$_['entry_key_secret']      = 'Секретный ключ';
$_['entry_key_public']      = 'Открытый ключ';
$_['entry_theme_code']      = 'Код стиля темы';
$_['entry_live_time']       = 'Живое время';
$_['entry_popup']           = 'Форма оплаты';
$_['entry_debug']           = 'Режим отладки';
$_['entry_waiting_status']  = 'Статус "ожидание оплаты"';
$_['entry_paid_status']     = 'Статус "оплачен"';
$_['entry_rejected_status'] = 'Статус "отменен"';
$_['entry_expired_status']  = 'Статус "истек срок оплаты"';
$_['entry_partial_status']  = 'Статус возврата "частичный"';
$_['entry_full_status']     = 'Статус возврата "полный"';
$_['entry_refund_amount']   = 'Возвращаемая сумма';

// Help
$_['help_status']           = '';
$_['help_title']            = 'Название способа оплаты отображается для клиентов.';
$_['help_description']      = 'Описание способа оплаты отображается для клиентов.';
$_['help_sort_order']       = '';
$_['help_total']            = 'Общая сумма заказа должна быть достигнута, прежде чем этот метод оплаты станет активным.';
$_['help_geo_zone']         = '';
$_['help_notification']     = 'Установите это значение в настройках магазина платежной системы.';
$_['help_key_secret']       = 'Секретный ключ к платежной системе для вашего магазина.';
$_['help_key_public']       = 'Открытый ключ к платежной системе для вашего магазина.';
$_['help_theme_code']       = 'Код персонализации стиля платежной формы представлен в настройках магазина платежной системы.';
$_['help_live_time']        = 'Время жизни неоплаченного счета в днях.';
$_['help_popup']            = '';
$_['help_debug']            = 'Вести журнал API-запросов.';
$_['help_waiting_status']   = '';
$_['help_paid_status']      = '';
$_['help_rejected_status']  = '';
$_['help_expired_status']   = '';
$_['help_partial_status']   = '';
$_['help_full_status']      = '';

// Error
$_['error_permission']       = 'Предупреждение: Вам не разрешено изменять настроики QIWI Касса!';
$_['error_order_permission'] = 'Предупреждение: Вам не разрешено изменять заказ!';
$_['error_required']         = 'Предупреждение: Изменения не сохранены, поскольку не заданы необходимые настройки!';
$_['error_required_field']   = 'Это поле обязательно для заполнения.';
