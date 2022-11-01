<?php

$prefix = version_compare(VERSION, '2.3.0') >= 0 ? 'extension/' : '';
require_once DIR_CATALOG . 'model/' . $prefix . '/payment/yoomoney/autoload.php';

use YooKassa\Client;
use YooKassa\Model\NotificationEventType;
use YooMoneyModule\Model\ApiClient;
use YooMoneyModule\Model\VersionCompatibilityHelper;

class ModelExtensionPaymentYoomoneyOauth extends Model
{
    const OAUTH_APP_URL = 'https://yookassa.ru/integration/oauth-cms';
    const MODULE_NAME = 'yoomoney';
    const CMS_NAME = 'opencart';

    private $yoomoneyModel;

    /**
     * Формирует параметры, инициирует отправку, получает результат запроса на получение URL
     * для авторизации пользователя в OAuth приложении
     *
     * @return string[] - массив для последующей кодировки в JSON для передачи в JS
     */
    public function getOauthConnectUrl()
    {
        $data = array(
            'state' => $this->getOauthState(),
            'cms' => self::CMS_NAME,
            'host' => $_SERVER['HTTP_HOST']
        );

        $options = array(
            CURLOPT_URL => self::OAUTH_APP_URL . '/authorization',
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        );

        try {
            $response = $this->makeRequest($options);
        } catch (Exception $e) {
            $this->log('error', 'Failed to get OAuth token: ' . $e->getMessage());
            return array('error' => 'Got error while getting OAuth link.');
        }

        $data = json_decode($response, true);

        if (!isset($data['oauth_url'])) {
            $error = empty($data['error']) ? 'OAuth URL not found' : $data['error'];
            $this->log('error', 'Got error while getting OAuth link. Response body: ' . $response);
            return array('error' => $error);
        }

        return array('oauth_url' => $data['oauth_url']);
    }

    /**
     * Формирует параметры, инициирует отправку, получает результат запроса на получение токена,
     * проверяет ответ на запрос, инициирует сохранение токена в БД
     *
     * @return string[] - массив для последующей кодировки в JSON для передачи в JS
     */
    public function getOauthToken()
    {
        $data = array(
            'state' => $this->getOauthState()
        );

        $this->log('info', 'Sending request for OAuth token. Request parameters: ' . json_encode($data));

        $options = array(
            CURLOPT_URL => self::OAUTH_APP_URL . '/get-token',
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        );

        try {
            $response = $this->makeRequest($options);
        } catch (Exception $e) {
            $this->log('error', 'Failed to get OAuth token: ' . $e->getMessage());
            return array('error' => 'Got error while getting OAuth token.');
        }

        $data = json_decode($response, true);

        if (!isset($data['access_token'])) {
            $error = empty($data['error']) ? 'OAuth token not found' : $data['error'];
            $this->log('error', 'Got error while getting OAuth token. Response body: ' . $response);
            return array('error' => $error);
        }

        if (!isset($data['expires_in'])) {
            $error = empty($data['error']) ? 'Expires_in parameter not found' : $data['error'];
            $this->log('error', $error . '. Response body: ' . $response);
            return array('error' => $error);
        }

        $token = $this->config->get('yoomoney_kassa_access_token');

        if ($token) {
            $this->log('info', 'Old token found. Trying to revoke.');
            $this->revokeToken($token);
        }

        $this->saveSettings(array(
                'yoomoney_kassa_access_token' => $data['access_token'],
                'yoomoney_kassa_token_expires_in' => $data['expires_in']
        ));

        $this->config->set('yoomoney_kassa_access_token', $data['access_token']);

        try {
            $this->subscribe();
        } catch (Exception $e) {
            $this->log('error', $e->getMessage());
            return array('error' => 'Failed to get make webhooks');
        }

        try {
            $this->saveShopInfo();
        } catch (Exception $e) {
            $this->log('error', $e->getMessage());
            return array('error' => 'Failed to get shop info');
        }

        return array('success' => true);

    }

    /**
     * Формирует параметры, инициирует отправку, получает результат запроса на отзыва токена
     *
     * @param string $token - OAuth токен
     * @return string[]|void
     */
    private function revokeToken($token)
    {
        $data = array(
            'state' => $this->getOauthState(),
            'token' => $token,
            'cms' => self::CMS_NAME
        );

        $options = array(
            CURLOPT_URL => self::OAUTH_APP_URL . '/revoke-token',
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        );

        try {
            $response = $this->makeRequest($options);
        } catch (Exception $e) {
            $this->log('error', 'Failed to get OAuth token: ' . $e->getMessage());
            return array('error' => 'Got error while getting OAuth token.');
        }

        $data = json_decode($response, true);

        if (!isset($data['success'])) {
            $error = empty($data['error']) ? 'Got error while revoking OAuth token' : $data['error'];
            $this->log('error', 'Got error while revoking OAuth token. Response body: ' . $response);
        }
    }

    /**
     * Выполянет запрос с полученными параметрами
     *
     * @param array $options - массив curl опций
     * @return bool|string
     * @throws Exception
     */
    private function makeRequest($options)
    {
        $optionsConst = array(
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1
        );
        $options = $optionsConst + $options;
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            throw new Exception(
                'Response status code is not 200. Code: ' . $status . ' Response: ' . $result
            );
        }

        return $result;
    }

    /**
     * Проверяет в БД state и возвращает его, если нету в БД, генерирует его
     *
     * @return string state - уникальный id для запросов в OAuth приложение
     */
    private function getOauthState()
    {
        $state = $this->getYoomoneyModel()->getKassaModel()->getOauthCmsState();

        if (!$state) {
            $state = substr(md5(time()), 0, 12);
            $this->saveSettings(array('yoomoney_kassa_oauth_state' => $state));
        }

        return $state;
    }

    /**
     * Запись в лог. Использует основную функцию из файла yoomoney.php
     *
     * @param $level
     * @param $message
     * @param $context
     * @return void
     */
    private function log($level, $message, $context = null)
    {
        $this->getYoomoneyModel()->log($level, $message, $context);
    }

    /**
     * Получает инфомацию о магазине в Юkassa и вызывает ф-ю для сохранения ее в БД
     *
     * @return void
     * @throws Exception
     */
    private function saveShopInfo()
    {
        $apiClient = $this->getClient();
        $shopInfo = $apiClient->me();
        if (!isset($shopInfo[ApiClient::SHOP_INFO_ACCOUNT_ID])) {
            throw new Exception('Shop id not found');
        }
        $this->saveSettings(array(
            'yoomoney_kassa_shop_id' => $shopInfo[ApiClient::SHOP_INFO_ACCOUNT_ID],
        ));
    }

    /**
     * Загружает один раз и затем возвращает модель, описанную в yoomoney.php
     *
     */
    private function getYoomoneyModel()
    {
        if (!$this->yoomoneyModel) {
            $prefix = VersionCompatibilityHelper::getModulePrefix();
            $this->load->model($prefix . 'payment/' . self::MODULE_NAME);
            $this->yoomoneyModel = $prefix === ''
                ? $property = 'model_payment_' . self::MODULE_NAME
                : $property = 'model_extension_payment_' . self::MODULE_NAME;

            $this->yoomoneyModel = $this->__get($property);
        }
        return $this->yoomoneyModel;
    }

    /**
     * Проверяет существующие подписки, удаляет некорректные и создает новые
     *
     * @return void
     */
    private function subscribe()
    {
        $needWebHookList = array(
            NotificationEventType::PAYMENT_SUCCEEDED,
            NotificationEventType::PAYMENT_CANCELED,
            NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE,
            NotificationEventType::REFUND_SUCCEEDED,
        );

        $url = new Url(HTTP_CATALOG, HTTPS_CATALOG);

        $webHookUrl = str_replace(
            'http://',
            'https://',
            $url->link(
                VersionCompatibilityHelper::getModulePrefix() . 'payment/' . self::MODULE_NAME . '/capture'
                , ''
                , true
            )
        );

        $currentWebHookList = $this->getClient()->getWebhooks()->getItems();
        foreach ($needWebHookList as $event) {
            $hookIsSet = false;
            foreach ($currentWebHookList as $webHook) {
                if ($webHook->getEvent() === $event) {
                    if ($webHook->getUrl() === $webHookUrl) {
                        $hookIsSet = true;
                        continue;
                    }

                    $this->getClient()->removeWebhook($webHook->getId());
                }
            }
            if (!$hookIsSet) {
                $this->getClient()->addWebhook(array('event' => $event, 'url' => $webHookUrl));
            }
        }
    }

    /**
     * Производит запись в БД данных с предварительным удалением
     *
     * @param array $data - массив вида {key} => {value}, где key и value - соответсвующие поля в таблице oc_setting
     * @return void
     */
    private function saveSettings($data)
    {
        foreach ($data as $key => $value) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = '" . $key . "' AND `code` = '" . $this->db->escape(self::MODULE_NAME) . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape(self::MODULE_NAME) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
        }
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        return $this->getYoomoneyModel()->getClient();
    }
}
class ModelPaymentYoomoneyOauth extends ModelExtensionPaymentYoomoneyOauth
{

}