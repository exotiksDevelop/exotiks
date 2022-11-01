<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'Authorize.Net (AIM)',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified Authorize.Net (AIM) account details!',
  'text_edit' => 'Edit Authorize.Net (AIM)',
  'text_test' => 'Test',
  'text_live' => 'Live',
  'text_authorization' => 'Authorization',
  'text_capture' => 'Capture',
  'text_authorizenet_aim' => '<a onclick="window.open(\'http://reseller.authorize.net/application/?id=5561103\'));
"><img src="view/image/payment/authorizenet.png" alt="Authorize.Net" title="Authorize.Net" style="border: 1px solid #EEEEEE;" /></a>',
  'entry_login' => 'Login ID',
  'entry_key' => 'Transaction Key',
  'entry_hash' => 'MD5 Hash',
  'entry_server' => 'Transaction Server',
  'entry_mode' => 'Transaction Mode',
  'entry_method' => 'Transaction Method',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'error_permission' => 'Warning: You do not have permission to modify payment Authorize.Net (SIM)!',
  'error_login' => 'Login ID Required!',
  'error_key' => 'Transaction Key Required!',
));

