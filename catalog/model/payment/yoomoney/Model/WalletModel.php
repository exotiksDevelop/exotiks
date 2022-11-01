<?php

namespace YooMoneyModule\Model;

class WalletModel extends AbstractPaymentModel
{
    protected $accountId;
    protected $password;
    protected $testMode;

    /**
     * WalletModel constructor.
     * @param \Config $config
     */
    public function __construct($config)
    {
        parent::__construct($config, self::PAYMENT_WALLET);
        $this->accountId = $this->getConfigValue('account_id');
        $this->password = $this->getConfigValue('password');
        $this->testMode = $this->getConfigValue('test_mode') == '1';

        $this->createOrderBeforeRedirect = $this->getConfigValue('create_order_before_redirect');
        $this->clearCartAfterOrderCreation = $this->getConfigValue('clear_cart_before_redirect');
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function applyTemplateVariables($controller, &$templateData, $orderInfo)
    {
        $templateData['wallet'] = $this;
        $templateData['image_base_path'] = HTTPS_SERVER . 'image/payment/yoomoney';
        $prefix = version_compare(VERSION, '2.3.0') >= 0 ? 'extension/' : '';
        $templateData['validate_url'] = $controller->url->link($prefix.'payment/yoomoney/validate', '', true);

        $templateData['cmsname'] = 'opencart2';

        $templateData['successUrl'] = $controller->url->link('checkout/success', '', true);

        if ($controller->currency->has('RUB')) {
            $templateData['amount'] = sprintf('%.2f', $controller->currency->format($orderInfo['total'], 'RUB', '', false));
        } else {
            $templateData['amount'] = sprintf('%.2f', $controller->getModel()->convertFromCbrf($orderInfo, 'RUB'));
        }
        $templateData['amount'] = $controller->currency->format($orderInfo['total'], 'RUB', '', false);
        $templateData['comment'] = $orderInfo['comment'];
        $templateData['orderId'] = $orderInfo['order_id'];
        $templateData['customerNumber'] = trim($orderInfo['order_id'] . ' ' . $orderInfo['email']);
        $templateData['orderText'] = $orderInfo['comment'];
        $templateData['formcomment'] = '';
        $templateData['short_dest'] = '';

        $templateData['action'] = 'https://yoomoney.ru/quickpay/confirm.xml';

        return 'payment/yoomoney/wallet_form';
    }
}
