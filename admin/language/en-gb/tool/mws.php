<?php
// Heading
$_['heading_title']            = 'Fund Management';

// Text
$_['tab_return']                 = 'Refund';
$_['tab_history']                = 'History';

$_['text_order_id']       = 'Order number';
$_['lbl_mws_inv']       = 'Transaction number (Yandex.Chechout)';
$_['text_payment_method']      = 'Payment method:';
$_['text_total']               = 'Payment amount:';
$_['text_return_total']        = 'Returned:';
$_['text_amount']  = 'Return';
$_['text_cause']   = 'Refund reason';
$_['btn_return']   =  "Make refund";
$_['text_return_success']  = 'Payment returned successfully';

$_['text_history']        = 'Refund list';
$_['tbl_head_date']    = 'Refund date';
$_['tbl_head_amount']  = 'Refund amount';
$_['tbl_head_cause']   = 'Refund reason';

$_['text_history_empty']   =  "No successful refunds for this payment";
$_['text_invoice_empty']   =  "No information for this payment. May be caused by errorous certificate for work with the MWS or settings of the Yandex.Checkout module";

$_['err_mws_shopid']   =  'Void shop identifier (shopId) is specified in the Yandex.Checkout module';
$_['err_mws_kassa']    =  'The Yandex.Checkout module is disabled';
$_['err_mws_listorder']    =  "Error of requesting the operation details. <br><br>
                             Technical details:<br>
                             <code> %s </code>
                             <br>
                             <code> %s </code><br><br>
                             <code> %s </code>";

$_['err_mws_amount']   =  'Refund amount cannot exceed payment amount';
$_['err_mws_cause']    =  'Refund reason cannot be empty or exceed 100 characters';
//Payment
$_['text_method_none']       = 'Unknown payment method';
$_['text_method_PC']       = 'Payment from a Yandex.Money Wallet';
$_['text_method_WM']       = 'Payment from a WebMoney Purse';
$_['text_method_MC']       = 'Payment with direct carrier billing';
$_['text_method_AC']       = 'Payment from a bank card';
$_['text_method_GP']       = 'Payment in cash via payment kiosks and cash registers';
$_['text_method_SB']       = 'Payment via Sberbank: by a text message or through Sberbank Online';
$_['text_method_AB']       = 'Payment via Alfa-Click';
$_['text_method_MA']       = 'Payment via MasterPass';
$_['text_method_PB']       = 'Payment via Promsvyazbank';
$_['text_method_QW']       = 'Payment via QIWI Wallet';
$_['text_method_QP']       = 'Payment using promised payment service (QPPI.ru)';

// Error
$_['err_upload_type']      = 'The file you are uploading has incorrect format. The certificate needs to have .cer extension';
$_['err_upload_size']      = 'The file size cannot exceed 2,048 bytes';
$_['err_upload_main']      = 'File uploading error';

$_['error_warning']            = 'Warning: Please check the form carefully for errors!';
$_['error_permission']         = 'Warning: You do not have permission to modify orders!';
$_['error_curl']               = 'Warning: CURL error %s(%s)!';
$_['error_action']             = 'Warning: Could not complete this action!';