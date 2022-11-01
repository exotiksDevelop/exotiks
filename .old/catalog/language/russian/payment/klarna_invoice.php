<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'text_title' => 'Klarna Invoice - Pay within 14 days',
  'text_terms_fee' => '<span id="klarna_invoice_toc"></span> (+%s)<script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\', charge: %s}));
</script>',
  'text_terms_no_fee' => '<span id="klarna_invoice_toc"></span><script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\'}));
</script>',
  'text_additional' => 'Klarna Invoice requires some additional information before they can proccess your order.',
  'text_male' => 'Male',
  'text_female' => 'Female',
  'text_year' => 'Year',
  'text_month' => 'Month',
  'text_day' => 'Day',
  'text_comment' => 'Klarna\'s Invoice ID: %s. 
%s/%s: %.4f',
  'entry_gender' => 'Gender',
  'entry_pno' => 'Personal Number',
  'entry_dob' => 'Date of Birth',
  'entry_phone_no' => 'Phone number',
  'entry_street' => 'Street',
  'entry_house_no' => 'House No.',
  'entry_house_ext' => 'House Ext.',
  'entry_company' => 'Company Registration Number',
  'help_pno' => 'Please enter your Social Security number here.',
  'help_phone_no' => 'Please enter your phone number.',
  'help_street' => 'Please note that delivery can only take place to the registered address when paying with Klarna.',
  'help_house_no' => 'Please enter your house number.',
  'help_house_ext' => 'Please submit your house extension here. E.g. A, B, C, Red, Blue ect.',
  'help_company' => 'Please enter your Company\'s registration number',
  'error_deu_terms' => 'You must agree to Klarna\'s privacy policy (Datenschutz)',
  'error_address_match' => 'Billing and Shipping addresses must match if you want to use Klarna Invoice',
  'error_network' => 'Error occurred while connecting to Klarna. Please try again later.',
));

