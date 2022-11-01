<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'Payza',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified Payza account details!',
  'text_edit' => 'Edit Payza',
  'entry_merchant' => 'Merchant ID',
  'entry_security' => 'Security Code',
  'entry_callback' => 'Alert URL',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'help_callback' => 'This has to be set in the Payza control panel. You will also need to check the "IPN Status" to enabled.',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'error_permission' => 'Warning: You do not have permission to modify payment Payza!',
  'error_merchant' => 'Merchant ID Required!',
  'error_security' => 'Security Code Required!',
));

