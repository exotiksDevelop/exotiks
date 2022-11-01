<?php

/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpIncludeInspection */
/** @noinspection PhpUndefinedConstants */

require_once realpath(DIR_SYSTEM . 'library/qiwi/load.php');

use Qiwi\Client;

/**
 * Class ControllerExtensionPaymentQiwi.
 *
 * @property-read Loader $load
 * @property-read Cart\User $user
 * @property-read Document $document
 * @property-read Request $request
 * @property-read Response $response
 * @property-read Session $session
 * @property-read Url $url
 * @property-read Language $language
 * @property-read Log $log
 * @property-read Config $config
 * @property-read ModelSettingSetting $model_setting_setting
 * @property-read ModelLocalisationOrderStatus $model_localisation_order_status
 * @property-read ModelLocalisationGeoZone $model_localisation_geo_zone
 * @property-read ModelSaleOrder $model_sale_order
 * @property-read Qiwi\Admin\Model $model_extension_payment_qiwi
 */
class ControllerExtensionPaymentQiwi extends Controller implements \Qiwi\Admin\Controller
{
    /**
     * @var string[] The error list.
     */
    private $error = [];

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function index()
    {
        $this->load->language('extension/payment/qiwi');
        $this->load->model('setting/setting');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission()) {
            if ($this->validateRequire()) {
                $this->model_setting_setting->editSetting('payment_qiwi', $this->request->post);
                $this->session->data['success'] = $this->language->get('text_success');
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=payment', true));
            } else {
                $this->error['warning'] = $this->language->get('error_required');
            }
        }

        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->response->setOutput($this->load->view('extension/payment/qiwi', [
            // Text
            'button_save'         => $this->language->get('button_save'),
            'button_cancel'       => $this->language->get('button_cancel'),
            'heading_title'       => $this->language->get('heading_title'),
            'text_edit'           => $this->language->get('text_edit'),
            'text_description'    => $this->language->get('text_description'),
            'text_all_zones'      => $this->language->get('text_all_zones'),
            'text_enabled'        => $this->language->get('text_enabled'),
            'text_disabled'       => $this->language->get('text_disabled'),
            'text_popup_enabled'  => $this->language->get('text_popup_enabled'),
            'text_popup_disabled' => $this->language->get('text_popup_disabled'),

            // Text Tab General
            'help_status'       => $this->language->get('help_status'),
            'entry_status'      => $this->language->get('entry_status'),
            'help_title'        => $this->language->get('help_title'),
            'entry_title'       => $this->language->get('entry_title'),
            'help_description'  => $this->language->get('help_description'),
            'entry_description' => $this->language->get('entry_description'),
            'help_sort_order'   => $this->language->get('help_sort_order'),
            'entry_sort_order'  => $this->language->get('entry_sort_order'),
            'help_total'        => $this->language->get('help_total'),
            'entry_total'       => $this->language->get('entry_total'),
            'help_geo_zone'     => $this->language->get('help_geo_zone'),
            'entry_geo_zone'    => $this->language->get('entry_geo_zone'),

            // Text Tab Qiwi
            'help_notification'   => $this->language->get('help_notification'),
            'entry_notification'  => $this->language->get('entry_notification'),
            'help_key_secret'     => $this->language->get('help_key_secret'),
            'entry_key_secret'    => $this->language->get('entry_key_secret'),
            'help_key_public'     => $this->language->get('help_key_public'),
            'entry_key_public'    => $this->language->get('entry_key_public'),
            'help_theme_code'     => $this->language->get('help_theme_code'),
            'entry_theme_code'    => $this->language->get('entry_theme_code'),
            'help_live_time'      => $this->language->get('help_live_time'),
            'entry_live_time'     => $this->language->get('entry_live_time'),
            'help_popup'          => $this->language->get('help_popup'),
            'entry_popup'         => $this->language->get('entry_popup'),
            'help_debug'          => $this->language->get('help_debug'),
            'entry_debug'         => $this->language->get('entry_debug'),

            // Text Tab Order Status
            'help_waiting_status'   => $this->language->get('help_waiting_status'),
            'entry_waiting_status'  => $this->language->get('entry_waiting_status'),
            'help_paid_status'      => $this->language->get('help_paid_status'),
            'entry_paid_status'     => $this->language->get('entry_paid_status'),
            'help_rejected_status'  => $this->language->get('help_rejected_status'),
            'entry_rejected_status' => $this->language->get('entry_rejected_status'),
            'help_expired_status'   => $this->language->get('help_expired_status'),
            'entry_expired_status'  => $this->language->get('entry_expired_status'),
            'help_partial_status'   => $this->language->get('help_partial_status'),
            'entry_partial_status'  => $this->language->get('entry_partial_status'),
            'help_full_status'      => $this->language->get('help_full_status'),
            'entry_full_status'     => $this->language->get('entry_full_status'),

            // Options
            'error'          => $this->error,
            'order_statuses' => $this->model_localisation_order_status->getOrderStatuses(),
            'geo_zones'      => $this->model_localisation_geo_zone->getGeoZones(),
            'notification'   => defined('HTTPS_CATALOG') ? HTTPS_CATALOG . 'index.php?route=extension/payment/qiwi/notification': '',

            // Values
            'payment_qiwi_status'             => $this->getValue('payment_qiwi_status'),
            'payment_qiwi_title'              => $this->getValue('payment_qiwi_title', $this->language->get('heading_title')),
            'payment_qiwi_description'        => $this->getValue('payment_qiwi_description', $this->language->get('text_supports')),
            'payment_qiwi_sort_order'         => $this->getValue('payment_qiwi_sort_order'),
            'payment_qiwi_total'              => $this->getValue('payment_qiwi_total'),
            'payment_qiwi_geo_zone_id'        => $this->getValue('payment_qiwi_geo_zone_id'),
            'payment_qiwi_key_secret'         => $this->getValue('payment_qiwi_key_secret'),
            'payment_qiwi_key_public'         => $this->getValue('payment_qiwi_key_public'),
            'payment_qiwi_theme_code'         => $this->getValue('payment_qiwi_theme_code'),
            'payment_qiwi_live_time'          => $this->getValue('payment_qiwi_live_time', '40'),
            'payment_qiwi_popup'              => $this->getValue('payment_qiwi_popup'),
            'payment_qiwi_debug'              => $this->getValue('payment_qiwi_debug'),
            'payment_qiwi_waiting_status_id'  => $this->getValue('payment_qiwi_waiting_status_id'),
            'payment_qiwi_paid_status_id'     => $this->getValue('payment_qiwi_paid_status_id'),
            'payment_qiwi_rejected_status_id' => $this->getValue('payment_qiwi_rejected_status_id'),
            'payment_qiwi_expired_status_id'  => $this->getValue('payment_qiwi_expired_status_id'),
            'payment_qiwi_partial_status_id'  => $this->getValue('payment_qiwi_partial_status_id'),
            'payment_qiwi_full_status_id'     => $this->getValue('payment_qiwi_full_status_id'),

            // breadcrumbs
            'breadcrumbs' => [
                [
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
                ],
                [
                    'text' => $this->language->get('text_extension'),
                    'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
                ],
                [
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/payment/qiwi', 'user_token=' . $this->session->data['user_token'], true),
                ]
            ],

            // actions
            'cancel' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
            'action' => $this->url->link('extension/payment/qiwi', 'user_token=' . $this->session->data['user_token'], true),

            // views
            'header'      => $this->load->controller('common/header'),
            'column_left' => $this->load->controller('common/column_left'),
            'footer'      => $this->load->controller('common/footer'),
        ]));
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function order()
    {
        $this->load->language('extension/payment/qiwi');
        $this->load->model('setting/setting');
        $this->load->model('sale/order');
        $this->load->model('extension/payment/qiwi');
        $bills = [];
        $refundsIDs = [];
        $billsIDs = $this->model_extension_payment_qiwi->getBills($this->request->get['order_id']);
        foreach ($billsIDs as $billId) {
            $refundsIDs[$billId] = $this->model_extension_payment_qiwi->getRefunds($billId);
        }
        $client = new Client($this->config->get('payment_qiwi_key_secret'));
        foreach ($billsIDs as $billId) {
            try {
                $bills[$billId] = $client->getBillInfo($billId);
                $bills[$billId]['refunds'] = [];
                foreach ($refundsIDs[$billId] as $refundID) {
                    try {
                        $bills[$billId]['refunds'][$refundID] = $client->getRefundInfo($billId, $refundID);
                    } catch (Exception $exception) {
                        $bills[$billId]['refunds'][$refundID] = [
                            'refundId' => $refundID,
                            'error'  => $exception->getMessage()
                        ];
                    }
                }
            } catch (Exception $exception) {
                $bills[$billId] = [
                    'billId' => $billId,
                    'error'  => $exception->getMessage()
                ];
            }
        }

        return $this->load->view('extension/payment/qiwi_order', [
            // Texts
            'text_bill_title'  => $this->language->get('text_bill_title'),
            'text_bill_amount' => $this->language->get('text_bill_amount'),
            'text_bill_status' => $this->language->get('text_bill_status'),
            'text_bill_date'   => $this->language->get('text_bill_date'),
            'text_bill_action' => $this->language->get('text_bill_action'),
            'text_reject'      => $this->language->get('text_reject'),
            'text_refund'      => $this->language->get('text_refund'),

            // Refund
            'entry_refund_amount' => $this->language->get('entry_refund_amount'),

            // Options
            'user_token' => $this->request->get['user_token'],
            'error'      => $this->error,
            'bills'      => $bills,
            'order_id'   => $this->request->get['order_id'],
        ]);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function reject()
    {
        try {
            if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateOrderPermission()) {
                $this->load->language('extension/payment/qiwi');
                $this->load->model('setting/setting');
                $this->load->model('extension/payment/qiwi');
                $client = new Client($this->config->get('payment_qiwi_key_secret'));
                $billId = $this->request->post['bill_id'];
                $orderId = $this->model_extension_payment_qiwi->getOrder($billId);
                if ($this->config->get('payment_qiwi_debug')) {
                    $this->log->write('QIWI reject invoice '.$billId);
                }

                $client->cancelBill($billId);
                $this->session->data['success'] = $this->language->get('text_reject_success');
            }
        } catch (Exception $exception) {
            $this->error['warning'] = $exception->getMessage();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'redirect' => isset($orderId)
                ? $this->url->link('sale/order/info', 'user_token='.$this->session->data['user_token'].'&order_id='.$orderId, true)
                : $this->url->link('sale/order', 'user_token='.$this->session->data['user_token'], true),
            'error' => empty($this->error) ? null : $this->error,
        ]));
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function refund()
    {
        try {
            if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateOrderPermission()) {
                $this->load->language('extension/payment/qiwi');
                $this->load->model('setting/setting');
                $this->load->model('sale/order');
                $this->load->model('extension/payment/qiwi');
                $client   = new Client($this->config->get('payment_qiwi_key_secret'));
                $billId   = $this->request->post['bill_id'];
                $amount   = $this->request->post['amount'];
                $orderId  = $this->model_extension_payment_qiwi->getOrder($billId);
                $refundId = $client->generateId();
                $order    = $this->model_sale_order->getOrder($orderId);
                if ($this->config->get('payment_qiwi_debug')) {
                    $this->log->write('QIWI invoice ' . $billId . ' refund ' . $refundId . ' on ' . $amount . ' ' . $order['currency_code']);
                }

                $client->refund($billId, $refundId, $amount, $order['currency_code']);
                $this->model_extension_payment_qiwi->addRefund($billId, $refundId, $orderId);
                $this->session->data['success'] = $this->language->get('text_reject_success');
            }
        } catch (Exception $exception) {
            $this->error['warning'] = $exception->getMessage();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'redirect' => isset($orderId)
                ? $this->url->link('sale/order/info', 'user_token='.$this->session->data['user_token'].'&order_id='.$orderId, true)
                : $this->url->link('sale/order', 'user_token='.$this->session->data['user_token'], true),
            'error' => empty($this->error) ? null : $this->error,
        ]));
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function install()
    {
        $this->load->model('extension/payment/qiwi');
        $this->model_extension_payment_qiwi->install();
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function uninstall()
    {
        $this->load->model('extension/payment/qiwi');
        $this->model_extension_payment_qiwi->uninstall();
    }

    /**
     * Validate permission.
     *
     * @return bool
     */
    protected function validateOrderPermission()
    {
        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $this->error['warning'] = $this->language->get('error_order_permission');
        }

        return !$this->error;
    }

    /**
     * Validate permission.
     *
     * @return bool
     */
    protected function validatePermission()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/qiwi')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    /**
     * Validate required fields.
     *
     * @return bool
     */
    protected function validateRequire()
    {
        if (empty($this->request->post['payment_qiwi_title'])) {
            $this->error['title'] = $this->language->get('error_required_field');
        }

        if (empty($this->request->post['payment_qiwi_key_secret'])) {
            $this->error['key_secret'] = $this->language->get('error_required_field');
        }

        if (empty($this->request->post['payment_qiwi_key_public'])) {
            $this->error['key_public'] = $this->language->get('error_required_field');
        }

        return !$this->error;
    }

    /**
     * Get value.
     *
     * @param string $name The value name.
     * @param string $default The default value.
     *
     * @return string
     */
    protected function getValue($name, $default = '') {
        $value = isset($this->request->post[$name]) ? $this->request->post[$name] : $this->config->get($name);

        return isset($value) ? $value : $default;
    }
}
