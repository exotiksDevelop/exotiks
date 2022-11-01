<?php
/*
 * Скрипт для запуска автоматического экспорта товаров в альбомы вконтакте(модуль vkExport)
 */

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . dirname(__FILE__) . '/../');

include 'config.php';

$logfile = 'vkExport_cron.txt';
$route = 'autoexport';

include	'vk_export_cron.php';

?>
