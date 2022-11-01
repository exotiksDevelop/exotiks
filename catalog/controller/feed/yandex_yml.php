<?php
/**
* Yandex.YML data feed for OpenCart (ocStore) 2.0.x-2.2.x
*
* Main class to create YML
*
* @author Yesvik http://opencartforum.ru/user/6876-yesvik/
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2013- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*
* Official version of this module: http://opencartforum.ru/files/file/670-eksport-v-iandeksmarket/
*/

/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
//Версия модуля
define ('YANDEX_YML_VERSION', '1.9.6');

//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('error_reporting', E_ALL & ~E_NOTICE);

class ControllerFeedYandexYml extends Controller {
//++++ Config section ++++
    //До какой длины укорачивать описание товара. 0 - не укорачивать
    protected $SHORTER_DESCRIPTION = 0;
    //Отдавать ли Яндексу оригиналы фотографий товаров. Если false - то всегда масштабировать
    protected $ORIGINAL_IMAGES = true;
    //Сколько товаров брать из базы за один запрос 
    //(чем больше товаров, тем больше потребление памяти, чем товаров меньше - тем больше нагрузка на SQL)
    protected $CHUNK_SIZE = 8000;
    //1-секундная задержка между генерацией блоков для снижения нагрузки на SQL
    protected $SLEEP = 1000;
    //Брать группу покупателей для выбора цен из модуля ProgRoman - CityManager+GeoIP Pro
    protected $progroman_city_manager = false;
//---- Config section ----
    protected $CONFIG_PREFIX = 'yandex_yml_';

    protected $shop = array();
    protected $currencies = array();
    protected $offers_currency;
    protected $offers_currency_value;
    protected $decimal_place;
    protected $shop_currency;
    protected $shop_currency_value;
    
    protected $customer_group;
    
    protected $categories = array();
    protected $delivery_option = false;
    protected $offers = array();
    //protected $from_charset = 'utf-8';
    protected $eol = "\n";
    protected $yml = '';
    
    protected $is_main_category;
    protected $color_options;
    protected $size_options;
    protected $size_units;
    protected $optioned_name;
    protected $numpictures;
    protected $option_image;
    protected $option_image_pro = false;
    
    protected $image_width;
    protected $image_height;
    
    protected $product_optioned_ids = array();
    protected $product_ids = array();

    public function index() {
        $token = $this->config->get($this->CONFIG_PREFIX.'token');
        if ($token && (!isset($this->request->get['token']) || $this->request->get['token'] != $token)) {
            header("HTTP/1.0 403 Access Denied");
            echo 'Access Denied';
            return;
        }
        if ($this->config->get($this->CONFIG_PREFIX.'status')) {
            header('Content-Type: application/xml');
            $this->outYml();
        }
    }
    
    public function saveToFile() {
        $homedir = realpath(DIR_APPLICATION.'../');
        $filename = $homedir . '/export/' . $this->CONFIG_PREFIX . $this->config->get($this->CONFIG_PREFIX.'token') . '.xml';
        $fp = fopen($filename, 'w');
        if ($this->config->get($this->CONFIG_PREFIX.'status')) {
            $this->putYml($fp);
        }
        fclose($fp);
    }

    /**
    * Формирования YML до первого тэга <offer>
    */
    protected function getYmlHeader() {
        $this->load->model('export/yandex_yml');
        $this->is_main_category = $this->config->get($this->CONFIG_PREFIX.'ocstore');
        $this->load->model('localisation/currency');
        $this->load->model('tool/image');

        $this->image_width = max($this->config->get('config_image_popup_width'), 600);
        $this->image_height = max($this->config->get('config_image_popup_height'), 600);
        $this->numpictures = $this->config->get($this->CONFIG_PREFIX.'numpictures');
        $this->option_image = $this->config->get($this->CONFIG_PREFIX.'option_image');
        if ($this->config->get($this->CONFIG_PREFIX.'option_image_pro')) {
            $this->load->model('module/product_option_image_pro');
            if ($this->model_module_product_option_image_pro->installed()) {
                $this->option_image_pro = true;
            }
        }
        
        // Магазин
        $this->setShop('name', $this->config->get('config_name'));
        //$this->setShop('name', $this->config->get($this->CONFIG_PREFIX.'shopname'));
        $this->setShop('company', $this->config->get('config_owner'));
        //$this->setShop('company', $this->config->get($this->CONFIG_PREFIX.'company'));
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $HTTP_SERVER = $this->config->get('config_ssl');
        } else {
            $HTTP_SERVER = $this->config->get('config_url');
        }    
        $this->setShop('url', $HTTP_SERVER);
        $this->setShop('phone', $this->config->get('config_telephone'));
        $this->setShop('platform', 'Yandex.YML for OpenCart (ocStore)');
        $this->setShop('version', YANDEX_YML_VERSION);
        if ($this->config->get($this->CONFIG_PREFIX.'auto_discounts')) {
            $this->setShop('enable_auto_discounts', 'yes');
        }

        // Валюты
        $this->offers_currency = $this->config->get($this->CONFIG_PREFIX.'currency');
        if (!$this->currency->has($this->offers_currency)) exit();

        $this->decimal_place = intval($this->currency->getDecimalPlace($this->offers_currency));

        $this->shop_currency = $this->config->get('config_currency');
        $this->shop_currency_value = $this->currency->getValue($this->shop_currency);

        $this->setCurrency($this->offers_currency, 1);
        $this->offers_currency_value = $this->currency->getValue($this->offers_currency);
        
        $this->customer_group = $this->config->get($this->CONFIG_PREFIX.'groupprice');
        if (!$this->customer_group) {
            $this->customer_group = $this->config->get('config_customer_group_id');
        }

        $currencies = $this->model_localisation_currency->getCurrencies();

        $supported_currencies = array('RUR', 'RUB', 'USD', 'EUR', 'BYR', 'BYN', 'KZT', 'UAH');

        $currencies = array_intersect_key($currencies, array_flip($supported_currencies));

        foreach ($currencies as $currency) {
            if ($currency['code'] != $this->offers_currency && $currency['status'] == 1) {
                $this->setCurrency($currency['code'], number_format($this->offers_currency_value/$currency['value'], 4, '.', ''));
            }
        }
        //Тип данных vendor.model или default
        $datamodel = $this->config->get($this->CONFIG_PREFIX.'datamodel');
        
        // Категории
        $allowed_categories = $this->config->get($this->CONFIG_PREFIX.'categories');
        $allowed_manufacturers = $this->config->get($this->CONFIG_PREFIX.'manufacturers');
        $blacklist_type = $this->config->get($this->CONFIG_PREFIX.'blacklist_type');
        $blacklist = $this->config->get($this->CONFIG_PREFIX.'blacklist');
        $product_rel = $this->config->get($this->CONFIG_PREFIX.'product_rel');
        $out_of_stock_ids = explode(',', $this->config->get($this->CONFIG_PREFIX.'out_of_stock')); // id статуса товара "Нет на складе"
        $this->categories = $this->model_export_yandex_yml->getCategoryTree($allowed_categories, $blacklist_type, $blacklist, $out_of_stock_ids, $allowed_manufacturers, $this->is_main_category);
        
        $local_delivery_cost = $this->config->get($this->CONFIG_PREFIX.'delivery_cost');
        if ($local_delivery_cost!='') {
            $this->delivery_option = array('cost'=>$this->getDeliveryPrice($local_delivery_cost, 0),
                'days'=>$this->config->get($this->CONFIG_PREFIX.'delivery_days'));
            $delivery_before = $this->config->get($this->CONFIG_PREFIX.'delivery_before');
            if ($delivery_before) {
                /*
                if (strpos($delivery_before, ':') == false) {
                    $delivery_before.= ':00';
                }
                */
                $this->delivery_option['before'] = $delivery_before;
            }
        }
        
        //+++ Вывод +++
        $yml  = '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol;
        $yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
        $yml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
        $yml .= '<shop>' . $this->eol;

        // информация о магазине
        $yml .= $this->array2Tag($this->shop);

        // валюты
        $yml .= '<currencies>' . $this->eol;
        foreach ($this->currencies as $currency) {
            $yml .= $this->getElement($currency, 'currency');
        }
        $yml .= '</currencies>' . $this->eol;

        // категории
        $yandex_yml_categ_portal_id = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_portal_id'));
        
        $yml .= '<categories>' . $this->eol;
        foreach ($this->categories as $category) {
            if (isset($yandex_yml_categ_portal_id[$category['id']]) && $yandex_yml_categ_portal_id[$category['id']]!='') {
                $category['portal_id'] = $yandex_yml_categ_portal_id[$category['id']];
            }
            $category_name = $this->prepareField($category['name']);
            unset($category['name'], $category['export']);
            $yml .= $this->getElement($category, 'category', $category_name);
        }
        $yml .= '</categories>' . $this->eol;
        if (is_array($this->delivery_option) && !$this->config->get($this->CONFIG_PREFIX.'local_delivery')) {
            $yml .= $this->array2Delivery($this->delivery_option).$this->eol;
        }

        // товарные предложения
        $yml .= '<offers>' . $this->eol;
        return $yml;
        //--- Вывод ---
    }
    
    protected function getYmlChunk($page = 0) {
        $this->offers = array();
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $HTTP_SERVER = $this->config->get('config_ssl');
        } else {
            $HTTP_SERVER = $this->config->get('config_url');
        }                             
        if (!defined('HTTP_IMAGE')) {
            define('HTTP_IMAGE', $HTTP_SERVER . 'image/');
        }
        $this->load->model('export/yandex_yml');
        $this->load->model('localisation/currency');
        $this->load->model('tool/image');

        //Тип данных vendor.model или default
        $datamodel = $this->config->get($this->CONFIG_PREFIX.'datamodel');
        
        // Товарные предложения
        $in_stock_ids = explode(',', $this->config->get($this->CONFIG_PREFIX.'in_stock')); // id статуса товара "В наличии"
        $out_of_stock_ids = explode(',', $this->config->get($this->CONFIG_PREFIX.'out_of_stock')); // id статуса товара "Нет на складе"

        $pickup = ($this->config->get($this->CONFIG_PREFIX.'pickup') ? 'true' : false);
        
        $local_delivery_cost = $this->config->get($this->CONFIG_PREFIX.'delivery_cost');
            
        $store = ($this->config->get($this->CONFIG_PREFIX.'store') ? 'true' : false);
        $unavailable = $this->config->get($this->CONFIG_PREFIX.'unavailable');

        $allowed_categories = $this->config->get($this->CONFIG_PREFIX.'categories');
        $allowed_manufacturers = $this->config->get($this->CONFIG_PREFIX.'manufacturers');
        $blacklist_type = $this->config->get($this->CONFIG_PREFIX.'blacklist_type');
        $blacklist = $this->config->get($this->CONFIG_PREFIX.'blacklist');
        $product_rel = $this->config->get($this->CONFIG_PREFIX.'product_rel');
        $product_accessory = $this->config->get($this->CONFIG_PREFIX.'product_accessory');
        $skip = $page*$this->CHUNK_SIZE;
        $limit = $this->CHUNK_SIZE;
        
        if ($this->numpictures > 1) {
            //++++ Дополнительные изображения товара ++++
            $product_images = $this->model_export_yandex_yml->getProductImages($this->numpictures - 1);
            //---- Дополнительные изображения товара ----
        }
        $all_attributes = $this->model_export_yandex_yml->getAttributes($this->config->get($this->CONFIG_PREFIX.'attributes'));
        $this->optioned_name = $this->config->get($this->CONFIG_PREFIX.'optioned_name');
        
        $yandex_yml_categ_sales_notes = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_sales_notes'));
        $yandex_yml_categ_type_prefix = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_type_prefix'));
        $yandex_yml_categ_delivery_cost = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_delivery_cost'));
        $yandex_yml_categ_delivery_days = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_delivery_days'));

        $yandex_yml_manuf_sales_notes = unserialize($this->config->get($this->CONFIG_PREFIX.'manuf_sales_notes'));
        $yandex_yml_manuf_delivery_cost = unserialize($this->config->get($this->CONFIG_PREFIX.'manuf_delivery_cost'));
        $yandex_yml_manuf_delivery_days = unserialize($this->config->get($this->CONFIG_PREFIX.'manuf_delivery_days'));
        
        $this->color_options = explode(',', $this->config->get($this->CONFIG_PREFIX.'color_options'));
        $this->size_options = explode(',', $this->config->get($this->CONFIG_PREFIX.'size_options'));
        $this->size_units = $this->config->get($this->CONFIG_PREFIX.'size_units') ? unserialize($this->config->get($this->CONFIG_PREFIX.'size_units')) : array();
        
        $is_out = true;
        $yml = '';
        
        foreach ($this->model_export_yandex_yml->getProduct($allowed_categories, $blacklist_type, $blacklist, $out_of_stock_ids, $allowed_manufacturers, $this->customer_group, $product_rel || $product_accessory, $skip, $limit, $this->is_main_category) as $product) {
            $is_out = false;
            $data = array();

            // Атрибуты товарного предложения
            $data['id'] = $product['product_id'];
            $data['group_id'] = $product['product_id'];
            $data['type'] = $product['manufacturer'] ? $datamodel : 'default'; //'vendor.model' или 'default';
            if (!$unavailable && ($product['quantity'] > 0)) {
                $data['available'] = 'true';
            }
            elseif (in_array($product['stock_status_id'], $in_stock_ids)) {
                $data['available'] = 'true';
            }
            else {
                $data['available'] = false;
            }
//                $data['bid'] = 10;
//                $data['cbid'] = 15;

            // Параметры товарного предложения
            $data['url'] = $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
            if ($this->config->get($this->CONFIG_PREFIX.'utm_label')) {
                $data['url'].= (strpos($data['url'], '?') === false ? '?' : '&').str_replace('{product_id}', $product['product_id'], $this->config->get($this->CONFIG_PREFIX.'utm_label'));
            }
            if ($product['special'] && $product['special'] < $product['price']) {
                if ($this->config->get($this->CONFIG_PREFIX.'price_promo')) {
                    $data['price_promo'] = $product['special'];
                    $data['price'] = $product['price'];
                }
                else {
                    $data['price'] = $product['special'];
                }
                
                if ($this->config->get($this->CONFIG_PREFIX.'oldprice')) {
                    $data['oldprice'] = $product['price'];
                }
                if ($this->config->get($this->CONFIG_PREFIX.'price_old')) {
                    $data['price_old'] = $product['price'];
                }
            }
            else {
                $data['price'] = $product['price'];
            }
            
            if ($this->config->get($this->CONFIG_PREFIX.'opt_discount')) {
                $data['opt_prices'] = $this->model_extension_feed_yandex_yml->getProductDiscounts($product['product_id'], $this->customer_group);
            }
            
            if ($this->config->get($this->CONFIG_PREFIX.'purchase_price')) {
                if (isset($product[$this->config->get($this->CONFIG_PREFIX.'purchase_price')])) {
                    $data['purchase_price'] = $product[$this->config->get($this->CONFIG_PREFIX.'purchase_price')];
                    if ($data['purchase_price'] == '') {
                        unset($data['purchase_price']);
                    }
                }
            }
            
            
            $data['currencyId'] = $this->offers_currency;
            $data['categoryId'] = $product['category_id'];
            
            if ($pickup) {
                $data['pickup'] = $pickup;
            }
            if ($store) {
                $data['store'] = $store;
            }
            if ($product['shipping']) {
                $data['delivery'] = 'true';
                if (is_array($this->delivery_option) && isset($this->delivery_option['cost'])) {
                    $total = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                    if (isset($yandex_yml_manuf_delivery_cost[$product['manufacturer_id']]) && $yandex_yml_manuf_delivery_cost[$product['manufacturer_id']]!='') {
                        $delivery_cost = $this->getDeliveryPrice($yandex_yml_manuf_delivery_cost[$product['manufacturer_id']], $total);
                    }
                    elseif (isset($yandex_yml_categ_delivery_cost[$product['category_id']]) && $yandex_yml_categ_delivery_cost[$product['category_id']]!='') {
                        $delivery_cost = $this->getDeliveryPrice($yandex_yml_categ_delivery_cost[$product['category_id']], $total);
                    }
                    else {
                        $delivery_cost = $this->getDeliveryPrice($local_delivery_cost, $total);
                    }
                    
                    if ($data['available']) {
                        if (isset($yandex_yml_manuf_delivery_days[$product['manufacturer_id']]) && $yandex_yml_manuf_delivery_days[$product['manufacturer_id']]!='') {
                            $delivery_days = $yandex_yml_manuf_delivery_days[$product['manufacturer_id']];
                        }
                        elseif (isset($yandex_yml_categ_delivery_days[$product['category_id']]) && $yandex_yml_categ_delivery_days[$product['category_id']]!='') {
                            $delivery_days = $yandex_yml_categ_delivery_days[$product['category_id']];
                        }
                        else {
                            $delivery_days = $this->delivery_option['days'];
                        }
                    }
                    else {
                        $delivery_days = '33'; //Если товар под заказ, то срок доставки должен быть >=32 дня 
                    }
                    
                    if ($this->config->get($this->CONFIG_PREFIX.'local_delivery')) {
                        $data['local_delivery_cost'] = $delivery_cost;
                    }
                    elseif ($delivery_cost != $this->delivery_option['cost'] || $delivery_days != $this->delivery_option['days']) {
                        $data['delivery-option'] = $this->delivery_option;
                        $data['delivery-option']['cost'] = $delivery_cost;
                        $data['delivery-option']['days'] = $delivery_days;
                    }
                }
            }
            else {
                $data['delivery'] = 'false';
            }
            
            $data['name'] = $this->getFieldValue($product, $this->config->get($this->CONFIG_PREFIX.'name_field'), 'name');
            $data['vendor'] = $product['manufacturer'];
            $data['model'] = $this->getFieldValue($product, $this->config->get($this->CONFIG_PREFIX.'model_field'), 'model');            
            if ($this->config->get($this->CONFIG_PREFIX.'vendorcode_field')) {
                $data['vendorCode'] = $product[$this->config->get($this->CONFIG_PREFIX.'vendorcode_field')];
                if ($data['vendorCode'] == '') {
                    unset($data['vendorCode']);
                }
            }
            if ($this->config->get($this->CONFIG_PREFIX.'typeprefix_field')) {
                $data['typePrefix'] = $product[$this->config->get($this->CONFIG_PREFIX.'typeprefix_field')];
                if ($data['typePrefix'] == '') {
                    unset($data['typePrefix']);
                }
            }
            if ($this->config->get($this->CONFIG_PREFIX.'barcode_field')) {
                $data['barcode'] = $product[$this->config->get($this->CONFIG_PREFIX.'barcode_field')];
                if ($data['barcode'] == '') {
                    unset($data['barcode']);
                }
            }
            if ($this->config->get($this->CONFIG_PREFIX.'keywords_field')) {
                $data['keywords'] = $product[$this->config->get($this->CONFIG_PREFIX.'keywords_field')];
                if ($data['keywords'] == '') {
                    unset($data['keywords']);
                }
            }
            if ($this->config->get($this->CONFIG_PREFIX.'market_sku_field')) {
                $data['market-sku'] = $product[$this->config->get($this->CONFIG_PREFIX.'market_sku_field')];
                if ($data['market-sku'] == '') {
                    unset($data['market-sku']);
                }
            }
            if ($this->config->get($this->CONFIG_PREFIX.'manufacturer_field')) {
                $data['manufacturer'] = $product[$this->config->get($this->CONFIG_PREFIX.'manufacturer_field')];
                if ($data['manufacturer'] == '') {
                    unset($data['manufacturer']);
                }
            }
            
            if ($this->config->get($this->CONFIG_PREFIX.'count')) {
                $data['count'] = intval($product['quantity']);
            }
            if ($this->config->get($this->CONFIG_PREFIX.'quantity')) {
                $data['quantity'] = intval($product['quantity']);
            }
            if ($this->config->get($this->CONFIG_PREFIX.'stock_quantity')) {
                $data['stock_quantity'] = intval($product['quantity']);
            }
            if ($this->config->get($this->CONFIG_PREFIX.'quantity_in_stock')) {
                $data['quantity_in_stock'] = intval($product['quantity']);
            }
            
            
            $sales_notes = $this->config->get($this->CONFIG_PREFIX.'sales_notes');
            if ($sales_notes) {
                $data['sales_notes'] = $sales_notes;
            }
            if (isset($yandex_yml_categ_sales_notes[$product['category_id']]) && $yandex_yml_categ_sales_notes[$product['category_id']]!='') {
                $data['sales_notes'] = $yandex_yml_categ_sales_notes[$product['category_id']];
            }
            if (isset($yandex_yml_manuf_sales_notes[$product['manufacturer_id']]) && $yandex_yml_manuf_sales_notes[$product['manufacturer_id']]!='') {
                $data['sales_notes'] = $yandex_yml_manuf_sales_notes[$product['manufacturer_id']];
            }
            if (isset($data['sales_notes'])) {
                $data['sales_notes'] = mb_substr($data['sales_notes'], 0, 50);
            }

            if ((!isset($data['typePrefix']) || !$data['typePrefix']) && isset($yandex_yml_categ_type_prefix[$product['category_id']]) && $yandex_yml_categ_type_prefix[$product['category_id']]!='') {
                $data['typePrefix'] = $yandex_yml_categ_type_prefix[$product['category_id']];
            }
            
            if ($this->numpictures > 0) {
                if ($product['image'] && $product['image'] != 'no_image.png' && $product['image'] != 'no_image.jpg') {
                    $data['picture'] = array($this->prepareImage($product['image']));
                }
                //++++ Дополнительные изображения товара ++++
                if (isset($product_images[$product['product_id']])) {
                    if (!isset($data['picture']) || !is_array($data['picture'])) {
                        $data['picture'] = array();
                    }
                    foreach ($product_images[$product['product_id']] as $image) {
                        if ($image == 'no_image.png' || $image == 'no_image.jpg') {
                            continue;
                        }
                        $data['picture'][] = $this->prepareImage($image);
                    }
                }
                //---- Дополнительные изображения товара ----
            }

            if ($product_rel && $product['rel']) {
                $data['rec'] = $product['rel'];
            }
            
            /*++++ Атрибуты товара ++++
            // пример структуры массива для вывода параметров
            $data['param'] = array(
                array(
                    'name'=>'Wi-Fi',
                    'value'=>'есть'
                ), array(
                    'name'=>'Размер экрана',
                    'unit'=>'дюйм',
                    'value'=>'20'
                ), array(
                    'name'=>'Вес',
                    'unit'=>'кг',
                    'value'=>'4.6'
                )
            );
            */
            if ($this->config->get($this->CONFIG_PREFIX.'all_adult')) {
                $data['adult'] = 'true';
            }
            if ($this->config->get($this->CONFIG_PREFIX.'all_manufacturer_warranty')) {
                $data['manufacturer_warranty'] = 'true';
            }
            
            $data['param'] = array();
            $attributes = $this->model_export_yandex_yml->getProductAttributes($product['product_id']);
            $attr_text = array();
            if ($this->config->get($this->CONFIG_PREFIX.'exportattr')) {
                $data['noexport'] = true;
            }
            if (count($attributes) > 0) {
                foreach ($attributes as $attr) {
                    if ($this->config->get($this->CONFIG_PREFIX.'exportattr') && $attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'exportattr') && $attr['text'] != '') {
                        unset($data['noexport']);
                    }
                    if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'adult')) {
                        $data['adult'] = 'true';
                    }
                    if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'manufacturer_warranty')) {
                        $data['manufacturer_warranty'] = 'true';
                    }
                    if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'country_of_origin')) {
                        $data['country_of_origin'] = $attr['text'];
                    }
                    if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'tn_ved_codes')) {
                        $data['tn-ved-codes'] = $attr['text'];
                    }
                    if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'sales_notesattr') && $attr['text'] != '') {
                        $data['sales_notes'] = $attr['text'];
                    }
                    if (isset($all_attributes[$attr['attribute_id']])) {
                        $data['param'][] = $this->detectUnits(array(
                            'name' => $all_attributes[$attr['attribute_id']],
                            'value' => $attr['text']));
                    }
                    $attr_text[] = $attr['name'].': '.$attr['text'];
                    
                    if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'condition_used') && $attr['text']) {
                        $data['condition'] = array('type'=>'used', 'reason'=>$attr['text']);
                    }
                    elseif ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'condition_likenew') && $attr['text']) {
                        $data['condition'] = array('type'=>'likenew', 'reason'=>$attr['text']);
                    }
                    
                    
                }
            }
            if (isset($data['noexport']) && $data['noexport']) {
                continue;
            }
            
            if (floatval($product['weight']) > 0) {
                if ($product['weight_unit'] == 'кг' || $product['weight_unit'] == 'kg') {
                    $data['weight'] = number_format(floatval($product['weight']), 3, '.', '');
                }
                elseif ($product['weight_unit'] == 'г' || $product['weight_unit'] == 'g' || $product['weight_unit'] == 'гр' || $product['weight_unit'] == 'gr') {
                    $data['weight'] = number_format(floatval($product['weight'])/1000, 3, '.', '');
                }
                else {
                    $data['param'][] = array('id'=>'WEIGHT', 'name'=>'Вес', 'value'=>$product['weight'], 'unit'=>$product['weight_unit']);
                }
            }
            $data['weight_unit'] = $product['weight_unit'];
            
            if ((floatval($product['length']) > 0) && (floatval($product['width']) > 0) && (floatval($product['height']) > 0)) {
                $lenght_mul = 1;
                if ($product['length_class_id'] == 2) {
                    $lenght_mul = 0.001;
                }
                elseif ($product['length_class_id'] == 3) {
                    $lenght_mul = 2.5;
                }
                $length = number_format(floatval($product['length'])*$lenght_mul, 3, '.', '');
                $width = number_format(floatval($product['width'])*$lenght_mul, 3, '.', '');
                $height = number_format(floatval($product['height'])*$lenght_mul, 3, '.', '');
                
                if ($this->config->get($this->CONFIG_PREFIX.'dimensions')) {
                    $data['dimensions'] = $length.'/'.$width.'/'.$height;
                }
                
                if ($this->config->get($this->CONFIG_PREFIX.'length_width_height')) {
                    $data['length'] = $length;
                    $data['width'] = $width;
                    $data['height'] = $height;
                }
                
            }
            
            //---- Атрибуты товара ----
            
            //++++ Описание товара ++++
            $description_field = $this->config->get($this->CONFIG_PREFIX.'description_field');
            if ($description_field) {
                if ($description_field == 'attr_vs_description') {
                    $product_description = implode($attr_text,"<br/>\n");
                }
                else {
                    $product_description = $product[$description_field];
                }
                
                if ($this->SHORTER_DESCRIPTION > 0) {
                    $product_description = strip_tags($product_description);
                    $product_description = mb_substr($product_description, 0, $this->SHORTER_DESCRIPTION, 'UTF-8');
                }
                
                
                if ($this->config->get($this->CONFIG_PREFIX.'export_tags')) {
                    $data['description'] = '<![CDATA['.$product_description.']]>';
                }
                else {
                    $data['description'] = strip_tags($product_description);
                }
            }
            //---- Описание товара ----

            if ($product['minimum'] > 1) {
                if ($this->config->get($this->CONFIG_PREFIX.'min_quantity')) {
                    $data['min-quantity'] = $product['minimum'];
                    $data['step-quantity'] = $product['minimum'];
                }
                if ($this->config->get($this->CONFIG_PREFIX.'order_quantity')) {
                    $data['minimum_order_quantity'] = $product['minimum'];
                }
            }

            if (!$this->setOptionedOffer($data, $product)) {
                $data['price'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                if (isset($data['oldprice'])) {
                    $data['oldprice'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                if (isset($data['price_old'])) {
                    $data['price_old'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['price_old'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                if (isset($data['price_prmo'])) {
                    $data['price_prmo'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['price_prmo'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                if (isset($data['purchase_price'])) {
                    $data['purchase_price'] = number_format($this->shop_currency_value * $this->currency->convert($data['purchase_price'], $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                
                $this->setOffer($data);
            }
            unset($data);
            unset($product);
            //++++ Вывод ++++
            foreach ($this->offers as $idx=>$offer) {
                $this->offers[$idx] = null;
                $tags = $this->array2Tag($offer['data']);
                unset($offer['data']);
                if (isset($offer['delivery-option'])) {
                    $tags .= $this->array2Delivery($offer['delivery-option']);
                    unset($offer['delivery-option']);
                }
                if (isset($offer['condition'])) {
                    $tags .= $this->data2Condition($offer['condition']['type'], $offer['condition']['reason']);
                    unset($offer['condition']);
                }
                if (isset($offer['age'])) {
                    $tags .= '<age unit="year">18</age>'.$this->eol;
                    unset($offer['age']);
                }
                if (isset($offer['param'])) {
                    $tags .= $this->array2Param($offer['param']);
                    unset($offer['param']);
                }
                if (isset($offer['prices'])) {
                    $tags .= $this->array2Prices($offer['prices']);
                    unset($offer['prices']);
                }
                if (isset($offer['tn-ved-codes'])) {
                    $tags .= '<tn-ved-codes><tn-ved-code>'.$this->prepareField($offer['tn-ved-codes']).'</tn-ved-code></tn-ved-codes>'. $this->eol;
                    unset($offer['tn-ved-codes']);
                }
                $yml.= $this->getElement($offer, 'offer', $tags);
            }
            //---- Вывод ----
            $this->offers = array();
        }
        if ($is_out) {
            return 'OUT';
        }
        return $yml;
    }

    /**
    * Возвращает значение для тэга, когда источник данных тэга выбирается
    */
    protected function getFieldValue($product, $field, $default=false) {
        if (!$field) {
            if ($default) {
                return $product[$default];
            }
            return false;
        }
        elseif ($field == 'name+model') {
            return trim($product['name'].' '.$product['model']);
        }
        elseif ($field == '{model} {option} {sku}') {
            return trim($product['model'] 
                . (isset($product['option_color']) ? ', '.$product['option_color'] : '') 
                . (isset($product['option_size']) ? ', '.$product['option_size'] : '') 
                . ' '. $product['sku']);
        }
        return $product[$field];
    }
    
    /**
     * Создает много элементов offer товарных предложений для разных опций цвет и размер товара
     */
    protected function setOptionedOffer($data, $product) {
        $offers_array = array();
        
        $coptions = array();
        if ($this->color_options)
            $coptions = $this->model_export_yandex_yml->getProductOptions($this->color_options, $product['product_id']);
        $soptions = array();
        if ($this->size_options)
            $soptions = $this->model_export_yandex_yml->getProductOptions($this->size_options, $product['product_id']);
        if (!count($coptions) && !count($soptions)) {
            return false;
        }
        $images_by_options = array();
        if ($this->option_image_pro) {
            $data['picture'] = array();
            $images_by_options = $this->model_module_product_option_image_pro->getProductOptionImagesByValues($product['product_id']);
        }
        
        //++++ Цвета x Размеры для магазинов одежды ++++
        if (count($coptions)) {
            foreach ($coptions as $option) {
                //Если в опциях кол-во равно 0, то в OpenCart эта опция не показывается совсем, хотя она может быть просто не быть в наличии
                if ($option['subtract'] && ($option['quantity'] <= 0)) {
                    continue;
                }
                $data_arr = $data;
                $product['option_color'] = trim($option['option_name'].' '.$option['name']);
                $data_arr['option_color'] = trim($option['option_name'].' '.$option['name']);
                
                if (!isset($data_arr['picture']))
                    $data_arr['picture'] = array();
                $data_arr['picture'] = array_slice(array_unique($this->getOptionedImages($product, $data_arr['picture'], $option, $images_by_options)), 0, $this->numpictures);
                
                $data_arr['param'][] = array('name'=>(isset($option['name_main']) ? $option['name_main'] : $option['option_name']), 'value'=>$option['name']);
                $data_arr['group_id'] = $product['product_id'];
                $data_arr['product_option_value_id'] = $option['product_option_value_id'];
                $data_arr['available'] = $data_arr['available'] && ($option['quantity'] > 0);
                
                if ($this->config->get($this->CONFIG_PREFIX.'count') && $option['subtract']) {
                    $data_arr['count'] = intval($option['quantity']);
                }
                if ($this->config->get($this->CONFIG_PREFIX.'quantity') && $option['subtract']) {
                    $data_arr['quantity'] = intval($option['quantity']);
                }
                if ($this->config->get($this->CONFIG_PREFIX.'stock_quantity') && $option['subtract']) {
                    $data_arr['stock_quantity'] = intval($option['quantity']);
                }
                if ($this->config->get($this->CONFIG_PREFIX.'quantity_in_stock') && $option['subtract']) {
                    $data_arr['quantity_in_stock'] = intval($option['quantity']);
                }
                
                if ($option['price_prefix'] == '+') {
                    $data_arr['price']+= $option['price'];
                    if (isset($data_arr['oldprice']))
                        $data_arr['oldprice']+= $option['price'];
                    if (isset($data_arr['price_old']))
                        $data_arr['price_old']+= $option['price'];
                    if (isset($data_arr['price_promo']))
                        $data_arr['price_promo']+= $option['price'];
                }
                elseif ($option['price_prefix'] == '-') {
                    $data_arr['price']-= $option['price'];
                    if (isset($data_arr['oldprice']))
                        $data_arr['oldprice']-= $option['price'];
                    if (isset($data_arr['price_old']))
                        $data_arr['price_old']-= $option['price'];
                    if (isset($data_arr['price_promo']))
                        $data_arr['price_promo']-= $option['price'];
                }
                elseif ($option['price_prefix'] == 'u' && $option['price']) {
                    $option['price'] = $data_arr['price']/100*floatval($option['price']);
                    $data_arr['price']+= $option['price'];
                    if (isset($data_arr['oldprice']))
                        $data_arr['oldprice']+= $option['price'];
                    if (isset($data_arr['price_old']))
                        $data_arr['price_old']+= $option['price'];
                    if (isset($data_arr['price_promo']))
                        $data_arr['price_promo']+= $option['price'];
                }
                elseif ($option['price_prefix'] == 'd' && $option['price']) {
                    $option['price'] = $data_arr['price']/100*floatval($option['price']);
                    $data_arr['price']-= $option['price'];
                    if (isset($data_arr['oldprice']))
                        $data_arr['oldprice']-= $option['price'];
                    if (isset($data_arr['price_old']))
                        $data_arr['price_old']-= $option['price'];
                    if (isset($data_arr['price_promo']))
                        $data_arr['price_promo']-= $option['price'];
                }
                elseif ($option['price_prefix'] == '=') {
                    $data_arr['price'] = $option['price'];
                }
                $data_arr = $this->setOptionedWeight($data_arr, $option, $data['weight_unit']);
                $data_arr['url'].= '#'.$option['product_option_value_id'];
                $offers_array[] = $data_arr;
            }
        }
        else {
            $data['group_id'] = $product['product_id'];
            $offers_array[] = $data;
        }
        // Размеры
        foreach ($offers_array as $i=>$data) {
            if (count($soptions)) {
                foreach ($soptions as $option) {
                    //Если в опциях кол-во равно 0, то в OpenCart эта опция не показывается совсем, хотя она может быть просто не быть в наличии
                    if ($option['subtract'] && ($option['quantity'] <= 0)) {
                        continue;
                    }
                    $size_option_name = (isset($option['name_main']) ? $option['name_main'] : $option['option_name']);
                    $size_option_unit = $this->size_units[$option['option_id']];
                    $data_arr = $data;
                    $product['option_size'] = $size_option_name.' '.$option['name'];
                    
                    if (!isset($data_arr['picture']))
                        $data_arr['picture'] = array();
                    $data_arr['picture'] = array_slice(array_unique($this->getOptionedImages($product, $data_arr['picture'], $option, $images_by_options)), 0, $this->numpictures);
                    
                    $size_param = array('name'=>$size_option_name, 'value'=>$option['name']);
                    
                    $size_str = explode('/', $option['name']);
                    if (in_array(strtoupper($size_str[0]), array('XXS','XS','S','M','L','XL','XXL', '2XS', '2XL'))) {
                        $size_param['unit'] = 'INT';
                    }
                    elseif ($size_option_unit) {
                        $size_param['unit'] = $size_option_unit;
                    } 
                    $data_arr['param'][] = $size_param;
                    $data_arr['available'] = $data_arr['available'] && ($option['quantity'] > 0);
                    
                    if ($this->config->get($this->CONFIG_PREFIX.'count') && $option['subtract']) {
                        $data_arr['count'] = intval($option['quantity']);
                    }
                    if ($this->config->get($this->CONFIG_PREFIX.'quantity') && $option['subtract']) {
                        $data_arr['quantity'] = intval($option['quantity']);
                    }
                    if ($this->config->get($this->CONFIG_PREFIX.'stock_quantity') && $option['subtract']) {
                        $data_arr['stock_quantity'] = intval($option['quantity']);
                    }
                    if ($this->config->get($this->CONFIG_PREFIX.'quantity_in_stock') && $option['subtract']) {
                        $data_arr['quantity_in_stock'] = intval($option['quantity']);
                    }

                    if ($option['price_prefix'] == '+') {
                        $data_arr['price']+= $option['price'];
                        if (isset($data_arr['oldprice']))
                            $data_arr['oldprice']+= $option['price'];
                        if (isset($data_arr['price_old']))
                            $data_arr['price_old']+= $option['price'];
                        if (isset($data_arr['price_promo']))
                            $data_arr['price_promo']+= $option['price'];
                    }
                    elseif ($option['price_prefix'] == '-') {
                        $data_arr['price']-= $option['price'];
                        if (isset($data_arr['oldprice']))
                            $data_arr['oldprice']-= $option['price'];
                        if (isset($data_arr['price_old']))
                            $data_arr['price_old']-= $option['price'];
                        if (isset($data_arr['price_promo']))
                            $data_arr['price_promo']-= $option['price'];
                    }
                    elseif ($option['price_prefix'] == 'u' && $option['price']) {
                        $option['price'] = $data_arr['price']/100*floatval($option['price']);
                        $data_arr['price']+= $option['price'];
                        if (isset($data_arr['oldprice']))
                            $data_arr['oldprice']+= $option['price'];
                        if (isset($data_arr['price_old']))
                            $data_arr['price_old']+= $option['price'];
                        if (isset($data_arr['price_promo']))
                            $data_arr['price_promo']+= $option['price'];
                    }
                    elseif ($option['price_prefix'] == 'd' && $option['price']) {
                        $option['price'] = $data_arr['price']/100*floatval($option['price']);
                        $data_arr['price']-= $option['price'];
                        if (isset($data_arr['oldprice']))
                            $data_arr['oldprice']-= $option['price'];
                        if (isset($data_arr['price_old']))
                            $data_arr['price_old']-= $option['price'];
                        if (isset($data_arr['price_promo']))
                            $data_arr['price_promo']-= $option['price'];
                    }
                    elseif ($option['price_prefix'] == '=') {
                        $data_arr['price'] = $option['price'];
                    }

                    $data_arr = $this->setOptionedWeight($data_arr, $option, $data['weight_unit']);
                    if (count($coptions)) {
                        $data_arr['url'].= '-'.$option['product_option_value_id'];
                    }
                    else {
                        $data_arr['url'].= '#'.$option['product_option_value_id'];
                    }
                    $offers_array[] = $data_arr;

                    $data_arr['id'] = $data['group_id']
                        .(isset($data['product_option_value_id']) ? str_pad($data['product_option_value_id'], 6, '0', STR_PAD_LEFT) : '')
                        .(isset($option['product_option_value_id']) ? str_pad($option['product_option_value_id'], 6, '0', STR_PAD_LEFT) : '');
                    $data_arr['price'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data_arr['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                    if (isset($data_arr['oldprice']))
                        $data_arr['oldprice'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data_arr['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                    if (isset($data_arr['price_old']))
                        $data_arr['price_old'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data_arr['price_old'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                    if (isset($data_arr['price_promo']))
                        $data_arr['price_promo'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data_arr['price_promo'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');

                    $data_arr = $this->setOptionedNames($product, $data_arr);
                    
                    $this->setOffer($data_arr);
                }
            }
            else {
                $data['id'] = $data['group_id']
                    .(isset($data['product_option_value_id']) ? str_pad($data['product_option_value_id'], 6, '0', STR_PAD_LEFT) : '');
                $data['price'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                if (isset($data['oldprice'])) {
                    $data['oldprice'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                if (isset($data['price_old'])) {
                    $data['price_old'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['price_old'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                if (isset($data['price_promo'])) {
                    $data['price_promo'] = number_format($this->shop_currency_value * $this->currency->convert($this->tax->calculate($data['price_promo'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                }
                
                $data = $this->setOptionedNames($product, $data);
                
                $this->setOffer($data);
            }
        }
        return true;
        //---- Цвета x Размеры для магазинов одежды ----
    }
    
    /**
    * Формируем name и model тэги с учетом опций
    */
    protected function setOptionedNames($product, $data_arr) {
        if (isset($data_arr['option_color'])) {
            $product['option_color'] = $data_arr['option_color'];
        }
        if (isset($data_arr['option_size'])) {
            $product['option_size'] = $data_arr['option_size'];
        }
        $data_arr['name'] = $this->getFieldValue($product, $this->config->get($this->CONFIG_PREFIX.'name_field'), 'name');
        if (strpos($this->config->get($this->CONFIG_PREFIX.'name_field'), '{option}') !== false) {
        }
        elseif ($this->optioned_name == 'short') {
            $data_arr['name'].= (isset($product['option_color']) ? ', '.$product['option_color'] : '');
        }
        elseif ($this->optioned_name == 'long') {
            $data_arr['name'].=  (isset($product['option_color']) ? ', '.$product['option_color'] : '')
                .(isset($product['option_size']) ? ', '.$product['option_size'] : '');
        }
        
        $data_arr['model'] = $this->getFieldValue($product, $this->config->get($this->CONFIG_PREFIX.'model_field'), 'model');
        if (strpos($this->config->get($this->CONFIG_PREFIX.'model_field'), '{option}') !== false) {
        }
        elseif ($this->optioned_name == 'short') {
            if (isset($data_arr['model']))
                $data_arr['model'].= (isset($product['option_color']) ? ', '.$product['option_color'] : '');
        }
        elseif ($this->optioned_name == 'long') {
            if (isset($data_arr['model']))
                $data_arr['model'].=  (isset($product['option_color']) ? ', '.$product['option_color'] : '')
                    .(isset($product['option_size']) ? ', '.$product['option_size'] : '');
        }
        return $data_arr;
    }
    
    protected function getOptionedImages($product, $product_images, $option, $images_by_options) {
        $ret = array();
        if (!$this->numpictures)
            return $product_images;
        if ($this->option_image_pro && isset($images_by_options[$option['product_option_value_id']])) {
            foreach ($images_by_options[$option['product_option_value_id']] as $image) {
                $ret[] = $this->prepareImage($image['image']);
            }
            return array_merge($ret, $product_images);
        }
        elseif ($option['image'] && $this->option_image) {
            $ret[] = $this->prepareImage($option['image']);
            return array_merge($ret, $product_images);
        }
        return $product_images;
    }
    
    /**
    * Меняет аттрибут веса товара в зависимости от опции
    */
    protected function setOptionedWeight($product, $option, $unit) {
        if (!isset($product['weight'])) {
            return $product;
        }
        if (isset($option['weight']) && isset($option['weight_prefix'])) {
            $weight = floatval($option['weight']);
            if ($option['weight_prefix'] == '-') {
                $weight = 0 - $weight;
            }
            if ($unit == 'кг' || $unit == 'kg') {
                $product['weight']+= $weight;
            }
            elseif ($unit == 'г' || $unit == 'g' || $unit == 'гр' || $unit == 'gr') {
                $product['weight']+= $weight/1000;
            }
            else {
                foreach ($product['param'] as $i=>$param) {
                    if (isset($param['id']) && ($param['id'] == 'WEIGHT')) {
                        $product['param'][$i]['value']+= $weight;
                    }
                }
            }
        }
        return $product;
    }
    
    /**
     * Подготовка данных о фотографии
     */
    protected function prepareImage($image) {
        if ((strpos($image, 'http://') === 0) || (strpos($image, 'https://') === 0)) {
            return $image;
        }
        if (is_file(DIR_IMAGE . $image)) {
            try {
                list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $image);
            } catch(Exception $e) {
                return false;
            }
            if ($width_orig < $this->image_width || $height_orig < $this->image_height || !$this->ORIGINAL_IMAGES) {
                try {
                    $resized = $this->model_tool_image->resize($image, $this->image_width, $this->image_height);
                    return $resized;
                } catch(Exception $e) {
                    return false;
                }
            } else {
                $parts = explode('/', $image);
                $new_url = implode('/', array_map('rawurlencode', $parts));            
                return HTTP_IMAGE . $new_url;
            }
        }
        return false;
    }
    
    /**
     * Методы формирования YML
     */

    /**
     * Формирование массива для элемента shop описывающего магазин
     *
     * @param string $name - Название элемента
     * @param string $value - Значение элемента
     */
    protected function setShop($name, $value) {
        $allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email', 'enable_auto_discounts');
        if (in_array($name, $allowed)) {
            $this->shop[$name] = $this->prepareField($value);
        }
    }

    /**
     * Валюты
     *
     * @param string $id - код валюты (RUR, RUB, USD, BYR, BYN, KZT, EUR, UAH)
     * @param float|string $rate - курс этой валюты к валюте, взятой за единицу.
     *    Параметр rate может иметь так же следующие значения:
     *        CBRF - курс по Центральному банку РФ.
     *        NBU - курс по Национальному банку Украины.
     *        NBK - курс по Национальному банку Казахстана.
     *        СВ - курс по банку той страны, к которой относится интернет-магазин
     *         по Своему региону, указанному в Партнерском интерфейсе Яндекс.Маркета.
     * @param float $plus - используется только в случае rate = CBRF, NBU, NBK или СВ
     *        и означает на сколько увеличить курс в процентах от курса выбранного банка
     * @return bool
     */
    protected function setCurrency($id, $rate = 'CBRF', $plus = 0) {
        $allow_id = array('RUR', 'RUB', 'USD', 'BYR', 'BYN', 'KZT', 'EUR', 'UAH');
        if (!in_array($id, $allow_id)) {
            return false;
        }
        $allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
        if (in_array($rate, $allow_rate)) {
            $plus = str_replace(',', '.', $plus);
            if (is_numeric($plus) && $plus > 0) {
                $this->currencies[] = array(
                    'id'=>$this->prepareField(strtoupper($id)),
                    'rate'=>$rate,
                    'plus'=>(float)$plus
                );
            } else {
                $this->currencies[] = array(
                    'id'=>$this->prepareField(strtoupper($id)),
                    'rate'=>$rate
                );
            }
        } else {
            $rate = str_replace(',', '.', $rate);
            if (!(is_numeric($rate) && $rate > 0)) {
                return false;
            }
            $this->currencies[] = array(
                'id'=>$this->prepareField(strtoupper($id)),
                'rate'=>(float)$rate
            );
        }

        return true;
    }

    /**
     * Товарные предложения
     *
     * @param array $data - массив параметров товарного предложения
     */
    protected function setOffer($data) {
        if (isset($data['picture']) && is_array($data['picture'])) {
            $data['picture'] = array_filter($data['picture']);
            if (count($data['picture']) == 0) {
                unset($data['picture']);
            }
        }
        if ($this->config->get($this->CONFIG_PREFIX.'image_mandatory') && (!isset($data['picture']) || count($data['picture']) < 1)) {
            return;
        }
        if (isset($data['noexport']) && $data['noexport']) {
            return;
        }
        if ($this->config->get($this->CONFIG_PREFIX.'changeprice')) {
            $data['price']*= floatval($this->config->get($this->CONFIG_PREFIX.'changeprice'));
            if (isset($data['price_promo'])) {
                $data['price_promo']*= floatval($this->config->get($this->CONFIG_PREFIX.'changeprice'));
            }
        }
        if ($data['price'] <= floatval($this->config->get($this->CONFIG_PREFIX.'pricefrom'))) {
            return;
        }
        if ($this->config->get($this->CONFIG_PREFIX.'priceto') != '' && $data['price'] >= floatval($this->config->get($this->CONFIG_PREFIX.'priceto'))) {
            return;
        }
        
        if ($this->config->get($this->CONFIG_PREFIX.'shop_sku')) {
            $data['shop-sku'] = $data['id'];
        }

        //++++ Чтобы сопутствующие товары и подарки нормально работали ++++
        if (isset($data['group_id']) && $data['group_id'] != $data['id']) {
            if (!isset($this->product_optioned_ids[$data['group_id']] )) {
                $this->product_optioned_ids[$data['group_id']] = array();
            }
            $this->product_optioned_ids[$data['group_id']][] = $data['id'];
            $this->product_ids[] = $data['group_id'];
        }
        else {
            $this->product_ids[] = $data['id'];
        }
        //---- Чтобы сопутствующие товары и подарки нормально работали ----
        
        $offer = array();

        $attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param', 'delivery-option', 'group_id', 'accessory', 'age', 'condition', 'opt_prices', 'selling_type', 'tn-ved-codes');
        $attributes = array_intersect_key($data, array_flip($attributes));

        foreach ($attributes as $key => $value) {
            switch ($key)
            {
                case 'id':
                    $offer['id'] = $value;
                    break;
                case 'bid':
                case 'cbid':
                case 'group_id':
                    $value = (int)$value;
                    if ($value > 0) {
                        $offer[$key] = $value;
                    }
                    break;
                    
                case 'type':
                    if (in_array($value, array('vendor.model', 'book', 'audiobook', 'artist.title', 'tour', 'ticket', 'event-ticket'))) {
                        $offer['type'] = $value;
                    }
                    break;

                case 'available':
                    $offer['available'] = ($value ? 'true' : 'false');
                    break;

                case 'param':
                case 'delivery-option':
                case 'condition':
                    if (is_array($value)) {
                        $offer[$key] = $value;
                    }
                    break;

                case 'age':
                case 'tn-ved-codes':
                    $offer[$key] = $value;
                    break;

                case 'opt_prices':
                    if (is_array($value) && count($value) > 0) {
                        $offer['selling_type'] = 'u';
                        $offer['prices'] = $value;
                    }
                    break;
                    
                default:
                    break;
            }
        }

        $type = isset($offer['type']) ? $offer['type'] : '';

        $allowed_tags = array('url'=>0, 'buyurl'=>0, 'price'=>1, 'oldprice'=>0, 'price_old'=>0, 'price_promo'=>0, 'purchase_price'=>0, 'wprice'=>0, 'currencyId'=>1, 'xCategory'=>0, 'categoryId'=>1, 'picture'=>0, 'store'=>0, 'pickup'=>0, 'delivery'=>0, 'deliveryIncluded'=>0, 'local_delivery_cost'=>0, 'orderingTime'=>0, 'count'=>0, 'quantity'=>0, 'stock_quantity'=>0, 'quantity_in_stock'=>0, 'min-quantity'=>0, 'step-quantity'=>0, 'minimum_order_quantity'=>0);

        switch ($type) {
            case 'vendor.model':
                $allowed_tags = array_merge($allowed_tags, array('typePrefix'=>0, 'vendor'=>1, 'vendorCode'=>0, 'model'=>1, 'provider'=>0, 'tarifplan'=>0));
                break;

            case 'book':
                $allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'binding'=>0, 'page_extent'=>0, 'table_of_contents'=>0));
                break;

            case 'audiobook':
                $allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'table_of_contents'=>0, 'performed_by'=>0, 'performance_type'=>0, 'storage'=>0, 'format'=>0, 'recording_length'=>0));
                break;

            case 'artist.title':
                $allowed_tags = array_merge($allowed_tags, array('artist'=>0, 'title'=>1, 'year'=>0, 'media'=>0, 'starring'=>0, 'director'=>0, 'originalName'=>0, 'country'=>0));
                break;

            case 'tour':
                $allowed_tags = array_merge($allowed_tags, array('worldRegion'=>0, 'country'=>0, 'region'=>0, 'days'=>1, 'dataTour'=>0, 'name'=>1, 'hotel_stars'=>0, 'room'=>0, 'meal'=>0, 'included'=>1, 'transport'=>1, 'price_min'=>0, 'price_max'=>0, 'options'=>0));
                break;

            case 'event-ticket':
                $allowed_tags = array_merge($allowed_tags, array('name'=>1, 'place'=>1, 'hall'=>0, 'hall_part'=>0, 'date'=>1, 'is_premiere'=>0, 'is_kids'=>0));
                break;

            default:
                $allowed_tags = array_merge($allowed_tags, array('name'=>1, 'vendor'=>0, 'vendorCode'=>0));
                if ($this->config->get($this->CONFIG_PREFIX.'model_field')) {
                    $allowed_tags['model'] = 0;
                }
                break;
        }

        $allowed_tags = array_merge($allowed_tags, array('aliases'=>0, 'additional'=>0, 'description'=>0, 'sales_notes'=>0, 'promo'=>0, 'manufacturer_warranty'=>0, 'country_of_origin'=>0, 'weight'=>0, 'dimensions'=>0, 'length'=>0, 'width'=>0, 'height'=>0, 'downloadable'=>0, 'adult'=>0, 'age'=>0, 'barcode'=>0, 'keywords'=>0, 'shop-sku'=>0, 'market-sku'=>0, 'manufacturer'=>0, 'rec'=>0));

        $required_tags = array_filter($allowed_tags);

        if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
            return;
        }

        $data = array_intersect_key($data, $allowed_tags);

        $allowed_tags = array_intersect_key($allowed_tags, $data);

        // Стандарт XML учитывает порядок следования элементов,
        // поэтому важно соблюдать его в соответствии с порядком описанным в DTD
        $offer['data'] = array();
        foreach ($allowed_tags as $key => $value) {
            if (!isset($data[$key]))
                continue;
            if (is_array($data[$key])) {
                foreach ($data[$key] as $i => $val) {
                    $offer['data'][$key][$i] = $this->prepareField($val);
                }
            }
            else {
                $offer['data'][$key] = $this->prepareField($data[$key]);
            }
        }

        $this->offers[] = $offer;
    }

    /**
     * Формирование YML файла
     * @return string
     */
    protected function outYml() {
        if (isset($this->request->get['language'])) {
            $query = $this->db->query("SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code='".$this->db->escape($this->request->get['language'])."'"); 
            if ($query->num_rows) {
                $this->session->data['language'] = $this->request->get['language'];
                $this->config->set('config_language_id', $query->row['language_id']);
                $this->config->set('config_language', $this->request->get['language']);
            }
        }
        echo $this->getYmlHeader();
        $page = 0;
        $products_yml = $this->getYmlChunk($page);
        while ($products_yml !== 'OUT') {
            echo $products_yml;
            unset($products_yml);
            usleep($this->SLEEP);
            $page++;
            $products_yml = $this->getYmlChunk($page);
        }
        echo $this->getYmlFooter();
        return true;
    }

    /**
     * Вывод YML в файл
     * @param $fp дескриптор файла
     */
    protected function putYml($fp) {
        $options = getopt(array('l::'), array('language::'));
        $language = false;
        if (isset($options['l'])) {
            $language = $options['l'];
        }
        elseif (isset($options['language'])) {
            $language = $options['language'];
        }
        if ($language) {
            $query = $this->db->query("SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code='".$this->db->escape($this->request->get['language'])."'"); 
            if ($query->num_rows) {
                $this->session->data['language'] = $this->request->get['language'];
                $this->config->set('config_language_id', $query->row['language_id']);
                $this->config->set('config_language', $this->request->get['language']);
            }
        }
        fwrite($fp, $this->getYmlHeader());
        unset($this->categories);
        $page = 0;
        $products_yml = $this->getYmlChunk($page);
        while ($products_yml !== 'OUT') {
            fwrite($fp, $products_yml);
            unset($products_yml);
            usleep($this->SLEEP);
            $page++;
            $products_yml = $this->getYmlChunk($page);
        }
        fwrite($fp, $this->getYmlFooter());
        return true;
    }

    /**
     * Возвращает список офферов (из одного product_id или всех опций этого товара)
     */
    protected function getPromoOfferId($product_ids) {
        $ret = '';
        foreach ($product_ids as $product_id) {
            if (isset($this->product_optioned_ids[$product_id])) {
                foreach ($this->product_optioned_ids[$product_id] as $prod_id) {
                    $ret.= '<product offer-id="'.$prod_id.'" />' . $this->eol;
                }
            }
            elseif (in_array($product_id, $this->product_ids)) {
                $ret.= '<product offer-id="'.$product_id.'" />' . $this->eol;
            }
        }
        return $ret;
    }

    /**
     * Подвал YML-файла, в т.ч. вместе с gifts и promos
     */
    protected function getYmlFooter() {
        $yml_footer = '</offers>' . $this->eol;
        
        $ret = '';
        $retg = '';
        
        if ($this->config->get($this->CONFIG_PREFIX.'gift_promo_gift') != '') {
            $promo_name = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_name'));
            $promo_gift = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_gift'));
            $promo_field = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_field'));
            $promo_val = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_val'));
            $promo_img = unserialize($this->config->get($this->CONFIG_PREFIX.'gift_promo_img'));
            foreach($promo_gift as $gift_id=>$gift_name) {
                $retg.= '<gift id="gift'.$gift_id.'">'
                    .'<name>'.$this->prepareField($gift_name).'</name>'
                    .(isset($promo_img[$gift_id]) && $promo_img[$gift_id] ? '<picture>'.$this->prepareField($promo_img[$gift_id]).'</picture>' : '')
                    .'</gift>' . $this->eol;
                
                $product_ids = array_unique(array_intersect($this->product_ids, $this->model_extension_feed_yandex_yml->getFilteredProducts($promo_field[$gift_id], $promo_val[$gift_id])));
                if (count($product_ids)) {
                    $ret.= '<promo id="gift'.$gift_id.'" type="gift with purchase">' . $this->eol
                        .(isset($promo_url[$gift_id]) ? '<url>'.$this->prepareField($promo_url[$gift_id]).'</url>' . $this->eol : '')
                        .(isset($promo_name[$gift_id]) && $promo_name[$gift_id] ? '<description>'.$this->prepareField($promo_name[$gift_id]).'</description>' . $this->eol : '')
                        .'<purchase>' . $this->eol;
                    $ret.= $this->getPromoOfferId($product_ids);
                    $ret.= '</purchase>' . $this->eol
                        .'<promo-gifts><promo-gift gift-id="gift'.$gift_id.'" /></promo-gifts>' . $this->eol
                        .'</promo>'. $this->eol;
                }
            }
        }
        if ($this->config->get($this->CONFIG_PREFIX.'custom_gifts')) {
            $retg.= trim(html_entity_decode($this->config->get($this->CONFIG_PREFIX.'custom_gifts'), ENT_QUOTES, 'UTF-8'));
        }
        if ($retg) {
            $yml_footer.= '<gifts>' . $this->eol . $retg . $this->eol . '</gifts>' . $this->eol;
        }
        /*
        if ($this->config->get($this->CONFIG_PREFIX.'flash_discount')) {
            $specials = $this->model_export_yandex_yml->getProductSpecials($this->customer_group);
            if (count($specials) > 0) {
                foreach ($specials as $key=>$spec) {
                    $ret.= '<promo id="'.$key.'" type="flash discount">'. $this->eol
                        .'<start-date>'.$spec['date_start'].'</start-date>'
                        .'<end-date>'.$spec['date_end'].'</end-date>'. $this->eol
                        .'<purchase>';
                    foreach ($spec['products'] as $product_id=>$product) {
                        $dprice = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '');
                        $ret.= '<product offer-id="'.$product_id.'">'
                            .'<discount-price currency="'.$this->offers_currency.'">'.$dprice.'</discount-price>'
                            .'</product>'. $this->eol;
                    }
                    $ret.= '</purchase>'. $this->eol
                        .'</promo>'. $this->eol;
                }
            }
        }
        */
        
        if ($this->config->get($this->CONFIG_PREFIX.'coupons')) {
            $coupon_urls = unserialize($this->config->get($this->CONFIG_PREFIX.'coupon_urls'));
            $category_ids = array();
            foreach ($this->categories as $cat) {
                $category_ids[] = $cat['id'];
            }
            $coupons = $this->model_export_yandex_yml->getCoupons(explode(',', $this->config->get($this->CONFIG_PREFIX.'coupons')));
            if (count($coupons) > 0) {
                foreach ($coupons as $key=>$coupon) {
                    $ret.= '<promo id="code'.$coupon['coupon_id'].'" type="promo code">'. $this->eol
                        .($coupon['date_start'] != '0000-00-00' ? '<start-date>'.$coupon['date_start'].'</start-date>' : '')
                        .($coupon['date_end'] != '0000-00-00' ? '<end-date>'.$coupon['date_end'].'</end-date>' : ''). $this->eol
                        .'<description>'.$this->prepareField($coupon['name']).'</description>'. $this->eol
                        .(isset($coupon_urls[$coupon['coupon_id']]) ? '<url>'.$this->prepareField($coupon_urls[$coupon['coupon_id']]).'</url>'. $this->eol : '')
                        .'<promo-code>'.$this->prepareField($coupon['code']).'</promo-code>'. $this->eol;
                    if ($coupon['type'] == 'F') {
                        $ret.= '<discount unit="currency" currency="'.$this->offers_currency.'">'.number_format($this->currency->convert($coupon['discount'], $this->shop_currency, $this->offers_currency), 0, '.', '').'</discount>'. $this->eol;
                    }
                    else {
                        $ret.= '<discount unit="percent">'.number_format($coupon['discount'], 0, '.', '').'</discount>'. $this->eol;
                    }
                    $ret.= '<purchase>'. $this->eol;
                    
                    if (!count($coupon['product_ids']) && !count($coupon['category_ids'])) {
                        foreach($category_ids as $category_id) {
                            $ret.= '<product category-id="'.$category_id.'"></product>'. $this->eol;
                        }
                    }
                    else {
                        $ret.= $this->getPromoOfferId($coupon['product_ids']);
                        foreach ($coupon['category_ids'] as $category_id) {
                            if (in_array($category_id, $category_ids)) {
                                $ret.= '<product category-id="'.$category_id.'"></product>'. $this->eol;
                            }
                        }
                    }
                    $ret.= '</purchase>'. $this->eol
                        .'</promo>'. $this->eol;
                }
            }
        }
        
        if ($this->config->get($this->CONFIG_PREFIX.'custom_promo')) {
            $ret.= html_entity_decode($this->config->get($this->CONFIG_PREFIX.'custom_promo'), ENT_QUOTES, 'UTF-8');
        }
        if ($ret) {
            $yml_footer.= '<promos>' . $this->eol . $ret . $this->eol . '</promos>' . $this->eol;
        }
        $yml_footer.= '</shop></yml_catalog>';
        return $yml_footer;
    }

    /**
     * Фрмирование элемента
     *
     * @param array $attributes
     * @param string $element_name
     * @param string $element_value
     * @return string
     */
    protected function getElement($attributes, $element_name, $element_value = '') {
        $retval = '<' . $element_name . ' ';
        foreach ($attributes as $key => $value) {
            $retval .= $key . '="' . $value . '" ';
        }
        $retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
        $retval .= $this->eol;

        return $retval;
    }

    /**
     * Преобразование массива в теги
     *
     * @param array $tags
     * @return string
     */
    protected function array2Tag($tags) {
        $retval = '';
        foreach ($tags as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $val) {
                    $retval .= '<' . $key . '>' . $val . '</' . $key . '>' . $this->eol;
                }
            }
            else {
                $retval .= '<' . $key . '>' . $value . '</' . $key . '>' . $this->eol;
            }
        }

        return $retval;
    }

    /**
     * Преобразование массива в теги параметров
     *
     * @param array $params
     * @return string
     */
    protected function array2Param($params) {
        $retval = '';
        foreach ($params as $param) {
            $retval .= '<param name="' . $this->prepareField($param['name']);
            if (isset($param['unit'])) {
                $retval .= '" unit="' . $this->prepareField($param['unit']);
            }
            $retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;
        }

        return $retval;
    }

    /**
     * Преобразование массива в тег prices
     *
     * @param array $params
     * @return string
     */
    protected function array2Prices($prices) {
        $retval = '';
        foreach ($prices as $price) {
            $retval .= '<price><value>'.number_format($this->currency->convert($this->tax->calculate($price['price'], $price['tax_class_id'], $this->config->get('config_tax')), $this->shop_currency, $this->offers_currency), $this->decimal_place, '.', '').'</value>'
                .'<quantity>'.$price['quantity'].'</quantity></price>' . $this->eol;
        }
        if ($retval) {
            $retval = '<prices>' . $this->eol . $retval. $this->eol . '</prices>';
        }

        return $retval;
    }

    /**
     * Преобразование массива в тег delivery-option
     *
     * @param array $delivery_option ('cost'=>Цена, 'days'=>Срок в днях, 'before'=>Час перескока)
     * @return string
     */
    protected function array2Delivery($delivery_option) {
        $retval = '';
        if (is_array($delivery_option)) {
            $retval = '<delivery-options><option cost="'.$delivery_option['cost'].'" days="'.$delivery_option['days'].'"'
                .(isset($delivery_option['before']) ? ' order-before="'.$delivery_option['before'].'"' : '').' /></delivery-options>' . $this->eol;
        }
        return $retval;
    }
    
    /**
     * Вывод тэга condition
     */
    protected function data2Condition($type, $reason) {
        $retval = '<condition type="'.$type.'"><reason>'.$this->prepareField($reason).'</reason></condition>'. $this->eol;
        return $retval;
    }

    /**
     * Подготовка текстового поля в соответствии с требованиями Яндекса
     * Запрещаем любые html-тэги, стандарт XML не допускает использования в текстовых данных
     * непечатаемых символов с ASCII-кодами в диапазоне значений от 0 до 31 (за исключением
     * символов с кодами 9, 10, 13 - табуляция, перевод строки, возврат каретки). Также этот
     * стандарт требует обязательной замены некоторых символов на их символьные примитивы.
     * @param string $text
     * @return string
     */
    protected function prepareField($field) {
        $field = htmlspecialchars_decode($field);
        //Убираем не UTF-8 символы
        //@todo использовать github.com/neitanod/forceutf8 для их конвертации
        $field = mb_convert_encoding($field, 'UTF-8', 'UTF-8');
        if (strpos($field, '<![CDATA[') === 0) {
            return trim($field);
        }
        $field = strip_tags($field);
        $from = array('&nbsp;', '&', '"', '>', '<', '\'');
        $to = array(' ', '&amp;', '&quot;', '&gt;', '&lt;', '&apos;');
        $field = str_replace($from, $to, $field);
        /**
        if ($this->from_charset != 'windows-1251') {
            $field = iconv($this->from_charset, 'windows-1251//IGNORE', $field);
        }
        **/
        $field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

        return trim($field);
    }

    protected function getPath($category_id, $current_path = '') {
        if (isset($this->categories[$category_id])) {
            if (!$current_path) {
                $new_path = $this->categories[$category_id]['id'];
            } else {
                $new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
            }    

            if (isset($this->categories[$category_id]['parentId'])) {
                return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
            } else {
                return $new_path;
            }
        }
    }

    /**
     * Определение единиц измерения по содержимому
     *
     * @param array $attr array('name'=>'Вес', 'value'=>'100кг')
     * @return array array('name'=>'Вес', 'unit'=>'кг', 'value'=>'100')
     */
    protected function detectUnits($attr) {
        //$matches = array();
        $attr['name'] = trim(strip_tags($attr['name']));
        $attr['value'] = trim(strip_tags($attr['value']));
        if (preg_match('/\(([^\)]+)\)$/mi', $attr['name'], $matches)) {
            $attr['name'] = trim(str_replace('('.$matches[1].')', '', $attr['name']));
            $attr['unit'] = trim($matches[1]);
        }
        return $attr;
    }
    
    /**
    * Опредение цены доставки в зависимости от цены товара
    *
    * @param str $price - цена в виде: 0:300|5000:0 (стоимость товара от 0, доставка 300; стоимость товара от 5000, доставка 0)
    * @param float $total - цена товара
    */
    protected function getDeliveryPrice($price, $total=0) {
        if (strpos($price, ':') === false)
            return floatval($price);
        $vars = explode('|', $price);
        $ret = floatval($price);
        foreach ($vars as $var) {
            $tp = explode(':', $var);
            if (floatval($total) < floatval($tp[0])) break;
            $ret = floatval($tp[1]);
        }
        return $ret;
    }
    
    /**
     * Брать группу покупателей из поддомена. Привязка поддомен-группа берется из модуля "ProgRoman - CityManager+GeoIP Pro"
     */
    protected function getCustomerGroup() {
        if ($this->progroman_city_manager) {
            $this->load->model('progroman/city_manager');
            $redirects = $this->model_progroman_city_manager->getRedirects();
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $HTTP_SERVER = $this->config->get('config_ssl');
            } else {
                $HTTP_SERVER = $this->config->get('config_url');
            }
            $found = false;
            foreach ($redirects as $red) {
                if (strpos($red['url'], $HTTP_SERVER) !== false) {
                    $found = $red;
                    break;
                }
            }
            if ($found) {
                $groups = $this->model_progroman_city_manager->getCustomersGroups(array($found['fias_id']));
                if (isset($groups[0])) {
                    return $groups[0]['customer_group_id'];
                }
            }
        }
        return $this->config->get($this->CONFIG_PREFIX.'groupprice');
    }
}
