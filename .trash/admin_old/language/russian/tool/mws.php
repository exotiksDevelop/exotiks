<?php
// Heading
$_['heading_title']            = 'Управление денежными средствами';

// Text
$_['tab_return']                 = 'Возврат';
$_['tab_history']                = 'История';

$_['text_order_id']       = 'Номер заказа';
$_['lbl_mws_inv']       = 'Номер транзакции (Яндекс.Касса)';
$_['text_payment_method']      = 'Способ оплаты:';
$_['text_total']               = 'Сумма оплаты:';
$_['text_return_total']        = 'Возвращено:';
$_['text_amount']	= 'Вернуть';
$_['text_cause']	= 'Причина возврата';
$_['btn_return']	=	"Сделать возврат";
$_['text_return_success']	= 'Платеж успешно возвращен';

$_['text_history']        = 'Список возвратов';
$_['tbl_head_date']	= 'Дата возврата';
$_['tbl_head_amount']	= 'Сумма возврата';
$_['tbl_head_cause']	= 'Причина возврата';

$_['text_history_empty']	=	"Успешные возвраты по данному платежу отсутствуют";
$_['text_invoice_empty']	=	"Информация по платежу отсутствует. Причиной может быть ошибочный сертификат по работе с MWS или настройки модуля Яндекс.Касса";

$_['err_mws_shopid']	=	'В модуле Яндекс.Касса указан пустой идентификатор магазина (shopId)';
$_['err_mws_kassa']	=	'Модуль Яндекс.Кассы отключен';
$_['err_mws_listorder']	=	"Ошибка запроса данных об операции. <br><br>
                             Технические подробности:<br>
                             <code> %s </code> 
                             <br>
                             <code> %s </code><br><br>
                             <code> %s </code>";

$_['err_mws_amount']	=	'Сумма возврата не может превышать сумму платежа';
$_['err_mws_cause']	=	'Причина возврата не может быть пустой или превышать длину в 100 символов';
//Payment
$_['text_method_none']       = 'Неизвестный платежный метод';
$_['text_method_PC']       = 'Яндекс.Деньги';
$_['text_method_WM']       = 'WebMoney';
$_['text_method_MC']       = 'Счёт мобильного';
$_['text_method_AC']       = 'Банковские карты';
$_['text_method_GP']       = 'Наличные через терминалы';
$_['text_method_SB']       = 'Сбербанк Онлайн';
$_['text_method_AB']       = 'Альфа-Клик';
$_['text_method_MA']       = 'MasterPass';
$_['text_method_PB']       = 'Промсвязьбанк';
$_['text_method_QW']       = 'QIWI Wallet';
$_['text_method_CR']       = 'Заплатить по частям';

// Error
$_['err_upload_type']      = 'Загружаемый файл имеет недопустимое расширение. Сертификат должен быть с расщирением .cer';
$_['err_upload_size']      = 'Размер файла не должен превышать 2048 байт';
$_['err_upload_main']      = 'Ошибка загрузки файла';

$_['error_warning']            = 'Warning: Please check the form carefully for errors!';
$_['error_permission']         = 'Warning: You do not have permission to modify orders!';
$_['error_curl']               = 'Warning: CURL error %s(%s)!';
$_['error_action']             = 'Warning: Could not complete this action!';