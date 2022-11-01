<?php
/*
 * Скрипт для запуска автоматического экспорта товаров вконтакте(модуль vkExport)
 */

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . dirname(__FILE__) . '/../');

include 'config.php';

$logfile = 'vkExport_cron_market.txt';
$route = 'cron_market';

include	'vk_export_cron.php';

?>
