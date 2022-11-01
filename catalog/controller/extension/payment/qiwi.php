<?php

/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpIncludeInspection */

require_once realpath(DIR_SYSTEM . 'library/qiwi/load.php');

use Qiwi\Client;

/**
 * Class ControllerExtensionPaymentQiwi.
 *
 * @property-read Loader $load
 * @property-read Url $url
 * @property-read Language $language
 * @property-read Session $session
 * @property-read ModelCheckoutOrder $model_checkout_order
 * @property-read Config $config
 * @property-read Response $response
 * @property-read Log $log
 * @property-read Document $document
 * @property-read \Cart\Currency $currency
 * @property-read Qiwi\Catalog\Model $model_extension_payment_qiwi
 */
class ControllerExtensionPaymentQiwi extends Controller implements \Qiwi\Catalog\Controller
{
    /**
     * The errors messages.
     *
     * @var string[]
     */
    protected $error = [];

    /**
     * Event load checkout JS handler.
     *
     * @throws \Exception
     */
    public function eventLoadCheckoutJs()
    {
        $this->load->model('setting/setting');
        if ($this->config->get('payment_qiwi_popup')) {
            $this->document->addScript('https://oplata.qiwi.com/popup/v1.js');
        }
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function index()
    {
        $this->load->language('extension/payment/qiwi');
        return $this->load->view('extension/payment/qiwi', [
            'button_confirm' => $this->language->get('button_confirm'),
            'text_loading'   => $this->language->get('text_loading'),
            'confirm'        => $this->url->link('extension/payment/qiwi/confirm', '', true)
        ]);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function confirm()
    {
        $billId = null;
        $params = null;
        $bill   = null;
        $successUrl = $this->url->link('checkout/success', '', true);
        $this->load->language('extension/payment/qiwi');
        $this->load->model('extension/payment/qiwi');
        $this->load->model('setting/setting');
        $this->load->model('checkout/order');
        $order  = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $client = new Client($this->config->get('payment_qiwi_key_secret'));
        $params = [
            'amount'             => $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false),
            'currency'           => $this->session->data['currency'],
            'comment'            => $order['comment'],
            'expirationDateTime' => $client->getLifetimeByDay((int) $this->config->get('payment_qiwi_live_time')),
            'phone'              => $order['telephone'],
            'email'              => $order['email'],
            'account'            => $order['customer_id'],
            'successUrl'         => $successUrl,
            'customFields'       => [
                'themeCode'        => $this->config->get('payment_qiwi_theme_code')
            ],
        ];
        try {
            $billId = $client->generateId();
            $bill = $client->createBill($billId, $params);
            $this->model_extension_payment_qiwi->addBill($billId, $this->session->data['order_id']);
            $this->model_checkout_order->addOrderHistory(
                $this->session->data['order_id'],
                $this->config->get('payment_qiwi_waiting_status_id'),
                sprintf($this->language->get('create_bill'), $bill['payUrl']),
                true
            );
        } catch (Exception $e) {
            $this->error['warning'] = $this->language->get('error_api');
        }

        if ($this->config->get('payment_qiwi_debug')) {
            $this->log->write('QIWI create bill: ' . json_encode([
                'billId' => $billId,
                'params' => $params,
                'result' => $bill,
            ]));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'error'      => isset($this->error['warning']) ? $this->error['warning'] : null,
            'payUrl'     => isset($bill['payUrl']) ? $bill['payUrl'] : null,
            'successUrl' => $successUrl,
        ]));
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function notification()
    {
        $sign = null;
        $body = null;
        $this->load->language('extension/payment/qiwi');
        $this->load->model('extension/payment/qiwi');
        $this->load->model('setting/setting');
        $this->load->model('checkout/order');
        $client = new Client();
        try {
            $sign = array_key_exists('HTTP_X_API_SIGNATURE_SHA256', $_SERVER) ? stripslashes($_SERVER['HTTP_X_API_SIGNATURE_SHA256']) : '';
            $body = file_get_contents('php://input');
            $notice = json_decode($body, true);
            if ($client->checkNotificationSignature($sign, $notice, $this->config->get('payment_qiwi_key_secret'))) {
                $billId  = $notice['bill']['billId'];
                $orderId = $this->model_extension_payment_qiwi->getOrder($billId);
                $order = $this->model_checkout_order->getOrder($orderId);

                // Process status.
                switch ( $notice['bill']['status']['value'] ) {
                    case 'WAITING':
                        if ($order['order_status_id'] != $this->config->get('payment_qiwi_waiting_status_id')) {
                            $this->model_checkout_order->addOrderHistory(
                                $orderId,
                                $this->config->get('payment_qiwi_waiting_status_id'),
                                $this->language->get('waiting_bill'),
                                true
                            );
                        }
                        break;
                    case 'PAID':
                        if ($order['order_status_id'] != $this->config->get('payment_qiwi_paid_status_id')) {
                            $this->model_checkout_order->addOrderHistory(
                                $orderId,
                                $this->config->get('payment_qiwi_paid_status_id'),
                                $this->language->get('paid_bill'),
                                true
                            );
                        }
                        break;
                    case 'REJECTED':
                        if ($order['order_status_id'] != $this->config->get('payment_qiwi_rejected_status_id')) {
                            $this->model_checkout_order->addOrderHistory(
                                $orderId,
                                $this->config->get('payment_qiwi_rejected_status_id'),
                                $this->language->get('rejected_bill'),
                                true
                            );
                        }
                        break;
                    case 'EXPIRED':
                        if ($order['order_status_id'] != $this->config->get('payment_qiwi_expired_status_id')) {
                            $this->model_checkout_order->addOrderHistory(
                                $orderId,
                                $this->config->get('payment_qiwi_expired_status_id'),
                                $this->language->get('expired_bill'),
                                true
                            );
                        }
                        break;
                    case 'PARTIAL':
                        if ($order['order_status_id'] != $this->config->get('payment_qiwi_partial_status_id')) {
                            $this->model_checkout_order->addOrderHistory(
                                $orderId,
                                $this->config->get('payment_qiwi_partial_status_id'),
                                $this->language->get('partial_bill'),
                                true
                            );
                        }
                        break;
                    case 'FULL':
                        if ($order['order_status_id'] != $this->config->get('payment_qiwi_full_status_id')) {
                            $this->model_checkout_order->addOrderHistory(
                                $orderId,
                                $this->config->get('payment_qiwi_full_status_id'),
                                $this->language->get('full_bill'),
                                true
                            );
                        }
                        break;
                    default:
                        throw new Exception('Unsupported status ' . $notice['bill']['status']['value'] . '.');
                }
            } else {
                throw new Exception('Check notification signature fail.');
            }
        } catch (Exception $exception) {
            $this->error['warning'] = $exception->getMessage();
        }

        if ($this->config->get('payment_qiwi_debug')) {
            $message = 'QIWI notification' . PHP_EOL;
            $message .= '- signature: ' . $sign . PHP_EOL;
            $message .= '- body: ' . $body . PHP_EOL;
            if (isset($this->error['warning'])) {
                $message .= '- error: ' . $this->error['warning'];
            } else {
                $message .= '- success';
            }
            $this->log->write($message);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => count($this->error)]));
    }
}
