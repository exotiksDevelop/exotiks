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


$status = $class365api->request("get", "customerorderstatus", array('point_group_id'=>37)); //37
echo '<pre>';
print_r($status);