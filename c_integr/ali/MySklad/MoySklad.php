<?php

namespace MySklad;

// no time
//use TelegramErrorLogger;

use stdClass;

class MoySklad
{
    private $auth;
    private $token;
    private $log_errors;
    private $data;

    public function __construct($auth, $token = null, $log_errors = true)
    {
        $this->auth = $auth;
        $this->token = $token;
        $this->log_errors = $log_errors;
    }

    /**
     * Получить список товаров.
     * @param string $filter Фильтрация по имени поля и значение.
     * Например отсортировать по статусу ["name"=>"state", "value"=>"idForMyState"]
     * @param int $limit колличество товаров за запрос
     * @return mixed only 1000 items
     */
    public function getProducts(string $filter = '', $limit = 1000,$offset=0)
    {
        if (!$filter) {
            return $this->endpoint("product?limit=$limit&offset=$offset"); // todo: rework with no rows or add check for errors...
        } else {
//            return $this->endpoint("product?limit=$limit&filter=" . $filter['name'] . '=' . $filter['value'])->rows;
            return $this->endpoint("product?limit=$limit&filter=$filter");
        }
    }


    /**
     * Получить список услуг.
     * @param array $filter Фильтрация по имени поля и значение.
     * Например отсортировать по статусу ["name"=>"state", "value"=>"idForMyState"]
     * @return mixed
     */
    public function getServices($filter = [])
    {
        if (!$filter) {
            return $this->endpoint('service');
        } else {
            return $this->endpoint('service?filter=' . $filter['name'] . '=' . $filter['value']);
        }
    }

    /**
     * Получить список групп товаров.
     * @param array $filter Фильтрация по имени поля и значение.
     * Например отсортировать по статусу ["name"=>"state", "value"=>"idForMyState"]
     * @return mixed
     */
    public function getProductFolders($filter = [])
    {
        if (!$filter) {
            return $this->endpoint('productfolder')->rows;
        } else {
            return $this->endpoint('productfolder?filter=' . $filter['name'] . '=' . $filter['value'])->rows;
        }
    }

    /**
     * Contacts the various API's endpoints
     * \param $api the API endpoint
     * \param $content the request parameters as array
     * \param $post boolean tells if $content needs to be sends
     * \return the JSON Telegram's reply.
     * @param string $api
     * @param array $content
     * @param string $method
     * @return object
     */
    public function endpoint(string $api, $content = [], $method = 'GET')
    {
        $url = "https://online.moysklad.ru/api/remap/1.2/entity/{$api}";
        $reply = null;
        switch ($method) {
            case 'POST':
                $reply = $this->sendAPIRequest($url, $content, 'POST');
                break;
            case 'GET':
                $reply = $this->sendAPIRequest($url, []);
                break;

        }
        return json_decode($reply);
    }

        public function endpointStock($limit=1000,$offset=0)
    {
        $url = "https://online.moysklad.ru/api/remap/1.1/report/stock/all?stockMode=all&store.id=7d8ae43d-eb9a-11e9-0a80-03b700248002&limit=".$limit.'&offset='.$offset;
        $reply = null;
        $reply = $this->sendAPIRequest($url, []);
        return json_decode($reply);
    }

    public function sendAPIRequest($url, array $content = [], $method = 'GET')
    {
        $ch = curl_init();
        $auth = $this->token ? "Authorization: Bearer {$this->token}" : "Authorization: Basic {$this->auth}";
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_USERAGENT => 'moysklad-API-client/1.2',
            CURLOPT_HTTPHEADER => [$auth]
        ];
        if ($method == 'POST' and $content) {
//            $body = json_encode($content);
//            switch (json_last_error()) {
//                case JSON_ERROR_NONE:
//                    echo ' - Ошибок нет';
//                    break;
//                case JSON_ERROR_DEPTH:
//                    echo ' - Достигнута максимальная глубина стека';
//                    break;
//                case JSON_ERROR_STATE_MISMATCH:
//                    echo ' - Некорректные разряды или несоответствие режимов';
//                    break;
//                case JSON_ERROR_CTRL_CHAR:
//                    echo ' - Некорректный управляющий символ';
//                    break;
//                case JSON_ERROR_SYNTAX:
//                    echo ' - Синтаксическая ошибка, некорректный JSON';
//                    break;
//                case JSON_ERROR_UTF8:
//                    echo ' - Некорректные символы UTF-8, возможно неверно закодирован';
//                    break;
//                default:
//                    echo ' - Неизвестная ошибка';
//                    break;
//            }
            $options[CURLOPT_POSTFIELDS] = json_encode($content);
            $options[CURLOPT_HTTPHEADER] = [
                $auth,
                "Content-Type: application/json"
            ];
        }

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok' => false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Get the POST request of a user in a Webhook.
     */
    public function getPostData()
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');

            return json_decode($rawData);
        } else {
            return $this->data;
        }
    }

    public function getOrganization($name = '')
    {
        if (empty($name)) {
            return $this->endpoint('organization');
        } else {
            return $this->endpoint("organization?filter=name=$name");
        }
    }

    public function getAgent(array $counterparty, $create = false)
    {
//        if ($counterparty['phone'] and empty($counterparty['name']))
        if ($counterparty['phone'])
            $agent = $this->endpoint("counterparty?search={$counterparty['phone']}");
//            $agent = $this->endpoint("counterparty?filter=phone={$counterparty['phone']}");
//        elseif ($counterparty['name'] and empty($counterparty['phone']))
//        if ($counterparty['name'])
//            $agent = $this->endpoint("counterparty?filter=name={$counterparty['name']}");
        if (empty($agent->rows) and $create) {
            $body = [
                'name' => $counterparty['name'],
                'phone' => $counterparty['phone'],
                'actualAddress' => $counterparty['address']
            ];
            $agent = $this->endpoint('counterparty', $body, 'POST'); // Todo сделать создание контрагента
        }
        return $agent;
    }

    /**
     * @param array $orders not finished
     */
    public function createOrders(array $orders)
    {
        $org_metadata = $this->getOrganization()->meta;
        $body = [];
        foreach ($orders as $order) {
            $agent = $this->getAgent($order['agent'])->meta;
            $body[] = [
                'name' => 'tg-' . $order['id'],
                'organization' => $org_metadata,
                'agent' => $agent
            ];
        }

    }

    /**
     * @param array $body
     * "name" : "custom_name", (required)
     * "organization": { (required)
     * "meta": {}
     * },
     * "agent": { (required) "meta": {} },
     * "state": {"meta": {} },
     * "positions": [{
     * "quantity": 10,
     * "price": 100,
     * "discount": 0,
     * "vat": 0,
     * "assortment": {"meta": {} },
     * "reserve": 10
     * }
     * and others
     * @return mixed
     */
    public function createOrder($body)
    {
        return $this->endpoint('customerorder', $body, 'POST');
    }

    public function updateOrder($id, $body)
    {
        return $this->endpoint("customerorder/$id", $body, 'PUT');
    }

    public function getCustomerOrder($filter)
    {
        return $this->endpoint('customerorder?filter=' . $filter);
    }


    function downloadFile($url, $save_to)
    {
        $auth = "Authorization: Basic $this->auth";

//    set_time_limit(0);
//This is the file where we save the    information
        $fp = fopen($save_to, 'w+');
        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ", "%20", $url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
// write curl response to file
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$auth]);
// get curl response
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    /**
     * @param $image_meta_url
     * @return string
     */
    public function downloadImage($image_meta_url)
    {
        $ms_product_images = json_decode($this->sendAPIRequest($image_meta_url));
        $download_path = '';
        if ($ms_product_images->rows)
            foreach ($ms_product_images->rows as $image_data) {
                $filename = $image_data->filename;
                $download_path = 'images/' . $filename;
                if (!file_exists($download_path)) {
                    @mkdir('images/', 0777, true);
                }
                $this->downloadFile($image_data->meta->downloadHref, $download_path);
                return $download_path;
            }
        return $download_path;
    }

    public function getImageMiniature($url)
    {
        $images = json_decode($this->sendAPIRequest($url));
        $miniatures = [];
        foreach ($images->rows as $image) {
            $miniatures[] = $image->miniature->href;
        }

        return $miniatures;
    }

    /**
     * @param string $filter
     * example
     * $filter = 'state=idForMyState'
     * $filter = ["name"=>"state", "value"=>"idForMyState"] outdated
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getAssortment(string $filter = '', $limit = 1000, $offset = 0)
    {
        $url = "assortment?limit=$limit&offset=$offset";
        if (!empty($filter))
            $url .= "&filter={$filter}";

        return $this->endpoint($url);
    }
    public function getStock(string $filter = '', $limit = 1000, $offset = 0)
    {
        $url = "?limit=$limit&offset=$offset";
        if (!empty($filter))
            $url .= "&filter={$filter}";

        return $this->endpoint($url);
    }

    /**
     * @param $name
     * @return string id of the created entity or blank string
     */
    public function createCustomEntity($name)
    {
        $response = $this->endpoint('customentity', ['name' => $name], 'POST');
        if (!empty($response->id))
            return (string)$response->id;
        else return '';
    }

    /**
     * @param string $id customentity
     * @param array $data with keys ['name', 'code', 'description', 'externalCode']
     * @return stdClass
     */
    public function addFieldCustomEntity(string $id, array $data)
    {
        return $this->endpoint("customentity/{$id}", $data, "POST");
    }

    /**
     * @param int $limit
     * @param string $next_href like https://online.moysklad.ru/api/remap/1.2/entity/productfolder?limit=100&offset=100
     * @return object list productfolders
     */
    public function getAllProductfolders($limit = 1000, $next_href = '')
    {
        $limit = 'limit=' . $limit;
        if (!empty($next_href)) {
            parse_str(parse_url($next_href, PHP_URL_QUERY), $p);
            $limit = "limit={$p['limit']}&offset={$p['offset']}";
        }
        return $this->endpoint("productfolder?{$limit}");
    }

}
