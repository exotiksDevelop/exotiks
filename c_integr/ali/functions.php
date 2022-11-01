<?php

/**
 * Али плохо подбирает категорию из сложного имени.
 * Не всегда нужная категория идет на первой позиции.
 * Для этого пока разбивать на простые словосочетания.
 * deprecated : use getConstAliCategoryIdByName func
 * @param string $name
 * @return string
 */
function checkName(string $name)
{
    switch (true) {
        case stripos($name, 'Термоманометр'):
            return $name = 'Термо манометр';
        default:
            return $name;
    }
}

function get_ali_category_obj($product_name)
{
    $product_name = checkName($product_name);
    $product_name = str_replace(' ', '+', $product_name);
    $req = new AliexpressPostproductRedefiningCategoryforecastRequest;
//    $req->setSubject("Moomin+Кружка+эмалированная+Moomin+Retro+Фрекен+Снорк");
    $req->setSubject($product_name);
    $req->setLocale("ru");
    $req->setForecastMode("1");
    $req->setIsFilterByPermission("N");
    return $req;
}


function getOrigin($country_name)
{
    switch ($country_name) {
        case 'Италия':
            return ['value' => 'IT(Origin)', 'value_id' => '9441878616'];
        case 'Россия':
            return ['value' => 'RU(Origin)', 'value_id' => '9442210754'];
        case stripos($country_name, 'Китай'):
        case 'Китай':
        default:
            return ['value' => 'CN(Origin)', 'value_id' => '9441741844'];
    }
}

function getProductUnitsTypeId($product_type)
{
    switch ($product_type) {
        case "шт.":
            $title = "piece/pieces";
            return '100000015';
        default:
            return '100000015';
    }
}

function getColor($color_name = 'Белый')
{
    switch ($color_name) {
        case 'Белый':
            return ['alias' => 'White', 'value' => '29'];
        case 'Желтый':
            return ['alias' => 'Yellow', 'value' => '366'];
        case 'Синий':
            return ['alias' => 'Blue', 'value' => '173'];
        case 'Красный':
            return ['alias' => 'Red', 'value' => '10'];
        case 'Зеленый':
            return ['alias' => 'Green', 'value' => '175'];
        case 'Черный':
            return ['alias' => 'Black', 'value' => '193'];
        case 'Розовый':
            return ['alias' => 'Pink', 'value' => '1052'];
        case 'Мультиколор':
//            return ['alias' => 'Multicolor', 'value' => '200003699'];
        default:
            return ['alias' => 'Multi', 'value' => '200003699'];
    }
}

function getCapacityFromName($product_name)
{
    $int = filter_var($product_name, FILTER_SANITIZE_NUMBER_INT);
    switch (true) {
        case $int < 50:
            return [
                "alias" => "$int",
                "value" => "200007969"
            ];
        case $int < 1000:
            return [
                "alias" => "$int",
                "value" => "200007973"
            ];
        case ($int > 51 and $int < 100):
            return [
                "alias" => "$int",
                "value" => "200007970"
            ];
        case ($int > 101 and $int < 200):
            return [
                "alias" => "$int",
                "value" => "200007971"
            ];
        case ($int > 201 and $int < 300):
            return [
                "alias" => "$int",
                "value" => "200007972"
            ];
        case ($int > 301 and $int < 400):
            return [
                "alias" => "$int",
                "value" => "200007962"
            ];
        case ($int > 401 and $int < 500):
            return [
                "alias" => "$int",
                "value" => "200007963"
            ];
        case ($int > 501 and $int < 600):
            return [
                "alias" => "$int",
                "value" => "200007964"
            ];
        case ($int > 601 and $int < 700):
            return [
                "alias" => "$int",
                "value" => "200007965"
            ];
        case ($int > 701 and $int < 800):
            return [
                "alias" => "$int",
                "value" => "200007966"
            ];
        default:
            return [];
    }
}

function getBrandName($name)
{
    switch ($name) {
        case 'ESNone':
            return [
                'value' => 'ESNone',
                'value_id' => '203062806'
            ];
        case 'MUURLA':
            return [
                'value' => 'MUURLA',
                'value_id' => '18529003'
            ];
        case 'Arabia':
            return [
                'value' => 'ARABIA',
                'value_id' => '201499654'
            ];
        case 'GREENGATE':
            return [
                'value' => 'GREENGATE',
                'value_id' => '1953488377'
            ];
        case 'Iittala':
            return [
                'value' => 'Iittala',
                'value_id' => '201471742'
            ];
        case 'Mikebon':
            return [
                'value' => 'Mikebon',
                'value_id' => '203969126'
            ];
        case 'AQUA':
            return [
                'value' => 'AQUATIM',
                'value_id' => '203982229'
            ];
        default:
            return [
                'value' => 'None',
                'value_id' => '201512802'
            ];
    }
}

function getMaterial($name)
{
    switch ($name) {
        case 'фарфор':
            return [
                "aliexpress_common_attribute_name_id" => "10", // mags
                'aliexpress_common_attribute_value' => 'Ceramic',
                'aliexpress_common_attribute_value_id' => '747'
            ];
        case 'стекло':
            return [
                "aliexpress_common_attribute_name_id" => "10",
                'aliexpress_common_attribute_value' => 'Glass',
                'aliexpress_common_attribute_value_id' => '346'
            ];
        case 'пластик':
            return [
                "aliexpress_common_attribute_name_id" => "10",
                'aliexpress_common_attribute_value' => 'Plastic',
                'aliexpress_common_attribute_value_id' => '124'
            ];
        case 'силикон':
            return [
                "aliexpress_common_attribute_name_id" => "10",
                'aliexpress_common_attribute_value' => 'Silicone',
                'aliexpress_common_attribute_value_id' => '350016'
            ];
        case 'дерево':
            return [
                "aliexpress_common_attribute_name_id" => "10",
                'aliexpress_common_attribute_value' => 'Wood',
                'aliexpress_common_attribute_value_id' => '350383'
            ];
        case 'сталь':
        case 'металл':
            return [
                "aliexpress_common_attribute_name_id" => "10",
                'aliexpress_common_attribute_value' => 'Metal',
                'aliexpress_common_attribute_value_id' => '398'
            ];
        case 'кожа':
            return [
                "aliexpress_common_attribute_name_id" => "10",
                'aliexpress_common_attribute_value' => 'Leather',
                'aliexpress_common_attribute_value_id' => '100006966'
            ];
        case 'нейлон':
            return [
                "aliexpress_common_attribute_name_id" => "20205", // bags
                "aliexpress_common_attribute_value" => "Nylon",
                "aliexpress_common_attribute_value_id" => "63"
            ];
        case 'полиэстр':
            return [
                "aliexpress_common_attribute_name_id" => "20205",
                "aliexpress_common_attribute_value" => "Polyester",
                "aliexpress_common_attribute_value_id" => "48"
            ];
        case 'полипропилен':
            return [
                "aliexpress_common_attribute_name_id" => "20205",
                "aliexpress_common_attribute_value" => "PP",
                "aliexpress_common_attribute_value_id" => "438"
            ];
        case 'хлопок':
            return [
                "aliexpress_common_attribute_name_id" => "20205",
                "aliexpress_common_attribute_value" => "Cotton Fabric",
                "aliexpress_common_attribute_value_id" => "365211"
            ];
        case 'бумага':
            return [
                "aliexpress_common_attribute_name_id" => "20205",
                "aliexpress_common_attribute_value" => "Paper",
                "aliexpress_common_attribute_value_id" => "350333"
            ];
        case 'микрофибра':
            return [
                "aliexpress_common_attribute_name_id" => "20205",
                "aliexpress_common_attribute_value" => "Microfiber",
                "aliexpress_common_attribute_value_id" => "365213"
            ];
        case 'шелк':
            return [
                "aliexpress_common_attribute_name_id" => "20205",
                "aliexpress_common_attribute_value" => "Silk",
                "aliexpress_common_attribute_value_id" => "365230"
            ];
        default:
            return [];
    }
}

/**
 *  Request to Aliexpress to create or update products
 *
 * @param $product
 * @param $categoryId
 * @param null $ali_product_id
 * @param string[] $image
 * @return AliexpressSolutionProductEditRequest|AliexpressSolutionProductPostRequest
 */
function createAliRequest($product, $categoryId, $ali_product_id = null, $image = ['https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/480px-No_image_available.svg.png'])
{
    global $ms;
    $ms_product = json_decode($product['ms_product_full_data']);

    $brand_name = '';
    if ($ms_product->attributes) {
        foreach ($ms_product->attributes as $attribute) {
            switch ($attribute->name) {
                case "Длина, см":
                    $length = $attribute->value;
                    break;
                case "Ширина, см":
                    $height = $attribute->value;
                    break;
                case "Высота, см":
                    $width = $attribute->value;
                    break;
                case "Производитель":
                    $brand_name = $attribute->value;
                    break;
                case "ССЫЛКА НА ФОТО":
                    $image_from_attr = $attribute->value;
                    break;
                case "Страна":
                    $country_name = $attribute->value;
                    break;
                case "Материал":
                    $material = $attribute->value;
                    break;
            }

//        if ($attribute->name == "Длина, см") $length = $attribute->value;
//        if ($attribute->name == "Ширина, см") $height = $attribute->value;
//        if ($attribute->name == "Высота, см") $width = $attribute->value;
//        if ($attribute->name == "Сайт.Изображения") $image_from_attr = $attribute->value;
//        if ($attribute->name == "Цвет 1") $color_val = $attribute->value;
//        if ($attribute->name == "Цвет 2") $multi_color = true;
//        if ($attribute->name == "Производитель") $brand_name = $attribute->value;
//        if ($attribute->name == "Страна") $country_name = $attribute->value;
//        if ($attribute->name == "Ali.Наименование (Eng)") $eng_title = $attribute->value;
//        if ($attribute->name == "Материал") $material = $attribute->value;
        }
    }
    $images = empty($image_from_attr) ? $image : [$image_from_attr];

    $length = empty($length) ? 0 : (int)$length;
    $height = empty($height) ? 0 : (int)$height;
    $width = empty($width) ? 0 : (int)$width;
    if (empty($country_name))
        if ($ms_product->country->meta->href === 'https://online.moysklad.ru/api/remap/1.2/entity/country/9df7c2c3-7782-4c5c-a8ed-1102af611608')
            $country_name = 'Россия';

    if ($ali_product_id) {
        $req = new AliexpressSolutionProductEditRequest;
        $req->product_id = $ali_product_id;
    } else {
        $req = new AliexpressSolutionProductPostRequest;
    }

    $product_request = new PostProductRequestDto;
//    $title = mb_strlen((string)$ms_product->name) > 120 ? substr((string)$ms_product->name, 0, 121) : (string)$ms_product->name;
    $product_request->subject = mb_substr((string)$ms_product->name, 0, 127, 'UTF-8');
    $product_request->description = !empty($ms_product->description) ? (string)$ms_product->description : "$ms_product->name, \n {$brand_name}";
    $product_request->language = "ru_RU";
    $product_request->product_unit = "100000015";
    if (!$ali_product_id) {
        $product_request->aliexpress_category_id = $categoryId;
        $product_request->category_id = $categoryId;
    }
    $product_request->main_image_urls_list = $images;
    $attribute_list1 = new AttributeDto;
    if (!empty($brand_name))
        $brand_name = getBrandName($brand_name);
    else {
        if (stripos($product->name, 'AQUA') !== false)
            $brand_name = getBrandName('AQUA');
    }
    if (empty($brand_name['value'])) {
        $brand_name = [
            'value' => 'Other',
            'value_id' => 'Other'];
    }
    $attribute_list1->aliexpress_attribute_name_id = "2";
    $attribute_list1->attribute_name = $brand_name['value'];
    $attribute_list1->attribute_value = $brand_name['value_id'];

    $attribute_list2 = new AttributeDto;
    $attribute_list2->aliexpress_attribute_name_id = "3";

    if (empty($ms_product->article)) {
        global $logger;
        $logger->debug('Empty article', ['$ms_product' => $ms_product->id]);
        return;
    }

    $article = iconv(mb_detect_encoding((string)$ms_product->article, mb_detect_order(), true), "UTF-8", (string)$product["article"]);
    $attribute_list2->attribute_value = $article;
    $my_attribute_list = [$attribute_list1, $attribute_list2];

    if (empty($country_name)) {
        $country_name = '';
    }
    $attribute_list3 = new AttributeDto;
    $attribute_list3->aliexpress_attribute_name_id = "219"; //Origin
    $attribute_list3->attribute_name = "Origin";
    $origin = getOrigin($country_name);
    $attribute_list3->attribute_value = $origin['value'];
    $attribute_list3->aliexpress_attribute_value_id = $origin['value_id'];
    $my_attribute_list[] = $attribute_list3;
//    }

    if (!empty($material)) {
        $attribute_list2 = new AttributeDto;
        $material = getMaterial($material);
        if (!empty($material)) {
            $attribute_list2->aliexpress_attribute_name_id = $material['aliexpress_common_attribute_name_id'];
            $attribute_list2->aliexpress_attribute_value_id = $material['aliexpress_common_attribute_value_id'];
        }
    }

    $product_request->attribute_list = $my_attribute_list;

    $sku_info_list = new SkuInfoDto;
    $sku_info_list->inventory = $product['in_stock'];
    $sku_info_list->price = getPrice($ms_product->salePrices, 'Aliexpress');
    $sku_info_list->sku_code = (string)$ms_product->code;
//    $sku_info_list->discount_price = "0";
    $product_request->sku_info_list = $sku_info_list;
    $product_request->inventory_deduction_strategy = "payment_success_deduct";
    $product_request->weight = $ms_product->weight;
    $product_request->package_length = $length;
    $product_request->package_height = $height;
    $product_request->package_width = $width;
    $product_request->freight_template_id = "729258568"; // SDEK
    $product_request->shipping_lead_time = "3";
    $product_request->service_policy_id = "0";
    if ($ali_product_id)
        $req->setEditProductRequest(json_encode($product_request));
    else
        $req->setPostProductRequest(json_encode($product_request));
    return $req;
}


function getImageFromDb($db_connection, $article, $name)
{
//    $product = $db_connection->get("rz_goods", '*', ["OR" => ['ms_code' => $article, 'name[~]' => $name]]);
    $product = $db_connection->get("oc_product", '*', ["OR" => ['sku' => $article, 'origname[~]' => $name]]);
    $images = [];
    if (!empty($product['img']) or !empty($product['image']))
        $img = explode('||', (string)$product['img']);
//    echo json_encode($img) . "<br>";
    if (!empty($img))
        foreach ($img as $image) {
            $images[] = 'https://le-village.ru/image/' . $image;
        }
    return $images;
}

/**
 * @param $ali_product_id
 * @param $barcode - sometimes use article
 * @param $value
 * @return AliexpressSolutionBatchProductInventoryUpdateRequest
 * @throws Exception
 */
function updateInventoryAli($ali_product_id, $barcode, $value)
{
    if (empty($barcode) or empty($ali_product_id))
        throw new Exception('product id or article empty');
    $req = new AliexpressSolutionBatchProductInventoryUpdateRequest;
    $mutiple_product_update_list = new SynchronizeProductRequestDto;
    $mutiple_product_update_list->product_id = $ali_product_id;
    $multiple_sku_update_list = new SynchronizeSkuRequestDto;
    $multiple_sku_update_list->sku_code = $barcode;
    $multiple_sku_update_list->inventory = empty($value) ? 0 : $value;
    $mutiple_product_update_list->multiple_sku_update_list = $multiple_sku_update_list;
    $req->setMutipleProductUpdateList(json_encode($mutiple_product_update_list));
    return $req;
}

/**
 * @param array $data like list items ['ali_product_id', 'barcode', 'stock']
 * @return AliexpressSolutionBatchProductInventoryUpdateRequest
 * @throws Exception
 */
function updateInventoryAliButch(array $data)
{
    $req = new AliexpressSolutionBatchProductInventoryUpdateRequest;
    $list = [];
    foreach ($data as $datum) {
        if (empty($datum['article']) or empty($datum['ali_product_id']))
            throw new Exception('product id or article empty');
        $mutiple_product_update_list = new SynchronizeProductRequestDto;
        $mutiple_product_update_list->product_id = $datum['ali_product_id'];
        $multiple_sku_update_list = new SynchronizeSkuRequestDto;
        $multiple_sku_update_list->sku_code = $datum['article'];
        $multiple_sku_update_list->inventory = empty($datum['stock']) ? 0 : $datum['stock'];
        $mutiple_product_update_list->multiple_sku_update_list = $multiple_sku_update_list;
        $list[] = $mutiple_product_update_list;
    }
    $req->setMutipleProductUpdateList(json_encode($list));
    return $req;
}

function updatePriceAli($ali_product_id, $value,$sku,$stock)
{   


        global $topclient, $sessionKey;
    $req = new AliexpressSolutionProductEditRequest;
    $sku_info_list = new SkuInfoDto;
    $sku_info_list->price=$value;
    $sku_info_list->inventory=(int)$stock;
    $sku_info_list->sku_code=$sku;
    $edit_product_request = new PostProductRequestDto;
    $edit_product_request->product_id=(string)$ali_product_id;
    $edit_product_request->sku_info_list = $sku_info_list;
    $req->setEditProductRequest(json_encode($edit_product_request));
    $resp = $topclient->execute($req, $sessionKey);
    return $resp;

}

function rus2translit($st)
{
    $st = strtr($st,
        "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
        "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
    );
    $st = strtr($st, array(
        'ё' => "yo", 'х' => "h", 'ц' => "ts", 'ч' => "ch", 'ш' => "sh",
        'щ' => "shch", 'ъ' => '', 'ь' => '', 'ю' => "yu", 'я' => "ya",
        'Ё' => "Yo", 'Х' => "H", 'Ц' => "Ts", 'Ч' => "Ch", 'Ш' => "Sh",
        'Щ' => "Shch", 'Ъ' => '', 'Ь' => '', 'Ю' => "Yu", 'Я' => "Ya",
    ));
    return $st;
}

function translit2rus($st, $to_cyrillic = false)
{
    $cyr = [
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
        'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
    ];
    $lat = [
        'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
        'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
        'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
        'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
    ];
    if ($to_cyrillic !== false)
        $st = str_replace($cyr, $lat, $st);
    else
        $st = str_replace($lat, $cyr, $st);
    return $st;
}


function orderListParam($current_page = "1", $page_size = "20", $order_status = '', $order_status_list = [])
{
    $req = new AliexpressSolutionOrderGetRequest;
    $param = new OrderQuery;
    $param->current_page = $current_page;
    $param->page_size = $page_size;

    $param->create_date_start = date('Y-m-d H:i:s', strtotime('-3 week')); // 1 month max
    if ($order_status)
        $param->order_status = $order_status;
    elseif ($order_status_list)
        $param->order_status_list = $order_status_list;

    $req->setParam0(json_encode($param));
    return $req;
}

/**
 * @param $order_id
 * @param string $ext_info_bit_flag It defines which details to be returned.
 * Convert the number into binary format, for example, 16 to 10000.
 * Only the last 5 bits take effects, starting from the end, 1st bit is for issue information,
 * 2nd bit is for loan information, 3rd bit is for logistics information,
 * 4th bit is for buyer information and 5th bit is for refund information.
 * If any bit is 1, it means to return the corresponding information,
 * for example, 3 wich is 00011, means to return issue information and logistics information.
 * Leaving this field blank means return all information.
 * @return AliexpressSolutionOrderInfoGetRequest
 */
function aliOrderDetail($order_id, $ext_info_bit_flag = '11111')
{
    $req = new AliexpressSolutionOrderInfoGetRequest;
    $param = new OrderDetailQuery;
    $param->order_id = $order_id;
//    if ($ext_info_bit_flag != '11111')
    $param->ext_info_bit_flag = $ext_info_bit_flag;

    $req->setParam1(json_encode($param));
    return $req;
}

/**
 * @param array $prices список цен продажи
 * @param string $price_type__name название типа цен продажи
 * @param int $value значиение цены больше чем это значение
 * @return bool
 */



function generateMetaForSklad($entity, $id)
{
    $type = $entity;
    if ($entity == 'state') $entity = 'customerorder/metadata/states';
    return array(
        'meta' => array(
            'href' => 'https://online.moysklad.ru/api/remap/1.2/entity/' . $entity . '/' . $id,
            'type' => $type,
            'mediaType' => 'application/json',
            'uuidHref' => 'https://online.moysklad.ru/app/#' . $entity . '/edit?id=' . $id
        )
    );
}


function curl_get_contents($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

/**
 * Set constant categories for le_village products
 * @param $product_name
 * @param string $path_name
 * @return false|string
 */
function getConstAliCategoryIdByName($product_name, $path_name = '')
{
    if (empty($path_name)) {
        switch (true) {
            case stripos($product_name, 'Насос') !== false:
                return '4104';
            case stripos($product_name, 'Блесн') !== false:
            case stripos($product_name, 'блесн') !== false:
                return '100005544';
            case stripos($product_name, 'манометр') !== false:
            case stripos($product_name, 'Манометр') !== false:
                return '15370801';
            case stripos($product_name, 'Леск') !== false:
            case stripos($product_name, 'леск') !== false:
                return '100005541';
            case stripos($product_name, 'STOUT Труба') !== false:
            case stripos($product_name, 'Труба') !== false:
            case stripos($product_name, 'труб') !== false:
                return '200287143'; //Трубы
            case stripos($product_name, 'фитинг') !== false:
            case stripos($product_name, 'Фитинг') !== false:
            case stripos($product_name, 'Муфта') !== false:
            case stripos($product_name, 'Тройник') !== false:
                return '131006'; // Крепления для труб
            default:
                return false;
        }
    } else {
        switch (true) {
            case stripos($product_name, 'Водонагреватели газовые') !== false:
                return '60703';
            case stripos($product_name, 'Электрические котлы') !== false:
                return '60702';
            case stripos($product_name, 'ИНЖЕНЕРНАЯ САНТЕХНИКА') !== false:
                return '200282144'; // Трубы и фитинги
//                return '200282142'; // Водопровод
//                return '131006'; // крепления для труб и фитинги
            default:
                return false;
        }
    }
}

function getPrice($sale_prices, $price_type_name)
{
    foreach ($sale_prices as $price) {
        switch ($price->priceType->name) {
            case $price_type_name:
                return $price->value / 100;
        }
    }
    return 0;
}

function checkGroupName($name)
{
    switch (true) {
        case stripos($name, 'РЫБАЛКА') !== false:
        case stripos($name, 'ИНЖЕНЕРНАЯ САНТЕХНИКА') !== false:
        case stripos($name, 'ТОРГОВЛЯ'):
            return true;
        default:
            return false;
    }
}

function getAssortFromArray(array $assorts, $product_id)
{
    foreach ($assorts as $asrt) {
        switch ($asrt->id) {
            case $product_id:
//                return $asrt->quantity;
                return $asrt->stock;
            default:
                return 0;
        }
    }
    return 0;
}


function add_products($ms_products)
{
    global $db_connection, $logger;
    $asrt_list = file_get_contents('tmp/asrt.json');
    $asrt_list = json_decode($asrt_list, true);

    foreach ($ms_products as $product) {

        if (empty($product->article)) {
            $logger->error("Article is empty", ['ms_id' => $product->id]);
            return;
        }

        $price__true = checkSalePrices($product->salePrices, 'Aliexpress');
//        $photo__is_set = true;
        if (!empty($product->attributes))
            foreach ($product->attributes as $attribute)
                if (!empty($attribute->name) and $attribute->name == 'ССЫЛКА НА ФОТО') {
//                                    if (stripos($attribute->value, 'yadi.sk') === false)
                    if (stripos($attribute->value, 'yadi.sk') !== false)
                        $logger->error('Wrong image link', ['link' => $attribute->value]);
                    $photo__is_set = false;
                }
        if ($price__true) {
//                    echo 'Get from moysklad product ' . $product->name . "<br>";
            $pr = $db_connection->getProduct($product->id, $product->article);
            $json_product = json_encode($product);

            $wh = [
                'id' => $product->id
//                'code' => $product->code
//                'article' => $product->article
            ];
            $asrt = searchAssort($wh, $asrt_list);
            if (empty($asrt['stock']))
                $logger->debug("Empty stock for ");
            $in_stock = empty($asrt['stock']) ? 0 : $asrt['stock'];

            $logger->debug("stock $in_stock for product ", ['ms_id' => $product->id, 'code' => $product->article]);

//            $in_stock = empty($product->quantity) ? 0 : (int)$product->quantity;
//            $in_stock = empty($in_stock) ? 0 : (int)$in_stock;

            if (!empty($pr['ms_product_full_data']) and $pr['ms_product_full_data'] == $json_product) {
                // если товар есть в бд и json обьект равен с записью в бд
                if (!empty($pr['in_stock']) and ($pr['in_stock'] != $in_stock)) {
                    $db_connection->updateProduct(['ali_state' => 'Stock', 'in_stock' => $in_stock, 'publish' => 1], ['ms_id' => $pr['ms_id']]);
                    $logger->debug('Up stock state', ['ms_id' => $pr['ms_id']]);
                } else {
                    $db_connection->updateProduct(['ali_state' => 'Draft', 'publish' => 1, 'in_stock' => $in_stock], ['ms_id' => $pr['ms_id']]);
                    $logger->debug('Up stock draft', ['ms_id' => $pr['ms_id']]);
                }
            } elseif (!empty($pr['ms_product_full_data']) and ['ms_product_full_data'] != $json_product) {
                // если товар есть в бд и json обьект не равен с записью в бд
                $db_connection->updateProduct([
                    "ms_product_full_data" => $json_product,
                    "ali_state" => "Update",
                    "publish" => 1,
                    "in_stock" => $in_stock,
                    "name" => (string)$product->name
                ], ["ms_id" => $pr["ms_id"]]);
                $logger->debug('Up stock update', ['ms_id' => $pr['ms_id']]);
            } else {
                // если товара нет в бд.
                $db_connection->createProduct($product, $in_stock, $json_product);
                $logger->debug('Up stock new', ['ms_id' => $product->id]);
            }
        } else {
            $logger->error('Price is false', ['prices' => $product->salePrices, 'p_name' => $product->name]);
        }
    }

}

function searchAssort(array $fields, array $asrt)
{
    if (!file_exists('tmp/asrt.json'))
        return false;
    $item = false;
    foreach ($fields as $key => $value) {
        if (!empty($value)) {
            $item = array_search((string)$value, array_column($asrt, $key));
            if ($item !== false)
                break;
        }
    }
    return $asrt[$item];
}

function setOfflineAliProduct(string $product_id)
{
    if (empty($product_id)) return false;

    global $topclient, $sessionKey;
    $req = new AliexpressPostproductRedefiningOfflineaeproductRequest;
    $req->setProductIds($product_id);
    return $topclient->execute($req, $sessionKey);
}





