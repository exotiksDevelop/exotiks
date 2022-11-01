<?php

/**
 * Sync MS products with Aliexpress
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


$c = new TopClient;
$c->appkey = '32822905';
$c->secretKey = 'b6f8cf6378ffc78b225eb2129405e3ad';
$req = new AliexpressSolutionOrderGetRequest;
$param0 = new OrderQuery;
$param0->create_date_end="2021-08-15 12:12:12";
$param0->create_date_start="2021-10-12 12:12:12";
$param0->modified_date_start="2017-10-12 12:12:12";
$param0->order_status_list="SELLER_PART_SEND_GOODS";
$param0->page_size="20";
$param0->current_page="1";
$param0->order_status="SELLER_PART_SEND_GOODS";
$req->setParam0(json_encode($param0));
$resp = $c->execute($req, $sessionKey);

print_r($resp);