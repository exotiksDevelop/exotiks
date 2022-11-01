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
    print_r($class365_response);
  //  die("Запрос на получение токена с ошибкой. Ошибка:");
}
//$ali_category_id = get_ali_category_obj('Системы обогрева пола и запчасти');
//$ali_category_id = $topclient->execute($ali_category_id, $sessionKey);


echo '<pre>';


$req = new AliexpressSolutionProductListGetRequest;
$aeop_a_e_product_list_query = new ItemListQuery;
$aeop_a_e_product_list_query->current_page="1";
$aeop_a_e_product_list_query->product_status_type="onSelling";
$aeop_a_e_product_list_query->page_size="100";
$req->setAeopAEProductListQuery(json_encode($aeop_a_e_product_list_query));
$resp = $topclient->execute($req, $sessionKey);

  
    $countProduct = $resp->result->product_count;

    $math = $countProduct/100;

    //echo $math;



        for($i=1; $i <= $countProduct; $i++){

            $req = new AliexpressSolutionProductListGetRequest;
                $aeop_a_e_product_list_query = new ItemListQuery;
                $aeop_a_e_product_list_query->current_page=(string)$i;
                $aeop_a_e_product_list_query->product_status_type="onSelling";
                $aeop_a_e_product_list_query->page_size="100";
                $req->setAeopAEProductListQuery(json_encode($aeop_a_e_product_list_query));
                $resp = $topclient->execute($req, $sessionKey);

                    foreach ($resp->result->aeop_a_e_product_display_d_t_o_list->item_display_dto as $prod) {
                            



                            $req = new AliexpressSolutionProductInfoGetRequest;
                                $req->setProductId($prod->product_id);
                                $resp = $topclient->execute($req, $sessionKey);

                                


                                $sku = $resp->result->aeop_ae_product_s_k_us->global_aeop_ae_product_sku->sku_code;
                              
                            $prods['id'] = (string)$prod->product_id;

                            $prods['article'] = (string)$sku;

                            $prods['name'] = (string)$resp->result->subject;

                            $product_json = '';
                            $where = array();
                            $where = array('article'=>(string)$sku);
                            $pr = $db_connection->getAllProducts($where);

                            if(!$pr){
                                $db_connection->createProduct($prods, 1, $product_json);
                            }

                           
                    }
        }
$productBiznes = $class365api->request("get", "goods", array());
foreach ($productBiznes['result'] as $bz) {

    $code = (string)$bz['code'];

    $id_biz = $bz['id'];

        $db_connection->updateProduct(['ms_id' => $id_biz], ['article' => $code]);
}
