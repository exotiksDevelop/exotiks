<?php

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
$log_dir = 'logs/' . date('Y-m-d');
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}
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
print_r($class365_response);
$token = $class365_response["token"];

$class365api->setToken($token);

if ($class365_response['status']!='ok')
{
    die("Запрос на получение токена с ошибкой. Ошибка: {$class365_response['status']}");
}
//$ali_category_id = get_ali_category_obj('Системы обогрева пола и запчасти');
//$ali_category_id = $topclient->execute($ali_category_id, $sessionKey);


echo '<pre>';
    $status_list = 'FINISH, PLACE_ORDER_SUCCESS, IN_CANCEL, WAIT_SELLER_SEND_GOODS, SELLER_PART_SEND_GOODS, WAIT_BUYER_ACCEPT_GOODS, FUND_PROCESSING, IN_FROZEN, IN_ISSUE, WAIT_SELLER_EXAMINE_MONEY, RISK_CONTROL';
    $ali_request = orderListParam('1', '20', '', $status_list);
   

   

    $ali_orders = $topclient->execute($ali_request, $sessionKey);

       

        if ($ali_orders->result->success == 'true' and $ali_orders->result->total_count > 0)
        foreach ($ali_orders->result->target_list->order_dto as $order) {

             $order_detail__req = aliOrderDetail((string)$order->order_id);

                    $order_detail = $topclient->execute($order_detail__req, $sessionKey);
               
                    $order_status = $order_detail->order_status;

                        if($order_status == 'WAIT_SELLER_SEND_GOODS'){//WAIT_SELLER_SEND_GOODS НОВЫЙ

                        }
                        if($order_status == 'IN_CANCEL'){ // отменен

                        }
                        if($order_status == 'WAIT_BUYER_ACCEPT_GOODS'){ // Доставляется

                        }
                        if($order_status == 'FINISH'){ // Доставлен

                        }

                        //WAIT_BUYER_ACCEPT_GOODS

            exit();
            
        }
