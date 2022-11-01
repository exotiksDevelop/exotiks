<?php

$prefix = version_compare(VERSION, '2.3.0') >= 0 ? 'extension/' : '';
require_once DIR_CATALOG.'model/'.$prefix.'payment/yoomoney/autoload.php';

use YooKassa\Client;
use YooMoneyModule\Model\ApiClient;

class YooMoneyKassaModel extends \YooMoneyModule\Model\KassaModel
{
    private $invoiceEnable;
    private $invoiceSubject;
    private $invoiceMessage;
    private $invoiceLogo;
    /** @var ApiClient */
    private $apiClient;

    /**
     * YooMoneyKassaModel constructor.
     *
     * @param \Config $config
     */
    public function __construct($config)
    {
        parent::__construct($config);

        $this->invoiceEnable  = (bool)$config->get('yoomoney_kassa_invoice');
        $this->invoiceSubject = $config->get('yoomoney_kassa_invoice_subject');
        $this->invoiceMessage = $config->get('yoomoney_kassa_invoice_message');
        $this->invoiceLogo    = (bool)$config->get('yoomoney_kassa_invoice_logo');
    }

    /**
     * @param $apiClient
     */
    public function setApiClient($apiClient)
    {
        $this->apiClient = $apiClient;
        $this->apiClient->setKassaModel($this);
    }

    public function setIsEnabled($value)
    {
        $this->enabled = $value ? true : false;
    }

    public function setShopId($value)
    {
        $this->shopId = $value;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function setEpl($value)
    {
        $this->epl = $value ? true : false;
    }

    public function setUseInstallmentsButton($value)
    {
        $this->useInstallmentsButton = (bool) $value;
    }

    public function setPaymentMethodFlag($paymentMethod, $value)
    {
        if (array_key_exists($paymentMethod, $this->paymentMethods)) {
            $this->paymentMethods[$paymentMethod] = $value ? true : false;
        }
    }

    public function setSendReceipt($value)
    {
        $this->isSendReceipt = $value ? true : false;
    }

    public function setDefaultTaxRate($value)
    {
        if (!in_array($value, $this->getTaxRateList())) {
            $value = 1;
        }
        $this->defaultTaxRate = (int)$value;
    }

    public function setDefaultTaxSystemCode($value)
    {
        if (!in_array($value, $this->getTaxSystemCodeList())) {
            $value = 0;
        }
        $this->defaultTaxSystemCode = (int)$value;
    }

    public function setTaxRates($taxRates)
    {
        $all            = $this->getTaxRateList();
        $this->taxRates = array();
        foreach ($taxRates as $shopTaxRateId => $taxRate) {
            if (in_array($taxRate, $all)) {
                $this->taxRates[$shopTaxRateId] = (int)$taxRate;
            }
        }
    }

    public function setSuccessOrderStatusId($value)
    {
        $this->successOrderStatus = (int)$value;
    }

    public function setMinPaymentAmount($value)
    {
        if ($value < 0) {
            $value = 0;
        }
        $this->minPaymentAmount = (int)$value;
    }

    public function setGeoZoneId($value)
    {
        $this->geoZone = $value;
    }

    public function setDebugLog($value)
    {
        $this->isLog = $value;
    }

    public function setDisplayName($value)
    {
        $this->displayName = $value;
    }

    /**
     * @return bool
     */
    public function isInvoicesEnabled()
    {
        return $this->invoiceEnable;
    }

    /**
     * @param bool $value
     */
    public function setInvoicesEnabled($value)
    {
        $this->invoiceEnable = $value;
    }

    /**
     * @return string
     */
    public function getInvoiceSubject()
    {
        return $this->invoiceSubject;
    }

    /**
     * @param string $value
     */
    public function setInvoiceSubject($value)
    {
        $this->invoiceSubject = $value;
    }

    /**
     * @return string
     */
    public function getInvoiceMessage()
    {
        return $this->invoiceMessage;
    }

    /**
     * @param string $value
     */
    public function setInvoiceMessage($value)
    {
        $this->invoiceMessage = $value;
    }

    /**
     * @return bool
     */
    public function getSendInvoiceLogo()
    {
        return $this->invoiceLogo;
    }

    /**
     * @param bool $value
     */
    public function setSendInvoiceLogo($value)
    {
        $this->invoiceLogo = $value;
    }

    /**
     * @param bool $value
     */
    public function setCreateOrderBeforeRedirect($value)
    {
        $this->createOrderBeforeRedirect = (bool)$value;
    }

    /**
     * @param bool $value
     */
    public function setClearCartBeforeRedirect($value)
    {
        $this->clearCartAfterOrderCreation = (bool)$value;
    }

    /**
     * @return bool
     */
    public function checkConnection()
    {
        return !$this->apiClient->isConnectionFailed();
    }

    /**
     * @param bool $value
     */
    public function setShowLinkInFooter($value)
    {
        $this->showInFooter = $value ? true : false;
    }

    /**
     * @return array|null
     */
    public function fetchShopInfo()
    {
        if ($shopInfo = $this->apiClient->fetchShopInfo()) {
            $this->setShopId($shopInfo[ApiClient::SHOP_INFO_ACCOUNT_ID]);
            $this->isTestShop = (bool) $shopInfo[ApiClient::SHOP_INFO_TEST];
            $this->isFiscalizationEnabled = (bool) $shopInfo[ApiClient::SHOP_INFO_FISCALIZATION_ENABLED];
        }
        return $shopInfo;
    }

    /**
     * @return Client
     * @throws Exception
     */
    public function getApiClient()
    {
        return $this->apiClient->getClient();
    }
}
