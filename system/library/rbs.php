<?php
/**
 * Интеграция платежного шлюза RBS с OpenCart
 */
class RBS {
    /** @var string $test       Адрес тестового шлюза */
    private $test_url = 'https://3dsec.sberbank.ru/payment/rest/';

    /** @var string $prod_url   Адрес боевого шлюза*/
    private $prod_url = 'https://securepayments.sberbank.ru/payment/rest/';

    /** @var string $version    Версия плагина*/
    private $version = '1.0';

    /** @var string $login      Логин продавца*/
    private $login;

    /** @var string $password   Пароль продавца */
    private $password;

    /** @var string $mode       Режим работы модуля (test/prod) */
    private $mode;

    /** @var string $stage      Стадийность платежа (one/two) */
    private $stage;

    /** @var boolean $logging   Логгирование (1/0) */
    private $logging;

    /** @var string $currency   Числовой код валюты в ISO 4217 */
    private $currency;

    /**
     * Магический метод, который заполняет инстанс
     *
     * @param $property
     * @param $value
     * @return $this
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * Формирование запроса в платежный шлюз и парсинг JSON-ответа
     *
     * @param string $method Метод запроса в ПШ
     * @param mixed[] $data Данные в запросе
     * @return mixed[]
     */
    private function gateway($method, $data) {
        // Добавления логина и пароля продавца к каждому запросу
        $data['userName'] = $this->login;
        $data['password'] = $this->password;

        // Выбор адреса ПШ в зависимости от выбранного режима
        if ($this->mode == 'test') {
            $url = $this->test_url;
        } else {
            $url = $this->prod_url;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.$method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array('CMS: OpenCart', 'Module-Version: ' . $this->version),
            CURLOPT_SSLVERSION => 6
        ));
        $response = curl_exec($curl);
        if ($this->logging) {
            $this->logger($url, $method, $data, $response);
        }
        $response = json_decode($response, true);
        curl_close($curl);

        return $response;
    }

    /**
     * Логирование запроса и ответа от ПШ
     *
     * @param string $url
     * @param string $method
     * @param mixed[] $data
     * @param mixed[] $response
     * @return integer
     */
    private function logger($url, $method, $data, $response) {
        $this->library('log');
        $logger = new Log('rbs.log');
        $logger->write('RBS PAYMENT '.$url.$method.' REQUEST: '.json_encode($data). ' RESPONSE: '.json_encode($response));
    }

    /**
     * Решистрация заказа в ПШ
     *
     * @param string $order_number Номер заказа в магазине
     * @param integer $amount Сумма заказа
     * @param string $return_url Страница в магазине, на которую необходимо вернуть пользователя
     * @return mixed[] Ответ ПШ
     */
    function register_order($order_number, $amount, $return_url) {
        $data = array(
            'orderNumber' => $order_number,
            'amount' => $amount,
            'returnUrl' => $return_url
        );
        if ($this->currency != 0) {
            $data['currency'] = $this->currency;
        }
        return $this->gateway($this->stage == 'two' ? 'registerPreAuth.do' : 'register.do', $data);
    }

    /**
     * Статус заказа в ПШ
     *
     * @param string $orderId Идентификатор заказа в ПШ
     * @return mixed[] Ответ ПШ
     */
    public function get_order_status($orderId) {
        return $this->gateway('getOrderStatusExtended.do', array('orderId' => $orderId));
    }

    /**
     * В версии 2.1 нет метода Loader::library()
     * Своя реализация
     * @param $library
     */
    private function library($library) {
        $file = DIR_SYSTEM . 'library/' . str_replace('../', '', (string)$library) . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load library ' . $file . '!');
            exit();
        }
    }
}