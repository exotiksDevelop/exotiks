<?php
/*
 * Скрипт для запуска автоматического обновления товаров в альбомах вконтакте(модуль vkExport)
 */
 
ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . dirname(__FILE__) . '/../');
 
include 'config.php';

$logfile = 'vkExport_cron_update.txt';
$route = 'autoupdate';

include	'vk_export_cron.php';

?>
