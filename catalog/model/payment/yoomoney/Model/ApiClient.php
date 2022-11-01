<?php

namespace YooMoneyModule\Model;

use \YooKassa\Client;

class ApiClient
{
    const MODULE_VERSION = '2.3.4';
    const SHOP_INFO_ACCOUNT_ID = 'account_id';
    const SHOP_INFO_TEST = 'test';
    const SHOP_INFO_FISCALIZATION_ENABLED = 'fiscalization_enabled';

    private $kassaModel;
    private $client;
    private $isConnectFailed = false;
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function setKassaModel($kassaModel)
    {
        $this->kassaModel = $kassaModel;
    }

    public function getClient()
    {
        if (!$this->kassaModel) {
            throw new \Exception('Model for client not set');
        }

        if (!$this->client){
            $this->client = new Client();
            $this->setClientAuth();
            $this->client->setLogger($this->logger);
            $userAgent = $this->client->getApiClient()->getUserAgent();
            $userAgent->setCms('OpenCart', VERSION);
            $userAgent->setModule('YooMoney',self::MODULE_VERSION);
        }
        return $this->client;
    }

    /**
     * Устанавливает креды авторизации клиента кассы,
     * приоритет - OAuth токен
     *
     * @return void
     */
    private function setClientAuth()
    {
        if ($this->kassaModel->getOauthToken()) {
            $this->client->setAuthToken($this->kassaModel->getOauthToken());
            return;
        }

        $this->client->setAuth(
            $this->kassaModel->getShopId(),
            $this->kassaModel->getPassword()
        );
    }

    public function fetchShopInfo()
    {
        $shopInfo = null;

        try {
            $shopInfo = $this->getClient()->me();
        } catch (\Exception $e) {
            $this->isConnectFailed = true;
        }

        if (!isset(
            $shopInfo[self::SHOP_INFO_ACCOUNT_ID],
            $shopInfo[self::SHOP_INFO_TEST],
            $shopInfo[self::SHOP_INFO_FISCALIZATION_ENABLED])
        ) {
            $this->isConnectFailed = true;
        }

        return $shopInfo;
    }

    public function isConnectionFailed()
    {
        return (bool) $this->isConnectFailed;
    }
}