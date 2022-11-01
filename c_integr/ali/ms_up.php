<?php

/**
 * Sync MS products with Aliexpress
 **/

use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MySklad\MoySklad;

require_once 'vendor/autoload.php';
require_once 'db/DbConnection.php';
require_once 'env.php';
require_once 'functions.php';


$tmp_dir = __DIR__ . '/tmp';
if (!file_exists($tmp_dir)) {
    mkdir($tmp_dir, 0777, true);
}
// Connect logger
$log_dir = 'logs/' . date('Y-m') . '/' . date('d');
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}
$stream = new StreamHandler($log_dir . '/ali.log', Logger::DEBUG);
$logger = new Logger('ms');
$logger->pushHandler($stream);

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
//    $db_connection->createTables();

} catch (Exception $e) {
    $logger->error('Problems with connection to db', ['trace' => $e->getTrace()]);
}

try {
// --- Connect Aliexpress ---
// СДК скачал из настроек приложения али и заменил composer пакет, т.е. заменил файлы латика.
    $topclient = new TopClient();
    $topclient->appkey = $GLOBALS['ali_app']['appKey'];
    $topclient->secretKey = $GLOBALS['ali_app']['appSecret'];
    $sessionKey = $GLOBALS['ali_app']['token'];

} catch (Exception $e) {
    $logger->error('Problems with topclient', ['trace' => $e->getTrace()]);
}

$time_start = microtime(true);

try {
// --- connect MoySklad ---
    $ms = new MoySklad(base64_encode($msAuth['base']));
    // nginx time-out. need event manager
    if (file_exists($tmp_dir . '/next.href')) {
        $next_href = file_get_contents($tmp_dir . '/next.href');
        $product_groups = $ms->getAllProductfolders(50, (string)$next_href); // todo how to filter by parent folder
    } else {
//        $sss = $db_connection->setAllProductsStateDelete();
        $sss = $db_connection->setAllProductsUnpublished();
        $product_groups = $ms->getAllProductfolders(50);
    }

    if (!empty($product_groups->meta->nextHref)) {
        $logger->debug('Next href ' . $product_groups->meta->nextHref);
        file_put_contents($tmp_dir . '/next.href', (string)$product_groups->meta->nextHref);
    } else {
        $logger->debug('Del Next href ');
        $sss = $db_connection->setAllUnpublishedProductsStateDelete();
        unlink($tmp_dir . '/next.href');
    }

    $count_all_products = 0;
    $count = 0;
    if (!empty($product_groups->errors))
        $logger->error("Can't get all product groups", ['err' => $product_groups->errors[0]]);
    if (!empty($product_groups->rows))
        foreach ($product_groups->rows as $group) {
            if ($count >= 30) {
                sleep(3);
                $count = 0;
            }
            $exist_group = checkGroupName($group->pathName);
            if ($exist_group) {
                $ms_products = $ms->getProducts('pathName=' . rawurlencode($group->pathName . '/' . $group->name));
                $count_all_products += (int)$ms_products->meta->size;

                $logger->debug("Count all products {$count_all_products}", [
                    'pr_in_cat_name' => $group->pathName . '/' . $group->name,
                    'size' => (int)$ms_products->meta->size,
                    'meta' => $ms_products->meta
                ]);

//                $ms_products = $ms->getAssortment("productFolder={$group->meta->href};stockStore=https://online.moysklad.ru/api/remap/1.3/entity/store/7d8ae43d-eb9a-11e9-0a80-03b700248002"); // Нужен только склад — СКЛАД
//$logger->debug('Products from MS', ['ms_products' => $ms_products]);
                if (!empty($ms_products->rows)) {
                    add_products($ms_products->rows);
                } else {
                    $logger->error('Get products failed or empty',
                        ['errors' => @$ms_products->errors[0], 'response' => $ms_products, 'pathname' => $group->pathName]
                    );
                }
                if (!empty($ms_products->meta->nextHref)) {
                    $ms_products = $ms->sendAPIRequest($ms_products->meta->nextHref);
                    if (!empty($ms_products->rows)) {
                        add_products($ms_products->rows);
                    } else {
                        $logger->error('Get products failed or empty',
                            ['errors' => $ms_products->errors[0], 'response' => $ms_products, 'pathname' => $group->pathName]
                        );
                    }
                }
            }
        }

} catch (Exception $e) {
    $logger->error('Problems with MS', ['trace' => $e->getTrace()]);
}

$time_end = microtime(true);
$time = $time_end - $time_start;
$logger->debug('Exec time up up_ms ' . date('i:s', $time));


try {
    $_GET['check_ali'] = false; // todo убрать когда закончится тест

// -- if necessary, compare with already created products on aliexpress --
    if ($_GET['check_ali'] == 'true') {
// list ali products
        echo 'Сравниваю товары с опубликоваными в Али<br>';
        $req = new AliexpressSolutionProductListGetRequest();
        $aeop_a_e_product_list_query = new ItemListQuery();
        $aeop_a_e_product_list_query->product_status_type = "onSelling";
        $req->setAeopAEProductListQuery(json_encode($aeop_a_e_product_list_query));
        $resp = $topclient->execute($req, $sessionKey);
        if (!empty($resp->result->aeop_a_e_product_display_d_t_o_list->item_display_dto)) {
            foreach ($resp->result->aeop_a_e_product_display_d_t_o_list->item_display_dto as $item) {
                // get custom ali product
                $req = new AliexpressSolutionProductInfoGetRequest;
                $req->setProductId((string)$item->product_id);
                $r = $topclient->execute($req, $sessionKey);
                if (!empty($r->result->aeop_ae_product_s_k_us->global_aeop_ae_product_sku->sku_code)) {
                    $article = (string)$r->result->aeop_ae_product_s_k_us->global_aeop_ae_product_sku->sku_code;
                    $in_stock = (int)$r->result->aeop_ae_product_s_k_us->global_aeop_ae_product_sku->ipm_sku_stock;
                } else
                    foreach ($r->result->aeop_ae_product_propertys->global_aeop_ae_product_property as $attr) {
                        if ($attr->attr_name == 'Model Number') // Продукты созданные вручную и артикул введен через атрибуты товара
                            $article = (string)$attr->attr_value;
                    }
                $where = empty($article) ? ['name' => (string)$item->subject] : ["OR" => ['name' => (string)$item->subject, 'article' => $article, 'barcode' => $article]];
                $p = $db_connection->getAllProducts($where);
                if (empty($p) and !empty($article)) {
                    if (!empty($ms_products)) {
                        foreach ($ms_products as $ms_pr) {
                            if ($ms_pr->article == $article)
                                $ms_product = $ms_pr;
                        }
                    } else {
                        $ms_pr = $ms->getAssortment("article=$article;code=$article");
                        if (!empty($ms_product->rows)) {
                            $ms_product = $ms_product->rows;
                        }
                    }
                    if (!empty($ms_product)) {
                        $ms_product = $ms_product->rows[0];
                        $ms_product_json = json_encode($ms_product);
//                        $db_connection->createProduct($ms_product, $ms_product->quantity, $ms_product_json);
                        $db_connection->createProduct($ms_product, $ms_product->stock, $ms_product_json);
                        $db_connection->updateProduct(['state' => 'Stock', 'ali_product_id' => $item->product_id, 'ali_online' => 1], ['ms_id' => $ms_product['id']]);
                    }
                } else {
                    if (!empty($in_stock) and ($p[0]['in_stock'] != $in_stock))
                        $db_connection->updateProduct([
                            'ali_product_id' => (string)$item->product_id,
                            'ali_online' => 1,
                            'state' => 'Stock'], $where);
                    else
                        $db_connection->updateProduct(['ali_product_id' => (string)$item->product_id, 'ali_online' => 1], $where);

                    echo 'Product ' . (string)$item->subject . ' already in aliexpress. Product id: ' . (string)$item->product_id . '<br>';
                }
            }
        } else {
            $logger->error('There are no products on aliexpress or the request ended in error', ['response' => $resp]);
        }
    }

} catch (Exception $e) {
    $logger->error('Problems with topclient', ['trace' => $e->getTrace()]);
}

echo '<br>end';
