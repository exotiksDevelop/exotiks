<?php

include('BusinessCrmAPI.php');
/*
Id интеграции	803744
Секретный ключ  	bKU5ebGnYqzasGTfHVNnZupkeHJT4Cka
	https://goodgod81.class365.ru/
*/


$app_id = 803744;
$secret = "bKU5ebGnYqzasGTfHVNnZupkeHJT4Cka";
$address_class365 = "https://goodgod81.class365.ru";

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
echo '<pre>';



//print_r($class365api->request( "get","contactinfotypes", [] ));


exit();





$phone = "79011163941";
$response = $class365api->request( "get","partners", [ "phone" => $phone ] );

if($response['result'][0]){

	$id_counterpaty = $response['result'][0]['id'];
}else{

	$counterpaty = array(
		'name' => 'ALiexpress',
	);

	$createAgent = $class365api->request("post", "partners", $counterpaty);

	$id_counterpaty = $createAgent['result']['id'];

	//Email - 4
	$contactInfo = array(
		'partner_id' =>$id_counterpaty,
		'contact_info_type_id' => 1,
		'contact_info' =>$phone
	);

	$createAgent = $class365api->request("post", "partnercontactinfo", $contactInfo);
}




$order_date = date('Y-m-d H:i:s');

        $orderData = array(
            'number' => '999999',
            'author_employee_id' => '334336',
            'responsible_employee_id' => '75531', // Юрий Емельянов
            'organization_id' => '75535',
            'partner_id' => $id_counterpaty, // Яндекс.Маркет
            'shipper_id' => '75535',
          //  'consignee_id' => $id_counterpaty, // Яндекс.Маркет
            'status_id' => '329', // ЯП Обрабатывается
            'comment' => 'AliExpress: 999999',
            'date' => $order_date
        );

		$createOrderResponse = $class365api->request("post", "realizations", $orderData);


			print_r($createOrderResponse);