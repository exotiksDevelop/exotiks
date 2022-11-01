<?php

$_['tab_error'] = 'Error';
$_['tab_files'] = 'Files';

$_['text_add'] = 'Add Modification';
$_['text_edit'] = 'Edit Modification: %s';

$_['text_enabled'] = 'Enabled';
$_['text_disabled'] = 'Disabled';

$_['entry_author'] = 'Author';
$_['entry_name'] = 'Name';
$_['entry_xml'] = 'XML';

$_['button_filter'] = 'Filter';
$_['button_reset'] = 'Reset';

$_['column_date_modified'] = 'Last Modified';

$_['error_warning'] = 'There has been an error. Please check your data and try again';
$_['error_required'] = 'This field is required';
$_['error_name'] = 'Missing name tag';
$_['error_code'] = 'Missing code tag';
$_['error_exists'] = 'Modification \'%s\' is already using the same code: %s!';
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'heading_title' => 'Модификаторы',
  'text_success' => 'Действие успешно выполнено!',
  'text_refresh' => 'Каждый раз, когда Вы включили / отключили или удалили модификатор, необходимо нажать кнопку обновить, чтобы обновить кэш модификаторов!',
  'text_list' => 'Модификаторы',
  'column_name' => 'Название',
  'column_author' => 'Автор',
  'column_version' => 'Версия',
  'column_status' => 'Состояние',
  'column_date_added' => 'Дата',
  'column_action' => 'Действие',
  'error_permission' => 'Внимание: У Вас нет прав для управления модификаторами!',
));

