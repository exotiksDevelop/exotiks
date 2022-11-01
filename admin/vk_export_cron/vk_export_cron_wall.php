<?php
/*
 * Скрипт для запуска автоматического эксопрта товаров на стену вконтакте(модуль vkExport)
 */

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . dirname(__FILE__) . '/../'); 

include 'config.php';

$logfile = 'vkExport_cron_wall.txt';
$route = 'autowallpost';

include	'vk_export_cron.php';

?>
