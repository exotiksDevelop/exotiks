<?php

namespace YooMoneyModule\Model;

use YooKassa\Model\CurrencyCode;
use YooKassa\Model\PaymentData\B2b\Sberbank\VatDataRate;
use YooKassa\Model\PaymentData\B2b\Sberbank\VatDataType;
use YooKassa\Model\PaymentMethodType;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;

class KassaModel extends AbstractPaymentModel
{
    const CUSTOM_PAYMENT_METHOD_WIDGET = 'widget';
    /**
     * @var array
     */
    private static $_enabledTestMethods = array(
        PaymentMethodType::YOO_MONEY => true,
        PaymentMethodType::BANK_CARD => true,
    );

    private static $_disabledPaymentMethods = array(
        PaymentMethodType::WEBMONEY,
        PaymentMethodType::B2B_SBERBANK,
        PaymentMethodType::WECHAT,
        PaymentMethodType::SBP,
    );

    /**
     * @var string
     */
    protected $shopId;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $epl;

    /**
     * @var array
     */
    protected $paymentMethods;

    /**
     * @var bool
     */
    protected $isSendReceipt;

    /**
     * @var bool
     */
    protected $isSecondReceipt;

    /**
     * @var int
     */
    protected $secondReceiptStatus;

    /**
     * @var int
     */
    protected $defaultTaxRate;

    /**
     * @var array
     */
    protected $taxRates;

    /**
     * @var bool
     */
    protected $isLog;

    /**
     * @var bool
     */
    protected $isTestMode;

    /**
     * @var int
     */
    protected $showInFooter;

    /**
     * @var bool
     */
    protected $useInstallmentsButton;

    /**
     * @var bool
     */
    protected $isEnableHoldMode;

    /**
     * @var int
     */
    protected $holdOrderStatus;

    /**
     * @var string
     */
    protected $paymentDescription;

    /**
     * @var int
     */
    protected $orderCanceledStatus;

    /**
     * @var bool
     */
    protected $addInstallmentsBlock;

    /**
     * @var int
     */
    protected $b2bSberbankEnabled;

    /**
     * @var int
     */
    protected $b2bSberbankPaymentPurpose;

    /**
     * @var int
     */
    protected $b2bSberbankDefaultTaxRate;

    /**
     * @var int
     */
    protected $b2bTaxRates;

    /**
     * @var int
     */
    protected $defaultTaxSystemCode;

    /**
     * @var string
     */
    protected $defaultPaymentMode;

    /**
     * @var string
     */
    protected $defaultPaymentSubject;

    /**
     * @var string
     */
    protected $defaultDeliveryPaymentMode;

    /**
     * @var string
     */
    protected $defaultDeliveryPaymentSubject;

    /**
     * @var string
     */
    protected $defaultVoucherPaymentMode;

    /**
     * @var string
     */
    protected $defaultVoucherPaymentSubject;

    /** @var string */
    protected $currency;

    /** @var string|bool */
    protected $currency_convert;

    /** @var string */
    protected $oauthToken;

    /** @var bool */
    protected $isTestShop;

    /** @var bool */
    protected $isFiscalizationEnabled;

    /** @var string */
    protected $oauthTokenExpiresIn;

    /** @var string */
    protected $oauthCmsState;

    /**
     * KassaModel constructor.
     *
     * @param \Config $config
     */
    public function __construct($config)
    {
        parent::__construct($config, self::PAYMENT_KASSA);

        $this->shopId                 = $this->getConfigValue('shop_id');
        $this->password               = $this->getConfigValue('password');
        $this->epl                    = $this->getConfigValue('payment_mode') !== 'shop';
        $this->useInstallmentsButton  = (bool)$this->getConfigValue('use_installments_button');
        $this->addInstallmentsBlock   = (bool)$this->getConfigValue('add_installments_block');
        $this->isEnableHoldMode       = (bool)$this->getConfigValue('enable_hold_mode');
        $this->holdOrderStatus        = $this->getConfigValue('hold_order_status');
        $this->paymentDescription     = $this->getConfigValue('payment_description');
        $this->orderCanceledStatus    = $this->getConfigValue('cancel_order_status');
        $this->oauthToken             = $this->getConfigValue('access_token');
        $this->oauthTokenExpiresIn    = $this->getConfigValue('token_expires_in');
        $this->oauthCmsState          = $this->getConfigValue('oauth_state');

        $this->isTestMode = false;
        if ($this->enabled && strncmp('test_', $this->password, 5) === 0) {
            $this->isTestMode = true;
        }

        $enabledPaymentMethods = array_merge(
            array(self::CUSTOM_PAYMENT_METHOD_WIDGET),
            PaymentMethodType::getEnabledValues()
        );

        $this->paymentMethods = array();
        foreach ($enabledPaymentMethods as $value) {
            $property = 'payment_method_'.$value;
            $enabled  = (bool)$this->getConfigValue($property);
            if (!in_array($value, self::$_disabledPaymentMethods) && (!$this->isTestMode || array_key_exists($value,
                        self::$_enabledTestMethods))
            ) {
                $this->paymentMethods[$value] = $enabled;
            }
        }

        $this->isSendReceipt        = (bool)$this->getConfigValue('send_receipt');
        $this->isSecondReceipt      = (bool)$this->getConfigValue('second_receipt_enable');
        $this->secondReceiptStatus  = (int)$this->getConfigValue('second_receipt_status');
        $this->defaultTaxRate       = (int)$this->getConfigValue('tax_rate_default');
        $this->defaultTaxSystemCode = (int)$this->getConfigValue('tax_system_default');
        $this->isLog                = (bool)$this->getConfigValue('debug_log');

        $this->taxRates = array();
        $tmp            = $this->getConfigValue('tax_rates');
        if (!empty($tmp)) {
            if (is_array($tmp)) {
                foreach ($tmp as $shopTaxRateId => $kassaTaxRateId) {
                    $this->taxRates[$shopTaxRateId] = $kassaTaxRateId;
                }
            }
        }

        $this->createOrderBeforeRedirect   = $this->getConfigValue('create_order_before_redirect');
        $this->clearCartAfterOrderCreation = $this->getConfigValue('clear_cart_before_redirect');

        $this->showInFooter = $this->getConfigValue('show_in_footer');

        $this->b2bSberbankEnabled            = $this->getConfigValue('b2b_sberbank_enabled');
        $this->b2bSberbankPaymentPurpose     = $this->getConfigValue('b2b_sberbank_payment_purpose');
        $this->b2bSberbankDefaultTaxRate     = $this->getConfigValue('b2b_tax_rate_default');
        $this->b2bTaxRates                   = $this->getConfigValue('b2b_tax_rates');
        $this->defaultPaymentMode            = $this->getConfigValue('default_payment_mode');
        $this->defaultPaymentSubject         = $this->getConfigValue('default_payment_subject');
        $this->defaultDeliveryPaymentMode    = $this->getConfigValue('default_delivery_payment_mode');
        $this->defaultDeliveryPaymentSubject = $this->getConfigValue('default_delivery_payment_subject');
        $this->defaultVoucherPaymentMode     = $this->getConfigValue('default_voucher_payment_mode');
        $this->defaultVoucherPaymentSubject  = $this->getConfigValue('default_voucher_payment_subject');

        $this->currency                      = $this->getConfigValue('currency');
        $this->currency_convert              = $this->getConfigValue('currency_convert');
    }

    /**
     * @return bool
     */
    public function isTestMode()
    {
        return $this->isTestMode;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function getEPL()
    {
        return $this->epl;
    }

    /**
     * @return bool
     */
    public function useInstallmentsButton()
    {
        return $this->useInstallmentsButton;
    }

    /**
     * @return bool
     */
    public function getAddInstallmentsBlock()
    {
        return $this->addInstallmentsBlock;
    }

    /**
     * @return bool
     */
    public function showInstallmentsBlock()
    {
        return $this->useInstallmentsButton() && $this->getAddInstallmentsBlock();
    }

    /**
     * @return array
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * @return array
     */
    public function getEnabledPaymentMethods()
    {
        $result = array();
        foreach ($this->paymentMethods as $method => $enabled) {
            if ($enabled) {
                $result[] = $method;
            }
        }

        return $result;
    }

    /**
     * @param $paymentMethod
     * @return bool
     */
    public function isPaymentMethodEnabled($paymentMethod)
    {
        return isset($this->paymentMethods[$paymentMethod]) && $this->paymentMethods[$paymentMethod];
    }

    /**
     * @return bool
     */
    public function isSendReceipt()
    {
        return $this->isSendReceipt;
    }

    /**
     * @return bool
     */
    public function isSecondReceipt()
    {
        return $this->isSecondReceipt;
    }

    /**
     * @return int
     */
    public function getSecondReceiptStatus()
    {
        return $this->secondReceiptStatus;
    }

    /**
     * @return array
     */
    public function getTaxRateList()
    {
        return array(1, 2, 3, 4, 5, 6);
    }

    /**
     * @return array
     */
    public function getTaxSystemCodeList()
    {
        return array(1, 2, 3, 4, 5, 6);
    }

    /**
     * @return array
     */
    public function getB2bRateList()
    {
        return array(VatDataType::UNTAXED, VatDataRate::RATE_7, VatDataRate::RATE_10, VatDataRate::RATE_18, VatDataRate::RATE_20);
    }

    /**
     * @return int
     */
    public function getDefaultTaxRate()
    {
        return $this->defaultTaxRate;
    }

    /**
     * @return int
     */
    public function getDefaultTaxSystemCode()
    {
        return $this->defaultTaxSystemCode;
    }

    /**
     * @param $shopTaxRateId
     * @return int|mixed
     */
    public function getTaxRateId($shopTaxRateId)
    {
        if (isset($this->taxRates[$shopTaxRateId])) {
            return $this->taxRates[$shopTaxRateId];
        }

        return $this->defaultTaxRate;
    }

    /**
     * @param $shopTaxRateId
     * @return int
     */
    public function getB2bTaxRateId($shopTaxRateId)
    {
        if (isset($this->b2bTaxRates[$shopTaxRateId])) {
            return $this->b2bTaxRates[$shopTaxRateId];
        }

        return $this->b2bSberbankDefaultTaxRate;
    }

    /**
     * @return array
     */
    public function getTaxRates()
    {
        return $this->taxRates;
    }

    /**
     * @return bool
     */
    public function getDebugLog()
    {
        return $this->isLog;
    }

    /**
     * @return int
     */
    public function getShowLinkInFooter()
    {
        return $this->showInFooter;
    }

    /**
     * @param array $templateData
     * @param $controller
     *
     * @return string
     */
    public function applyTemplateVariables($controller, &$templateData, $orderInfo)
    {
        $templateData['kassa']           = $this;
        $templateData['image_base_path'] = HTTPS_SERVER.'image/payment/yoomoney';
        $prefix                          = version_compare(VERSION, '2.3.0') >= 0 ? 'extension/' : '';
        $templateData['validate_url']    = $controller->url->link($prefix.'payment/yoomoney/create', '', true);
        $templateData['reset_token_url']    = $controller->url->link($prefix.'payment/yoomoney/resetToken', '', true);

        $templateData['amount']         = $orderInfo['total'];
        $templateData['comment']        = $orderInfo['comment'];
        $templateData['orderId']        = $orderInfo['order_id'];
        $templateData['customerNumber'] = trim($orderInfo['order_id'].' '.$orderInfo['email']);
        $templateData['orderText']      = $orderInfo['comment'];

        return 'payment/yoomoney/kassa_form';
    }

    /**
     * @param array $paymentMethods
     *
     * @return KassaModel
     */
    public function setPaymentMethods($paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnableHoldMode()
    {
        return $this->isEnableHoldMode;
    }

    /**
     * @return mixed
     */
    public function getHoldOrderStatusId()
    {
        return $this->holdOrderStatus;
    }

    /**
     * @param $paymentMethod
     * @return bool
     */
    public function getCaptureValue($paymentMethod)
    {
        $paymentMethodsForHold = array(
            '',
            PaymentMethodType::BANK_CARD,
            PaymentMethodType::YOO_MONEY,
            PaymentMethodType::GOOGLE_PAY,
            PaymentMethodType::APPLE_PAY,
            self::CUSTOM_PAYMENT_METHOD_WIDGET,
        );
        return !($this->isEnableHoldMode && in_array($paymentMethod, $paymentMethodsForHold));
    }

    /**
     * @return mixed
     */
    public function getPaymentDescription()
    {
        return $this->paymentDescription;
    }

    /**
     * @return mixed
     */
    public function getOrderCanceledStatus()
    {
        return $this->orderCanceledStatus;
    }

    /**
     * @return mixed
     */
    public function getB2bSberbankEnabled()
    {
        return $this->b2bSberbankEnabled == 'on';
    }

    /**
     * @return mixed
     */
    public function getB2bSberbankPaymentPurpose()
    {
        return $this->b2bSberbankPaymentPurpose;
    }

    /**
     * @return mixed
     */
    public function getB2bSberbankDefaultTaxRate()
    {
        return $this->b2bSberbankDefaultTaxRate;
    }

    /**
     * @return mixed
     */
    public function getB2bTaxRates()
    {
        return $this->b2bTaxRates;
    }

    /**
     * @return mixed
     */
    public function getDefaultPaymentMode()
    {
        return $this->defaultPaymentMode;
    }

    /**
     * @return mixed
     */
    public function getDefaultPaymentSubject()
    {
        return $this->defaultPaymentSubject;
    }

    /**
     * @return mixed
     */
    public function getDefaultDeliveryPaymentMode()
    {
        return $this->defaultDeliveryPaymentMode;
    }

    /**
     * @return mixed
     */
    public function getDefaultDeliveryPaymentSubject()
    {
        return $this->defaultDeliveryPaymentSubject;
    }

    /**
     * @return mixed
     */
    public function getDefaultVoucherPaymentMode()
    {
        return $this->defaultVoucherPaymentMode;
    }

    /**
     * @return mixed
     */
    public function getDefaultVoucherPaymentSubject()
    {
        return $this->defaultVoucherPaymentSubject;
    }

    /**
     * @return array
     */
    public function getPaymentModeEnum()
    {
        return array(
            PaymentMode::FULL_PREPAYMENT    => 'Полная предоплата ('.PaymentMode::FULL_PREPAYMENT.')',
            PaymentMode::PARTIAL_PREPAYMENT => 'Частичная предоплата ('.PaymentMode::PARTIAL_PREPAYMENT.')',
            PaymentMode::ADVANCE            => 'Аванс ('.PaymentMode::ADVANCE.')',
            PaymentMode::FULL_PAYMENT       => 'Полный расчет ('.PaymentMode::FULL_PAYMENT.')',
            PaymentMode::PARTIAL_PAYMENT    => 'Частичный расчет и кредит ('.PaymentMode::PARTIAL_PAYMENT.')',
            PaymentMode::CREDIT             => 'Кредит ('.PaymentMode::CREDIT.')',
            PaymentMode::CREDIT_PAYMENT     => 'Выплата по кредиту ('.PaymentMode::CREDIT_PAYMENT.')',
        );
    }

    /**
     * @return array
     */
    public function getPaymentSubjectEnum()
    {
        return array(
            PaymentSubject::COMMODITY             => 'Товар ('.PaymentSubject::COMMODITY.')',
            PaymentSubject::EXCISE                => 'Подакцизный товар ('.PaymentSubject::EXCISE.')',
            PaymentSubject::JOB                   => 'Работа ('.PaymentSubject::JOB.')',
            PaymentSubject::SERVICE               => 'Услуга ('.PaymentSubject::SERVICE.')',
            PaymentSubject::GAMBLING_BET          => 'Ставка в азартной игре ('.PaymentSubject::GAMBLING_BET.')',
            PaymentSubject::GAMBLING_PRIZE        => 'Выигрыш в азартной игре ('.PaymentSubject::GAMBLING_PRIZE.')',
            PaymentSubject::LOTTERY               => 'Лотерейный билет ('.PaymentSubject::LOTTERY.')',
            PaymentSubject::LOTTERY_PRIZE         => 'Выигрыш в лотерею ('.PaymentSubject::LOTTERY_PRIZE.')',
            PaymentSubject::INTELLECTUAL_ACTIVITY => 'Результаты интеллектуальной деятельности ('.PaymentSubject::INTELLECTUAL_ACTIVITY.')',
            PaymentSubject::PAYMENT               => 'Платеж ('.PaymentSubject::PAYMENT.')',
            PaymentSubject::AGENT_COMMISSION      => 'Агентское вознаграждение ('.PaymentSubject::AGENT_COMMISSION.')',
            PaymentSubject::COMPOSITE             => 'Несколько вариантов ('.PaymentSubject::COMPOSITE.')',
            PaymentSubject::ANOTHER               => 'Другое ('.PaymentSubject::ANOTHER.')',
        );
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency ?: CurrencyCode::RUB;
    }

    /**
     * @return bool
     */
    public function getCurrencyConvert()
    {
        return $this->currency_convert == 'on';
    }

    /**
     * @return string
     */
    public function getOauthToken()
    {
        return $this->oauthToken;
    }

    /**
     * @return bool
     */
    public function isTestShop()
    {
        return (bool)$this->isTestShop;
    }

    /**
     * @return bool
     */
    public function isFiscalisationEnabled()
    {
        return (bool)$this->isFiscalizationEnabled;
    }

    /**
     * @return mixed
     */
    public function getOauthTokenExpiresIn()
    {
        return $this->oauthTokenExpiresIn;
    }

    /**
     * @return mixed
     */
    public function getOauthCmsState()
    {
        return $this->oauthCmsState;
    }
}
