<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'SagePay Direct',
  'text_payment' => 'Payment',
  'text_success' => 'Success: You have modified SagePay account details!',
  'text_edit' => 'Edit SagePay Direct',
  'text_sagepay_direct' => '<a href="https://support.sagepay.com/apply/default.aspx?PartnerID=E511AF91-E4A0-42DE-80B0-09C981A3FB61" target="_blank"><img src="view/image/payment/sagepay.png" alt="SagePay" title="SagePay" style="border: 1px solid #EEEEEE;" /></a>',
  'text_sim' => 'Simulator',
  'text_test' => 'Test',
  'text_live' => 'Live',
  'text_defered' => 'Defered',
  'text_authenticate' => 'Authenticate',
  'text_release_ok' => 'Release was successful',
  'text_release_ok_order' => 'Release was successful, order status updated to success - settled',
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
  'text_column_date_added' => 'Created',
  'text_confirm_void' => 'Are you sure you want to void the payment?',
  'text_confirm_release' => 'Are you sure you want to release the payment?',
  'text_confirm_rebate' => 'Are you sure you want to rebate the payment?',
  'entry_vendor' => 'Vendor',
  'entry_test' => 'Test Mode',
  'entry_transaction' => 'Transaction Method',
  'entry_total' => 'Total',
  'entry_order_status' => 'Order Status',
  'entry_geo_zone' => 'Geo Zone',
  'entry_status' => 'Status',
  'entry_sort_order' => 'Sort Order',
  'entry_debug' => 'Debug logging',
  'entry_card' => 'Store Cards',
  'entry_cron_job_token' => 'Secret Token',
  'entry_cron_job_url' => 'Cron Job\'s URL',
  'entry_last_cron_job_run' => 'Last cron job\'s run time',
  'help_total' => 'The checkout total the order must reach before this payment method becomes active.',
  'help_debug' => 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise',
  'help_transaction' => 'Transaction method MUST be set to Payment to allow subscription payments',
  'help_cron_job_token' => 'Make this long and hard to guess',
  'help_cron_job_url' => 'Set a cron job to call this URL',
  'btn_release' => 'Release',
  'btn_rebate' => 'Rebate / refund',
  'btn_void' => 'Void',
  'error_permission' => 'Warning: You do not have permission to modify payment SagePay!',
  'error_vendor' => 'Vendor ID Required!',
));
