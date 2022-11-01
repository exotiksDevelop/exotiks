<?php

/**
 * Sync MS stock
 **/


use Medoo\Medoo;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MySklad\MoySklad;

require_once 'vendor/autoload.php';
require_once 'db/DbConnection.php';
require_once 'env.php';
require_once 'functions.php';


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
    $_GET['check_ali'] = true; // todo убрать когда закончится тест
} catch (Exception $e) {
    $logger->error('Problems with connection to db', ['trace' => $e->getTrace()]);
}
try {
// --- connect MoySklad ---
    $ms = new MoySklad(base64_encode($msAuth['base']));
} catch (Exception $e) {
 //   $logger->error('Problems with MS', ['trace' => $e->getTrace()]);
}

echo '<pre>';
//print_r($ms->endpointStock());

 //$astock = $ms->endpointStock();


	$res = $ms->endpointStock();
	$count_ostat = $res->meta->size;
	$cou = ceil((int)$count_ostat/1000);

		$ih = 0;
			for($i=0; $i <= $cou; $i++){
			$match = 1000*$i;
			$resul = $ms->endpointStock(1000,$match);
					for($ii=0;$ii < count($resul->rows); $ii++){

						//print_r($resul->rows[$ii]);

						$stock = $resul->rows[$ii]->quantity;
						$code = $resul->rows[$ii]->code;
						$ex = explode('/',$resul->rows[$ii]->meta->href);
						$ex = explode('?', $ex[8]);
						$id_product = $ex[0];
						echo $code.' '.$id_product.' '.$stock.'<br />';
						$db_connection->updateProduct(['in_stock' => $stock], ['ms_id' => $id_product]);
						
						echo '<br />********************************<br />';
					}
					


				}