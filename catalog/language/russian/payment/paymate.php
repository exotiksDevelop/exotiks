<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'text_title' => 'Credit Card / Debit Card (Paymate)',
  'text_unable' => 'Unable to locate or update your order status',
  'text_declined' => 'Payment was declined by Paymate',
  'text_failed' => 'Paymate Transaction Failed',
  'text_failed_message' => '<p>Unfortunately there was an error processing your Paymate transaction.</p><p><b>Warning: </b>%s</p><p>Please verify your Paymate account balance before attempting to re-process this order</p><p> If you believe this transaction has completed successfully, or is showing as a deduction in your Paymate account, please <a href="%s">Contact Us</a> with your order details.</p>',
  'text_basket' => 'Basket',
  'text_checkout' => 'Checkout',
  'text_success' => 'Success',
));

