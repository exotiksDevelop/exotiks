<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'WorldPay',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified WorldPay account details!',
  'text_edit' => 'Edit WorldPay',
  'text_worldpay' => '<a target="_BLANK" href="https://business.worldpay.com/partner/opencart"><img src="view/image/payment/worldpay.png" alt="Worldpay" title="Worldpay" style="border: 1px solid #EEEEEE;" /></a>',
  'text_successful' => 'On - Always Successful',
  'text_declined' => 'On - Always Declined',
  'text_off' => 'Off',
  'entry_merchant' => 'Merchant ID',
  'entry_password' => 'Payment Response Password',
  'entry_callback' => 'Relay Response URL',
  'entry_test' => 'Test Mode',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'help_password' => 'This has to be set in the WorldPay control panel.',
  'help_callback' => 'This has to be set in the WorldPay control panel. You will also need to check the "Enable the Shopper Response".',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'error_permission' => 'Warning: You do not have permission to modify payment WorldPay!',
  'error_merchant' => 'Merchant ID Required!',
  'error_password' => 'Password Required!',
));

