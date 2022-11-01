<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'BluePay Hosted Form',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified BluePay Hosted Form account details!',
  'text_edit' => 'Edit BluePay Hosted Form',
  'text_bluepay_hosted' => '<a href="http://www.bluepay.com/preferred-partner/opencart" target="_blank"><img src="view/image/payment/bluepay.jpg" alt="BluePay Hosted Form" title="BluePay Hosted Form" style="border: 1px solid #EEEEEE;" /></a>',
  'text_test' => 'Test',
  'text_live' => 'Live',
  'text_sale' => 'Sale',
  'text_authenticate' => 'Authenticate',
  'text_release_ok' => 'Release was successful',
  'text_release_ok_order' => 'Release was successful',
  'text_rebate_ok' => 'Rebate was successful',
  'text_rebate_ok_order' => 'Rebate was successful, order status updated to rebated',
  'text_void_ok' => 'Void was successful, order status updated to voided',
  'text_payment_info' => 'Payment information',
  'text_release_status' => 'Payment released',
  'text_void_status' => 'Payment voided',
  'text_rebate_status' => 'Payment rebated',
  'text_order_ref' => 'Order ref',
  'text_order_total' => 'Total authorised',
  'text_total_released' => 'Total released',
  'text_transactions' => 'Transactions',
  'text_column_amount' => 'Amount',
  'text_column_type' => 'Type',
  'text_column_date_added' => 'Date Added',
  'text_confirm_void' => 'Are you sure you want to void the payment?',
  'text_confirm_release' => 'Are you sure you want to release the payment?',
  'text_confirm_rebate' => 'Are you sure you want to rebate the payment?',
  'entry_account_name' => 'Account Name',
  'entry_account_id' => 'Account ID',
  'entry_secret_key' => 'Secret Key',
  'entry_test' => 'Test Mode',
  'entry_transaction' => 'Transaction Method',
  'entry_card_amex' => 'Amex',
  'entry_card_discover' => 'Discover',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'entry_debug' => 'Debug logging',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'help_debug' => 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise',
  'help_transaction' => 'Transaction method MUST be set to Payment to allow subscription payments',
  'help_cron_job_token' => 'Make this long and hard to guess',
  'help_cron_job_url' => 'Set a cron job to call this URL',
  'btn_release' => 'Release',
  'btn_rebate' => 'Rebate / refund',
  'btn_void' => 'Void',
  'error_permission' => 'Warning: You do not have permission to modify payment BluePay!',
  'error_account_name' => 'Account Name Required!',
  'error_account_id' => 'Account ID Required!',
  'error_secret_key' => 'Secret Key Required!',
));
