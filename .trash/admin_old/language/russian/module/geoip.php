<?php
$_['heading_title'] = 'GeoIP';

// Entry
$_['entry_set_zone'] = 'Устанавливать регион<span class="help" data-toggle="tooltip" title="Если не удалось определить регион по IP, будет выставлен регион из настроек"></span>';
$_['entry_from_ajax'] = 'Вывод данных через ajax<span class="help" data-toggle="tooltip" title="Нужно включить, если стоит кэшер страниц"></span>';
$_['entry_use_cookie'] = 'Сохранять регион в cookie';
$_['entry_popup_active'] = 'Показывать "Угадали город"<span class="help" data-toggle="tooltip" title="При первом заходе пользователю показывается попап с определенным автоматически городом. Можно подтвердить город, либо выбрать другой."></span> ';
$_['entry_popup_cookie_time'] = 'Период показа, сек.<span class="help" data-toggle="tooltip" title="Попап \'Угадали город\' показывается при каждом визите, установите время, в течение которого попап не появится после первого захода: 86400 - сутки, 2592000 - месяц, 31536000 - год"></span>';
$_['entry_popup_view'] = 'Тип попапа';
$_['entry_key'] = 'Ключ';
$_['entry_zone'] = 'Зона';
$_['entry_city'] = 'Город';
$_['entry_sort'] = 'Сортировка';
$_['entry_value'] = 'Значение';
$_['entry_subdomain'] = 'Поддомен';
$_['entry_country'] = 'Страна';
$_['entry_currency'] = 'Валюта';
$_['entry_disable_redirect'] = 'Отключить автоматический редирект при первом заходе<span class="help" data-toggle="tooltip" title="Переход на поддомен только при выборе города в попапе"></span>';
$_['entry_domain'] = 'Основной домен (без http://)<span class="help" data-toggle="tooltip" title="Укажите домен без http://"></span>';
$_['entry_license'] = 'Лицензия<span class="help" data-toggle="tooltip" title="Ключ, выданный автором"></span>';
$_['entry_status'] = 'Статус';

$_['text_popup_cities'] = 'Города для попапа';
$_['text_popup_view_custom'] = 'Адаптивный';
$_['text_popup_view_bootstrap'] = 'Bootstrap';
$_['text_regions_info'] = 'Эти настройки используются для сопоставления регионов OpenCart и базы ФИАС, когда у вас регионы отличаются от стандартных OpenCart (например, добавлены/отредактированы вручную). Убедитесь, что регионы соответствую друг другу!';

$_['text_module'] = 'Модули';
$_['text_success'] = 'Настройки модуля обновлены!';
$_['text_edit'] = 'Правка модуля GeoIP';

$_['tab_popup'] = 'Попапы';
$_['tab_messages'] = 'Геосообщения';
$_['tab_redirects'] = 'Редиректы';
$_['tab_currencies'] = 'Валюта';
$_['tab_regions'] = 'Регионы';

$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
$_['error_key'] = 'Поле должно содержать латинские буквы, цифры и знаки "-", "_"';
$_['error_fias'] = 'Укажите зону';
$_['error_subdomain'] = 'Укажите поддомен в виде: http://abc.site.com/ или http://abc.site.com/path/to/';
$_['error_currency_country'] = 'Укажите страну';
$_['error_currency_code'] = 'Укажите валюту';
$_['error_license'] = 'Модуль не активирован, получите лицензионный ключ у автора модуля';