<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'PayPoint',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified PayPoint account details!',
  'text_edit' => 'Edit PayPoint',
  'text_paypoint' => '<a href="https://www.paypoint.net/partners/opencart" target="_blank"><img src="view/image/payment/paypoint.png" alt="PayPoint" title="PayPoint" style="border: 1px solid #EEEEEE;" /></a>',
  'text_live' => 'Production',
  'text_successful' => 'Always Successful',
  'text_fail' => 'Always Fail',
  'entry_merchant' => 'Merchant ID',
  'entry_password' => 'Remote Password',
  'entry_test' => 'Test Mode',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'help_password' => 'Leave empty if you do not have "Digest Key Authentication" enabled on your account.',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'error_permission' => 'Warning: You do not have permission to modify payment PayPoint!',
  'error_merchant' => 'Merchant ID Required!',
));

