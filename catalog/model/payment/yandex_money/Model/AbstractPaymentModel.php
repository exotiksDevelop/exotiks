<?php

namespace YandexMoneyModule\Model;

class AbstractPaymentModel
{
    const PAYMENT_KASSA = 'kassa';
    const PAYMENT_WALLET = 'wallet';
    const PAYMENT_BILLING = 'billing';

    /**
     * @var \Config
     */
    private $config;

    /**
     * @var string
     */
    private $paymentType;

    protected $enabled;
    protected $successOrderStatus;
    protected $geoZone;
    protected $minPaymentAmount;
    protected $displayName;

    /** @var bool */
    protected $createOrderBeforeRedirect;

    /** @var bool */
    protected $clearCartAfterOrderCreation;

    /**
     * AbstractPaymentModel constructor.
     * @param \Config $config
     * @param $paymentType
     */
    public function __construct($config, $paymentType)
    {
        $this->config = $config;
        $this->paymentType = $paymentType;

        $this->enabled = (bool)$this->getConfigValue('enabled');
        $this->displayName = $this->getConfigValue('display_name');
        $this->successOrderStatus = (int)$this->getConfigValue('success_order_status');
        $this->minPaymentAmount = (int)$this->getConfigValue('minimum_payment_amount');
        $this->geoZone = (int)$this->getConfigValue('geo_zone');
    }

    protected function getConfigKey($key)
    {
        return 'yandex_money_' . $this->paymentType . '_' . $key;
    }

    protected function getConfigValue($key)
    {
        return $this->config->get($this->getConfigKey($key));
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getSuccessOrderStatusId()
    {
        return $this->successOrderStatus;
    }

    public function getMinPaymentAmount()
    {
        return $this->minPaymentAmount;
    }

    public function getGeoZoneId()
    {
        return $this->geoZone;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getSortOrder()
    {
        return (int)$this->getConfigValue('sort_order');
    }

    public function applyTemplateVariables($controller, &$templateData, $orderInfo)
    {
        return 'extension/payment/yandex_money';
    }

    /**
     * @return bool
     */
    public function getCreateOrderBeforeRedirect()
    {
        return $this->createOrderBeforeRedirect;
    }

    /**
     * @return bool
     */
    public function getClearCartBeforeRedirect()
    {
        return $this->clearCartAfterOrderCreation;
    }
}