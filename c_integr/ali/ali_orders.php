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
try {
    $status_list = 'FINISH, PLACE_ORDER_SUCCESS, IN_CANCEL, WAIT_SELLER_SEND_GOODS, SELLER_PART_SEND_GOODS, WAIT_BUYER_ACCEPT_GOODS, FUND_PROCESSING, IN_FROZEN, IN_ISSUE, WAIT_SELLER_EXAMINE_MONEY, RISK_CONTROL';
    $ali_request = orderListParam('1', '20', '', $status_list);
   

   

    $ali_orders = $topclient->execute($ali_request, $sessionKey);


    if ($ali_orders->result->success == 'true' and $ali_orders->result->total_count > 0)
        foreach ($ali_orders->result->target_list->order_dto as $order) {
            if (!empty($order->order_id and $order->order_id != '8133545995493400')) { // skip order created early

                $_order = $db_connection->getOrder(['ali_order_id' => (string)$order->order_id]);

                if ($_order === false or empty($_order)) {



                    $order_detail__req = aliOrderDetail((string)$order->order_id);

                    $order_detail = $topclient->execute($order_detail__req, $sessionKey);

                   
                    if (!empty($order_detail->result->data)) {
                        $order_detail = $order_detail->result->data;


                        $new_order_id = $db_connection->createOrder($order_detail);


                        $fullname = $order_detail->receipt_address->contact_person;
                        $fulln = $order_detail->receipt_address->contact_person;
                        $customer_phone_num = (string)$order_detail->receipt_address->phone_country . (string)$order_detail->receipt_address->mobile_no;
                        $full_address = (string)$order_detail->receipt_address->zip . ', ' .
                            translit2rus((string)$order_detail->receipt_address->province) . ', ' .
                            translit2rus((string)$order_detail->receipt_address->city) . ', ' .
                            (string)$order_detail->receipt_address->detail_address;

                          //   print_r($order_detail);

                            $shipping_amount = $order_detail->logistics_amount->amount;


                            echo '<pre>';
                            $order_status = $order_detail->order_status;

                        if($order_status == 'WAIT_SELLER_SEND_GOODS'){//WAIT_SELLER_SEND_GOODS НОВЫЙ
                            $id_status_biznesru =343;// [point_group_id] => 11 айди группы статусов

                        }
                        if($order_status == 'IN_CANCEL'){ // отменен
                            $id_status_biznesru =345; // [point_group_id] => 11 айди группы статусов
                        }
                        if($order_status == 'WAIT_BUYER_ACCEPT_GOODS'){ // Доставляется
                            $id_status_biznesru =344;// [point_group_id] => 11 айди группы статусов
                        }
                        if($order_status == 'FINISH'){ // Доставлен
                            $id_status_biznesru =346;// [point_group_id] => 11 айди группы статусов
                        }
                            $buyer_info = $order_detail->buyer_signer_fullname.' '.$order_detail->buyerloginid;
                            $fullname = 'AliExpress';
                           // $response = $class365api->request( "get","partners", [ "name" => $fullname ] );


                        

                                 /*       if($response['result'][0]){

                                                $id_counterpaty = $response['result'][0]['id'];
                                        }else{

                                            $counterpaty = array(
                                                'name' => $fullname,
                                            );

                                            $createAgent = $class365api->request("post", "partners", $counterpaty);

                                            print_r($createAgent);

                                            $id_counterpaty = $createAgent['result']['id'];

                                            //Email - 4
                                            $contactInfo = array(
                                                'partner_id' =>$id_counterpaty,
                                                'contact_info_type_id' => 1,
                                                'contact_info' =>$customer_phone_num
                                            );

                                            $createAgent = $class365api->request("post", "partnercontactinfo", $contactInfo);
                                        }*/



                                      /*  $jsonResponse = $class365api->request("post", "realizationgoods", $params);


                                        
                                        $orderObject->crm_position_id = $jsonResponse['result']['id'];*/

                                        $order_date = date('Y-m-d H:i:s');


                                        echo 'НОМЕР ЗАКАЗА ali_'.$order->order_id.'<br />';

                                            $orderData = array(
                                                'number' => 'ali_'.$order->order_id,
                                                'author_employee_id' => '334336',
                                                'responsible_employee_id' => '75531', // Юрий Емельянов
                                                'organization_id' => '75535',
                                                'partner_id' => '3186514', // Яндекс.Маркет
                                                'shipper_id' => '75535',
                                                'consignee_id' => '3186514', // Яндекс.Маркет
                                                'status_id' => (string)$id_status_biznesru, // ЯП Обрабатывается
                                                'comment' => 'AliExpress: '.$order->order_id.' '.$buyer_info.' '.$fulln.' '.$customer_phone_num.' '.$full_address,
                                                'date' => $order_date
                                            );

                                            print_r($orderData);

                                        $createOrderResponse = $class365api->request("post", "realizations", $orderData);



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
                                        'realization_id' => $id_order_b,
                                        'amount' => $product_count,
                                        'good_id' => $res_product['b_id'],
                                        'price' =>(float)$product_amount
                                    ];
                    if ($res_product['b_modification_id']) {
                        $params['modification_id'] = $res_product['b_modification_id'];
                    }

                    $jsonResponse = $class365api->request("post", "realizationgoods", $params);

                    print_r($jsonResponse);


                             }

                            
                        }


                  

                            $params1 = [
                                        'realization_id' => $id_order_b,
                                        'amount' => 1,
                                        'good_id' => 1038914,
                                        'price' =>(float)$shipping_amount
                                    ];
                            $jsonResponse1 = $class365api->request("post", "realizationgoods", $params1);

                            $db_connection->updateOrder(['ms_id' => $id_order_b], ['ali_order_id' => $order->order_id]);

                            exit();





                       
                      //  $db_connection->updateOrder(['ms_id' => $ms_order->id], ['ali_order_id' => $order->order_id]);
                    }
                }             }
        }

} catch (Exception $e) {
    $logger->error('Problems with topclient', ['trace' => $e->getTrace()]);
}

function getIdProduct($article,$xml){

	foreach ($xml->product as $value) {


       // $article = $value->variants->variant->sku;

      //  echo $article.'<br />';
      //  if($article == 'Light_Jack_3.5m_белый'){

          $atr = (array)$value;
          $atr = (array)$atr['product-field-values'];


          $atr = $atr['product-field-value'];
          $sku_ali = null;
            foreach($atr as $at){

              //  print_r($at);

                $at = (array)$at;

                if($at['product-field-id'] == 91459){

                    $sku_ali = strip_tags(trim($at['value']));

                }



           
          }

                //	echo '^'.$article .'^ == ^'. $sku_ali.'^<br />';
             	if($article == $sku_ali){

             		return $value->variants->variant->id;
             	}
            

        }


}
