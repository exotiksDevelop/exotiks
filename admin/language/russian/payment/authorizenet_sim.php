<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'Authorize.Net (SIM)',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified Authorize.Net (SIM) account details!',
  'text_edit' => 'Edit Authorize.Net (SIM)',
  'text_authorizenet_sim' => '<a onclick="window.open(\'http://reseller.authorize.net/application/?id=5561103\'));
"><img src="view/image/payment/authorizenet.png" alt="Authorize.Net" title="Authorize.Net" style="border: 1px solid #EEEEEE;" /></a>',
  'entry_merchant' => 'Merchant ID',
  'entry_key' => 'Transaction Key',
  'entry_callback' => 'Relay Response URL',
  'entry_md5' => 'MD5 Hash Value',
  'entry_test' => 'Test Mode',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'help_callback' => 'Please login and set this at <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>.',
  'help_md5' => 'The MD5 Hash feature enables you to authenticate that a transaction response is securely received from Authorize.Net.Please login and set this at <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>.(Optional)',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'error_permission' => 'Warning: You do not have permission to modify payment Authorize.Net (SIM)!',
  'error_merchant' => 'Merchant ID Required!',
  'error_key' => 'Transaction Key Required!',
));

