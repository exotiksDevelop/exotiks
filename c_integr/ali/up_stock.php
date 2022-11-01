<?php
ini_set('display_errors', 1);

/**
 * Update stock in Aliexpress
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
include('BusinessCrmAPI.php');

$tmp_dir = __DIR__ . '/tmp';
if (!file_exists($tmp_dir)) {
    mkdir($tmp_dir, 0777, true);
}
// Connect logger
$log_dir = 'logs/' . date('Y-m-d');
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}
/*$stream = new StreamHandler($log_dir . '/ali.log', Logger::DEBUG);
$logger = new Logger('up_stock');
$logger->pushHandler($stream);*/

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
    $_GET['check_ali'] = false; // todo убрать когда закончится тест

} catch (Exception $e) {
  //  $logger->error('Problems with connection to db', ['trace' => $e->getTrace()]);
}

try {
// --- Connect Aliexpress ---
// СДК скачал из настроек приложения али и заменил composer пакет, т.е. заменил файлы латика.
    $topclient = new TopClient();
    $topclient->appkey = $GLOBALS['ali_app']['appKey'];
    $topclient->secretKey = $GLOBALS['ali_app']['appSecret'];
    $sessionKey = $GLOBALS['ali_app']['token'];

} catch (Exception $e) {
   //$logger->error('Problems with topclient', ['trace' => $e->getTrace()]);
}
$class365api = new BusinessCrmAPI($app_id, "", $address_class365, true);
$class365api->setSecret($secret);
$class365_response = $class365api->repair();
echo '<pre>';
$token = $class365_response["token"];

$class365api->setToken($token);

if ($class365_response['status']!='ok')
{
    die("Запрос на получение токена с ошибкой. Ошибка: {$class365_response['status']}");
}
     $arr = array('store_id'=>75538);



    $get_product_stock = $class365api->request("get", "storegoods", $arr);

    print_r($get_product_stock);

    exit();

    foreach ($get_product_stock['result'] as $val) {

            $res =$db_connection->getProductId($val['good_id'],$val['modification_id']);
           // print_r($res);


            if($res['code']){

                print_r($res);

                $req = new AliexpressSolutionProductListGetRequest;
                            $aeop_a_e_product_list_query = new ItemListQuery;
                            $aeop_a_e_product_list_query->product_status_type="onSelling";
                            $aeop_a_e_product_list_query->sku_code=urlencode($res['code']);

                                $req->setAeopAEProductListQuery(json_encode($aeop_a_e_product_list_query));
                                $resp = $topclient->execute($req, $sessionKey);

                                echo '<pre>';

                                print_r($resp);

               

            }
      //  print_r($val);

       // exit();
        // code...
    }

   // print_r(getProductId());
                    /*  $req = new AliexpressSolutionProductListGetRequest;
                            $aeop_a_e_product_list_query = new ItemListQuery;
                            $aeop_a_e_product_list_query->product_status_type="onSelling";
                            $aeop_a_e_product_list_query->sku_code=urlencode($sku_ali);

                                $req->setAeopAEProductListQuery(json_encode($aeop_a_e_product_list_query));
                                $resp = $topclient->execute($req, $sessionKey);

                                echo '<pre>';

                                print_r($resp);*/