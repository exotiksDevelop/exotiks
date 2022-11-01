<?php
ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки
/**
 * Sync MS products with Aliexpress
 **/

echo $_SERVER['SERVER_ADDR'].'<br />';
use Medoo\Medoo;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MySklad\MoySklad;

require_once 'vendor/autoload.php';
require_once 'db/DbConnection.php';
require_once 'env.php';


require_once 'functions.php';

include('BusinessCrmAPI.php');

// Connect logger
/*$log_dir = 'logs/' . date('Y-m-d');
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}*/
/*$stream = new StreamHandler($log_dir . '/ali_orders.log', Logger::DEBUG);
$firephp = new FirePHPHandler();
$logger = new Logger('ali');
$logger->pushHandler($stream);
$logger->pushHandler($firephp);
*/
$dbAuth = $GLOBALS['dbAuth'];

try {
// connect database
    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => $dbAuth['database'],
        'server' => $dbAuth['host'],
        'username' => $dbAuth['user'],
        'password' => $dbAuth['password'],
        'logging' => false
    ]);

    $db_connection = new DbConnection($database);
    $_GET['check_ali'] = true; // todo убрать когда закончится тест
} catch (Exception $e) {
    $logger->error('Problems with connection to db', ['trace' => $e->getTrace()]);
}
//$products = $db_connection->getAllProducts(["publish" => 1, "ali_product_id" => null, "ali_state[!]" => "Delete"]);

//$w_upd = $_GET['upd'] ? $_GET['upd'] : $argv[1];

try {
// --- Connect Aliexpress ---
// СДК скачал из настроек приложения али и заменил composer пакет, т.е. заменил файлы латика.
    $topclient = new TopClient();
    $topclient->appkey = $ali_app['appKey'];
    $topclient->secretKey = $ali_app['appSecret'];
    $sessionKey = $ali_app['token'];

} catch (Exception $e) {
    $logger->error('Problems with topclient', ['trace' => $e->getTrace()]);
}
$class365api = new BusinessCrmAPI($app_id, "", $address_class365, true);
$class365api->setSecret($secret);
$class365_response = $class365api->repair();
//print_r($class365_response);
$token = $class365_response["token"];

$class365api->setToken($token);

if ($class365_response['status']!='ok')
{   
  //  print_r($class365_response);
  //  die("Запрос на получение токена с ошибкой. Ошибка:");
}
//$ali_category_id = get_ali_category_obj('Системы обогрева пола и запчасти');
//$ali_category_id = $topclient->execute($ali_category_id, $sessionKey);


echo '<pre>';
 $params=array('with_prices' =>1,'with_remains' => 1);
$productBiznes = $class365api->request("get", "goods", $params);

foreach ($productBiznes['result'] as $bz) {

        $code = (string)$bz['part'];

        $id_biz = $bz['id'];
        $totals = 0;
        foreach ($bz['remains'] as $stocks) {
            $totals += $stocks['amount']['total'];
        }

        //if((string)$code == (string)'021'){


            foreach ($bz['prices'] as $prices) {
                if($prices['price_type']['name'] == 'Aliexpress'){
                    $price = $prices['price'];
                }
            }


            echo 'Обновить';

            $productres = $db_connection->getSun($code);


            $ali_product_id = $productres[0]['ali_product_id'];

           $res = updatePriceAli($ali_product_id, $price,$code,$totals);

         //   exit();

    //    }



}


//$s = updatePriceAli($id_ali, (int)$price,$sku_ali,$quan);
