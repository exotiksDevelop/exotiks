<?php
/*
 * Shoputils
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.2.x.ENG.TXT
 * It is also available through the world-wide-web at this URL:
 * https://opencart.market/LICENSE.2.0.x-2.1.x-2.2.x.ENG.TXT
 * 
 * =================================================================
 *       OPENCART/ocStore 2.0.x - 2.1.x - 2.2.x USAGE NOTICE
 * =================================================================
 * This package designed for Opencart/ocStore 2.0.x - 2.1.x - 2.2.x
 * Shoputils does not guarantee correct work of this extension
 * on any other Opencart edition except Opencart/ocStore 2.x.
 * Shoputils does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
*/

// Heading
$_['heading_title']           = 'Антиспам и антирегистрация ботов';

// Button
$_['button_download']         = 'Скачать';
$_['button_clear']            = 'Очистить';

// Tab 
$_['tab_log']                 = 'Журнал';

// Text
$_['text_extension']          = 'Дополнения';
$_['text_success']            = 'Настройки модуля "%s" обновлены!';
$_['text_clear_log_success']  = 'Журнал запросов модуля успешно очищен!';
$_['text_confirm']            = 'Содержимое журнала модуля будет удалено! Вы уверены, что хотите это сделать?';
$_['text_copyright']          = 'Модуль "%s" разработан <a href="https://liveopencart.ru/shoputils" target="_blank">ShopUtils</a>. Вопросы по техподдержке и работе модуля отправляйте через сайт <a href="https://opencart.market/?route=information/contact" target="_blank">https://opencart.market</a>.<br />&copy; ShopUtils 2010 &mdash; %s';
$_['text_contact']            = 'Форма обратной связи';
$_['text_registr']            = 'Регистрация покупателей';
$_['text_affiliate']          = 'Регистрация партнеров';
$_['text_log_off']            = 'Выключен';
$_['text_log_spam']           = 'Только запросы, идентифицированные как спам';
$_['text_log_full']           = 'Все запросы';

// Entry
$_['entry_status']            = 'Статус (форма обратной связи)';
$_['entry_registr_status']    = 'Статус (антирегистрация ботов как покупателей)';
$_['entry_affiliate_status']  = 'Статус (антирегистрация ботов как партнеров)';
$_['entry_word']              = 'Стоп-слова (только для формы обратной связи)';
$_['entry_ip']                = 'Запрещенные IP-адреса';
$_['entry_not_found']         = 'Переадресовывать спам на страницу "error/not_found"';
$_['entry_log']               = 'Журнал';
$_['entry_log_file']          = 'Файл журнала';

// Help
$_['help_word']               = 'Запрещенные слова или фразы в теле письма, которые должны идентифицироваться как спам. Разделяются новой строкой.';
$_['help_ip']                 = 'Запрещенные IP-адреса спамеров. Диапазоны IP-адресов НЕ поддерживаются. Адреса разделяются новой строкой.';
$_['help_not_found']          = 'Если включено - спам-боты после отправки спама попадут на не существующую страницу "error/not_found" и будет ответ сервера 404 (Not found).<br />Если выключено - спам-боты увидят эмуляцию успешной отправки спам-сообщения. При этом, физически на ваш email спам не придет.';
$_['help_log']                = 'Журнал запросов от формы обратной связи сохраняется в файле "system/storage/logs/%s"';
$_['help_registr_log']        = 'Журнал запросов от формы регистрации покупателей сохраняется в файле "system/storage/logs/%s"';
$_['help_affiliate_log']      = 'Журнал запросов от формы регистрации партнеров сохраняется в файле "system/storage/logs/%s"';
$_['help_log_file']           = 'Последние %d строк из файла журнала';

//lic
$_['text_get_key']          = 'Если Вы не знаете как получить лицензионный ключ - прочтите <a href="https://opencart.market/license_key" target="_blank">инструкцию на нашем официальном сайте</a>.';
$_['text_ok']               = ' - <span style="color:green;">OK</span>';
$_['text_error']            = ' - <span style="color:red;">ERROR</span>';
$_['text_domain']           = 'Ваш домен: <b>%s</b>';
$_['text_loader']           = 'Версия IonCube Loader: <b>%s</b>. Требуется IonCube Loader не ниже v<b>%s</b>';
$_['text_php']              = 'Версия PHP: <b>%s</b>. Требуется PHP не ниже v<b>%s</b>';
$_['text_file_warning']     = '<span style="color:red;">Не забудьте скопировать содержимое папки %s из дистрибутива модуля для вашей версии движка в корневую директорию вашего сайта с заменой существующих файлов, если вы этого еще не сделали!</span>';
$_['text_php70']            = 'PHP7.0';
$_['text_php71']            = 'PHP7.1-7.4';
$_['entry_key']             = 'Введите лицензионный ключ';
$_['error_loader']          = '<span style="color:red;">Отсутствует IonCube Loader!</span><br />Обратитесь к Вашему хостеру с просьбой установить IonCube Loader не ниже версии %s';
$_['error_loader_version']  = '<span style="color:red;">Не корректная версия IonCube Loader!</span><br />Обратитесь к Вашему хостеру с просьбой установить IonCube Loader не ниже версии %s';
$_['error_php_version']     = '<span style="color:red;">Не корректная версия PHP!</span>';
$_['error_key']             = 'Недействительный лицензионный ключ!';
$_['error_dir_perm']        = 'Директория "%s" не доступна для записи. Установите необходимые права!';

// Error
$_['error_permission']        = 'У Вас нет прав для управления модулем "%s"!';
$_['error_clear_log']         = 'У Вас нет прав для очистки журнала модуля!';
$_['error_warning']           = 'Файл %s не существует или поврежден!';
?>