<?php
ini_set('display_errors', 1);

use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MySklad\MoySklad;

require_once 'vendor/autoload.php';
require_once 'env.php';
require_once 'db/DbConnection.php';
require_once 'functions.php';

$tmp_dir = __DIR__ . '/tmp';
if (!file_exists($tmp_dir)) {
    mkdir($tmp_dir, 0777, true);
}
// Connect logger
$log_dir = 'logs/' . date('Y-m') . '/' . date('d');
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}
$stream = new StreamHandler($log_dir . '/ali.log', Logger::DEBUG);
$logger = new Logger('asrt');
$logger->pushHandler($stream);

$dbAuth = $GLOBALS['dbAuth'];
$msAuth = $GLOBALS['ms'];

try {
// connect database
    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => $dbAuth['database'],
        'server' => $dbAuth['host'],
        'port' => $dbAuth['port'],
        'username' => $dbAuth['user'],
        'password' => $dbAuth['password'],
        'logging' => false
    ]);

    $db_connection = new DbConnection($database);
} catch (Exception $e) {
    $logger->error('Problems with connection to db', ['trace' => $e->getTrace()]);
}


try {
// --- connect MoySklad ---
    $ms = new MoySklad(base64_encode($msAuth['base']));

    $time_start = microtime(true);

    $asrt = [];
    $next = true;
    $offset = 0;
    $limit = 1000;
    while ($next) {
        $assortment = $ms->getAssortment('stockStore=https://online.moysklad.ru/api/remap/1.2/entity/store/7d8ae43d-eb9a-11e9-0a80-03b700248002', $limit, $offset);
        if (!empty($assortment->rows)) {
            $asrt = array_merge($asrt, (array)$assortment->rows);
        }
        if ($assortment->meta->nextHref) {
            $offset += $limit;
        } else {
            $next = false;
        }
    }

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    file_put_contents($tmp_dir.'/asrt.json', json_encode($asrt));
    $logger->debug('Exec time asrt update ' . date('i:s', $time));

} catch (Exception $e) {
    $logger->error('Problems with MS', ['trace' => $e->getTrace()]);
}
