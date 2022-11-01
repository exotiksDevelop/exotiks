<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'Thank you for shopping with %s .... ',
  'text_title' => 'Credit Card / Debit Card (WorldPay)',
  'text_response' => 'Response from WorldPay',
  'text_success' => '... your payment was successfully received.',
  'text_success_wait' => '<b><span style="color: #FF0000">Please wait...</span></b> whilst we finish processing your order.<br>If you are not automatically re-directed in 10 seconds, please click <a href="%s">here</a>.',
  'text_failure' => '... Your payment has been cancelled!',
  'text_failure_wait' => '<b><span style="color: #FF0000">Please wait...</span></b><br>If you are not automatically re-directed in 10 seconds, please click <a href="%s">here</a>.',
  'text_pw_mismatch' => 'CallbackPW does not match. Order requires investigation.',
));

