<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'Paymate',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified Paymate account details!',
  'text_edit' => 'Edit Paymate',
  'text_paymate' => '<img src="view/image/payment/paymate.png" alt="Paymate" title="Paymate" style="border: 1px solid #EEEEEE;" />',
  'entry_username' => 'Paymate Username',
  'entry_password' => 'Password',
  'entry_test' => 'Test Mode',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'help_password' => 'Just use some random password. This will be used to make sure the payment information is not interfered with after being sent to the payment gateway.',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'error_permission' => 'Warning: You do not have permission to modify payment Paymate!',
  'error_username' => 'Paymate Username required!',
  'error_password' => 'Password required!',
));

