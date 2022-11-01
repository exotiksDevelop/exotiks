<?php
/*
 * Скрипт для запуска автоматического обновления товаров вконтакте(модуль vkExport)
 */

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . dirname(__FILE__) . '/../');

include 'config.php';

$logfile = 'vkExport_cron_market_update.txt';
$route = 'cron_market_update';

include	'vk_export_cron.php';

?>
