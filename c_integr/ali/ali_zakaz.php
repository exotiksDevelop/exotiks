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

    $status_list = 'FINISH, PLACE_ORDER_SUCCESS, IN_CANCEL, WAIT_SELLER_SEND_GOODS, SELLER_PART_SEND_GOODS, WAIT_BUYER_ACCEPT_GOODS, FUND_PROCESSING, IN_FROZEN, IN_ISSUE, WAIT_SELLER_EXAMINE_MONEY, RISK_CONTROL';
    $ali_request = orderListParam('1', '20', '', $status_list);
   
   

    $ali_orders = $topclient->execute($ali_request, $sessionKey);

   // print_r( $ali_orders);
    if ($ali_orders->result->success == 'true' and $ali_orders->result->total_count > 0)
        foreach ($ali_orders->result->target_list->order_dto as $order) {

            $id_order = (string)$order->order_id;

            echo $id_order.'<br />';

            $_order = $db_connection->getOrder(['ali_order_id' => $id_order]);

         

             $order_detail__req = aliOrderDetail($id_order); //5053522925553180

            

                    $order_detail = $topclient->execute($order_detail__req, $sessionKey);



                    if (!empty($order_detail->result->data)) {
                        if(!$_order){
                        $order_detail = $order_detail->result->data;
                        echo 'ПРОШЕЛ!!!';
                      //   print_r($order_detail);
                     $new_order_id = $db_connection->createOrder($order_detail);

                       


                       // $new_order_id = $db_connection->createOrder($order_detail);

                        $fullname = $order_detail->receipt_address->contact_person;
                        $fulln = $order_detail->receipt_address->contact_person;
                        $customer_phone_num = (string)$order_detail->receipt_address->phone_country . (string)$order_detail->receipt_address->mobile_no;
                        $full_address = (string)$order_detail->receipt_address->zip . ', ' .
                            translit2rus((string)$order_detail->receipt_address->province) . ', ' .
                            translit2rus((string)$order_detail->receipt_address->city) . ', ' .
                            (string)$order_detail->receipt_address->detail_address;

                          //   print_r($order_detail);

                            $shipping_amount = $order_detail->logistics_amount->amount;


                             $order_date = date('Y-m-d H:i:s');

                             /*
                             $OZ_MARKETS['0'] = array_merge($OZ_MARKETS[0], [
    'entity_type' => 'customerorders', //realizations || customerorders || shiftings
    'author_employee_id' => '75927',
    'responsible_employee_id' => '75927',//+
    'organization_id' => '75537',//+
    'partner_id' => '145798',//+
    'shipper_id' => '',
    'consignee_id' => '',
    'status_id' => 440514,
    'STORES_ID' => [75540], // пустой массив - значит все +
    'PRICE_ID' => 452770,
    'syncTypeBRU' => 'part', //part(Артикул)|code (Код)
    'ozon_warehouse' => '19823126039000',//+ Доставка Ozon
    'ozon_warehouse2' => '21100546817000',//+ CDEK Почта России Boxberry
    'ozon_warehouse3' => '21760121933000',//+ По Москве
    'states_map' => // Статус Озон = Статус Бизнеса
    [
        'awaiting_packaging' => 440514,
        'awaiting_deliver'   => 442833,
        'delivering'         => 442835,
        'delivered'          => 359,
        'cancelled'          => 442837,
        'arbitration'        => 0
    ]
]);

*/


                                        echo 'НОМЕР ЗАКАЗА ali_'.$order->order_id.'<br />';

                                            $orderData = array(
                                                'number' => 'ali_'.$order->order_id,
                                                'author_employee_id' => '75927',
                                                'responsible_employee_id' => '75927', // Юрий Емельянов
                                                'organization_id' => '75537',
                                                'partner_id' => '146025', // Яндекс.Маркет
                                                'shipper_id' => '',
                                                'consignee_id' => '', // Яндекс.Маркет
                                                'status_id' => '369', // ЯП Обрабатывается
                                                'comment' => 'AliExpress: '.$order->order_id.' '.$buyer_info.' '.$fulln.' '.$customer_phone_num.' '.$full_address,
                                                'date' => $order_date
                                            );

                                            print_r($orderData);

                                          //  exit();

                                   $createOrderResponse = $class365api->request("post", "customerorders", $orderData);



                                            $id_order_b = $createOrderResponse['result']['id'];

                                                                $positions = [];
                        foreach ($order->product_list->order_product_dto as $ali_product) {
                            $product_article = (string)$ali_product->sku_code;

                             $res_product = $db_connection->getProduct($product_article);

                             print_r($res_product);

                            $product_count = (string)$ali_product->product_count;
                            $product_amount = (string)$ali_product->total_product_amount->amount;

                             if($res_product['b_id']){

                                    $params = [
                                        'customer_order_id' => $id_order_b,
                                        'amount' => $product_count,
                                        'good_id' => $res_product['b_id'],
                                        'price' =>(float)$product_amount
                                    ];
                    if ($res_product['b_modification_id']) {
                        $params['modification_id'] = $res_product['b_modification_id'];
                    }
                    print_r($params);
                    $jsonResponse = $class365api->request("post", "customerordergoods", $params);

                    print_r($jsonResponse);

                 //   exit();


                             }

                            
                        }




                  //      exit();//146025
                    }
                    }



        }


