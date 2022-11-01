<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'text_success' => 'You have successfully modified customers',
  'error_permission' => 'Warning: You do not have permission to access the API!',
  'error_firstname' => 'First Name must be between 1 and 32 characters!',
  'error_lastname' => 'Last Name must be between 1 and 32 characters!',
  'error_email' => 'E-Mail Address does not appear to be valid!',
  'error_telephone' => 'Telephone must be between 3 and 32 characters!',
  'error_custom_field' => '%s required!',
));

