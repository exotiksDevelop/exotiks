<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'text_success' => 'Success: Your gift voucher discount has been applied!',
  'text_cart' => 'Success: You have modified your shopping cart!',
  'text_for' => '%s Gift Certificate for %s',
  'error_permission' => 'Warning: You do not have permission to access the API!',
  'error_voucher' => 'Warning: Gift Voucher is either invalid or the balance has been used up!',
  'error_to_name' => 'Recipient\'s Name must be between 1 and 64 characters!',
  'error_from_name' => 'Your Name must be between 1 and 64 characters!',
  'error_email' => 'E-Mail Address does not appear to be valid!',
  'error_theme' => 'You must select a theme!',
  'error_amount' => 'Amount must be between %s and %s!',
));

