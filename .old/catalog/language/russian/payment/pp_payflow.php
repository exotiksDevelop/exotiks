<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'text_title' => 'Credit or Debit Card (Processed securely by PayPal)',
  'text_credit_card' => 'Credit Card Details',
  'text_start_date' => '(if available)',
  'text_issue' => '(for Maestro and Solo cards only)',
  'text_wait' => 'Please wait!',
  'entry_cc_owner' => 'Card Owner',
  'entry_cc_type' => 'Card Type',
  'entry_cc_number' => 'Card Number',
  'entry_cc_start_date' => 'Card Valid From Date',
  'entry_cc_expire_date' => 'Card Expiry Date',
  'entry_cc_cvv2' => 'Card Security Code (CVV2)',
  'entry_cc_issue' => 'Card Issue Number',
  'error_required' => 'Warning: All payment information fields are required.',
  'error_general' => 'Warning: A general problem has occurred with the transaction. Please try again.',
  'error_config' => 'Warning: Payment module configuration error. Please verify the login credentials.',
  'error_address' => 'Warning: A match of the Payment Address City, State, and Postal Code failed. Please try again.',
  'error_declined' => 'Warning: This transaction has been declined. Please try again.',
  'error_invalid' => 'Warning: The provided credit card information is invalid. Please try again.',
));

