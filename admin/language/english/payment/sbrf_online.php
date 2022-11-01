<?php
// Heading
$_['heading_title']       = 'Sberbank-Online, v1.3';

// Text 
$_['text_payment']        = 'Payment';
$_['text_success']        = 'Success: You have modified "%s" details!';
$_['text_edit']           = 'Edit';
$_['text_sbrf_online']    = '<a style="cursor: pointer;" onclick="window.open(\'https://online.sberbank.ru\');"><img src="view/image/payment/sbrf_online.png" alt="Сбербанк-Онлайн" title="Сбербанк-Онлайн"/></a>';
$_['text_copyright']      = '';
$_['text_bank_default']   = '<b>Только для граждан РФ: </b>
Если вы - счастливый обладатель карты СБРФ, вы можете осуществить платеж через Сбербанк Онлайн.
В Сбербанке Онлайн выберите пункт меню "Платежи и переводы", далее "перевод клиенту Сбербанка".
Переведите сумму в размере {total} на карту: 5469 0000 0000 1111 (Василий Иванович П.).
В сообщении (комментарии) получателю укажите: "За заказ №{order_id}"';
$_['text_page_success_default'] = '<p>На Ваш e-mail отправлено письмо с детализацией заказа и инструкцией по оплате (например, для оплаты через Сбербанк-Онлайн)</p><p>Вы можете просматривать историю заказов в <a href="%s">Личном кабинете</a>, открыв <a href="%s">Историю заказов</a>.</p>Если Ваша покупка связана со скачиваемым файлом, вы можете перейти на страницу <a href="%s">файлов для скачивания</a> для их просмотра.</p><p>Все вопросы направляйте <a href="%s">нам</a>.</p><p>Спасибо за покупки в нашем интернет-магазине!</p>';

// Entry
$_['entry_bank']          = 'Bank Transfer Instructions:';
$_['entry_page_success']  = 'The text on the page, a successful checkout:';
$_['entry_title']         = 'Title:';
$_['entry_description']   = 'Description:';
$_['entry_icon']          = 'Enabled icon to the name of the payment method';
$_['entry_minimal_order'] = 'The minimum value of the order';
$_['entry_maximal_order'] = 'The maximum value of the order';
$_['entry_order_status']  = 'Order Status:';
$_['entry_geo_zone']      = 'Geo Zone:';
$_['entry_status']        = 'Status:';
$_['entry_sort_order']    = 'Sort Order:';

// Placehoder
$_['help_bank']           = 'Variebles:<br />{order_id} - Order ID<br />{total} - Total<br />{shipping_total} - Shipping Total';
$_['help_page_success']   = 'If the field is not empty, after ordering the buyer will be directed to a page: payment/sbrf_online/sbrf_online_success<br />If the field is blank - after ordering the buyer will get on a standard page: checkout/success';
$_['help_title']          = 'The name of the payment method on the checkout page';
$_['help_description']    = 'Additional description on the checkout page. To display it requires editing a file checkout \ payment_method.tpl your template (see. 2 of README.TXT in the root distribution). If you are using the module \'Simple - simplified registration and order\' - additional amendments do not require.';
$_['help_minimal_order']  = 'If your order amount is less than the specified amount, and the amount is not empty and not zero, this payment method will not be available during the ordering process.<br />Sample: 190.90';
$_['help_maximal_order']  = 'If your order amount more than the specified amount, and the amount is not empty and not zero, this payment method will not be available during the ordering process.<br />Sample: 5000.01';

$_['title_default']       = 'Sberbank-Online';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify payment bank transfer!';
$_['error_bank']          = 'Bank Transfer Instructions Required!';
?>