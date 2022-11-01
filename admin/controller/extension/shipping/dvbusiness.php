<?php

use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\ApiClient\Response\OrderResponseModel;
use DvBusiness\DostavistaOrders\DostavistaOrder;
use DvBusiness\DostavistaOrders\DostavistaOrderManager;
use DvBusiness\DostavistaAuth\DostavistaAuthManager;
use DvBusiness\DvOptions;
use DvBusiness\ModuleConfig\ModuleConfig;
use DvBusiness\ModuleMetric\ModuleMetricManager;
use DvBusiness\OpenCart\DostavistaOpenCartOrder;
use DvBusiness\OpenCart\OrderManager;
use DvBusiness\OpenCart\PaymentMethods;
use DvBusiness\OrderForm\OrderCalculationResultResponseModel;
use DvBusiness\OrderForm\OrderForm;
use DvBusiness\OrderForm\OrderFormProcessor;
use DvBusiness\Warehouses\Warehouse;
use DvBusiness\Warehouses\WarehouseManager;
use DvBusiness\WizardResult\WizardResultManager;
use DvBusiness\Updater\UpdateManager;
use DvBusiness\BankCard\BankCardsManager;
use DvBusiness\DostavistaAuth\DostavistaApiTokenVerifier;
use DvBusiness\DostavistaAuth\DostavistaClientManager;
use DvBusiness\Enums\PaymentMethodEnum;

class ControllerExtensionShippingDvBusiness extends Controller
{
    /** @var DvOptions|null */
    private $dvOptions;

    /**
     * @return Config (Proxy-объект)
     */
    public function getConfig()
    {
        return $this->config;
    }

    private function loadCore(): DvOptions
    {
        $this->load->model('setting/setting');
        $this->load->language('extension/shipping/dvbusiness');
        $this->load->library('dvbusiness/autoloader');

        $updater = new UpdateManager(
            $this->model_setting_setting,
            str_replace('/admin', '', $this->url->link('extension/shipping/dvbusiness/apiCallbackHandler'))
        );
        $updater->update();

        if ($this->dvOptions === null) {
            $this->dvOptions = new DvOptions($this->model_setting_setting);
        }

        return $this->dvOptions;
    }

    private function installEventsIfNotDone()
    {
        $dvOptions = $this->loadCore();

        $events = [
            'shipping_dvbusiness_before_header' => [
                'catalog/controller/common/header/before', 'event/dvbusiness/beforeHeader',
            ],
            'shipping_dvbusiness_after_order_creation' => [
                'catalog/model/checkout/order/addOrder/after', 'event/dvbusiness/afterOrderCreation',
            ],
        ];

        $isNewInstall = false;

        $this->load->model('setting/event');
        foreach ($events as $code => $data) {
            if (!$this->model_setting_event->getEventByCode($code)) {
                $isNewInstall = true;
                $this->model_setting_event->addEvent($code, $data[0], $data[1]);
            }
        }

        if ($isNewInstall) {
            $moduleConfig = new ModuleConfig();
            $moduleMetricManager = new ModuleMetricManager(
                $this->db,
                $moduleConfig->getDvCmsModuleApiProdUrl(),
                $dvOptions->getApiUrl(),
                $dvOptions->getAuthToken()
            );
            $moduleMetricManager->install();
        }
    }

    public function index()
    {
        $dvOptions = $this->loadCore();

        $this->installEventsIfNotDone();

        $moduleConfig = new ModuleConfig();

        $filteredSettings = [
            'shipping_dvbusiness_default_pickup_warehouse_id'         => $dvOptions->getDefaultPickupWarehouseId(),
            'shipping_dvbusiness_default_order_weight_kg'             => $dvOptions->getDefaultOrderWeightKg(),
            'shipping_dvbusiness_dostavista_payment_markup_amount'    => $dvOptions->getDostavistaPaymentMarkupAmount(),
            'shipping_dvbusiness_dostavista_payment_discount_amount'  => $dvOptions->getDostavistaPaymentDiscountAmount(),
            'shipping_dvbusiness_fix_order_payment_amount'            => $dvOptions->getFixOrderPaymentAmount(),
            'shipping_dvbusiness_free_delivery_opencart_order_sum'    => $dvOptions->getFreeDeliveryOpencartOrderSum(),
            'shipping_dvbusiness_default_vehicle_type_id'             => $dvOptions->getDefaultVehicleTypeId(),
            'shipping_dvbusiness_insurance_enabled'                   => $dvOptions->isInsuranceEnabled(),
            'shipping_dvbusiness_buyout_enabled'                      => $dvOptions->isBuyoutEnabled(),
            'shipping_dvbusiness_matter_weight_prefix_enabled'        => $dvOptions->isMatterWeightPrefixEnabled(),
            'shipping_dvbusiness_contact_person_notification_enabled' => $dvOptions->isContactPersonNotificationEnabled(),
            'shipping_dvbusiness_delivery_point_note_prefix'          => $dvOptions->getDeliveryPointNotePrefix(),

            'shipping_dvbusiness_opencart_cash_payment_code' => $dvOptions->getOpenCartCashPaymentCode(),

            'shipping_dvbusiness_cms_module_api_callback_secret_key'        => $dvOptions->getApiCallbackSecretKey(),
            'shipping_dvbusiness_integration_order_status_available'        => $dvOptions->getIntegrationOrderStatusAvailable(),
            'shipping_dvbusiness_integration_order_status_active'           => $dvOptions->getIntegrationOrderStatusActive(),
            'shipping_dvbusiness_integration_order_status_completed'        => $dvOptions->getIntegrationOrderStatusCompleted(),
            'shipping_dvbusiness_integration_order_status_canceled'         => $dvOptions->getIntegrationOrderStatusCanceled(),
            'shipping_dvbusiness_integration_order_status_failed'           => $dvOptions->getIntegrationOrderStatusFailed(),
            'shipping_dvbusiness_integration_order_status_draft'            => $dvOptions->getIntegrationOrderStatusDraft(),
            'shipping_dvbusiness_integration_order_status_courier_assigned' => $dvOptions->getIntegrationOrderStatusCourierAssigned(),
            'shipping_dvbusiness_integration_order_status_parcel_picked_up' => $dvOptions->getIntegrationOrderStatusParcelPickedUp(),
            'shipping_dvbusiness_integration_order_status_courier_departed' => $dvOptions->getIntegrationOrderStatusCourierDeparted(),
            'shipping_dvbusiness_integration_order_status_courier_arrived'  => $dvOptions->getIntegrationOrderStatusCourierArrived(),
            'shipping_dvbusiness_integration_order_status_delayed'          => $dvOptions->getIntegrationOrderStatusDelayed(),
            'shipping_dvbusiness_default_payment_card_id'                   => $dvOptions->getDefaultPaymentCardId(),
            'shipping_dvbusiness_default_payment_type'                      => $dvOptions->getDefaultPaymentType(),
            'shipping_dvbusiness_wizard_last_finished_step'                 => $dvOptions->getWizardLastFinishedStep(),
        ];

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $postData = $this->request->post;

            $postIsApiTest = $postData['shipping_dvbusiness_is_api_test_server'] ?? false;
            $postData['shipping_dvbusiness_wizard_last_finished_step']      = $dvOptions->getWizardLastFinishedStep();
            $postData['shipping_dvbusiness_cms_module_api_prod_auth_token'] = $postIsApiTest ? $dvOptions->getProdAuthToken() : $postData['shipping_dvbusiness_auth_token'];
            $postData['shipping_dvbusiness_cms_module_api_test_auth_token'] = $postIsApiTest ? $postData['shipping_dvbusiness_auth_token'] : $dvOptions->getTestAuthToken();

            $dvOptions->updateSettings($postData);
            $this->session->data['success'] = $this->language->get('settings_success_message');
            $moduleMetricManager = new ModuleMetricManager(
                $this->db,
                $moduleConfig->getDvCmsModuleApiProdUrl(),
                $dvOptions->getApiUrl(),
                $dvOptions->getAuthToken()
            );

            if (
                $postData['shipping_dvbusiness_auth_token']
                && $postData['shipping_dvbusiness_auth_token'] !== $dvOptions->getAuthToken()
            ) {
                $moduleMetricManager->tokenInstall();
            }

            if (
                $postData['shipping_dvbusiness_cms_module_api_callback_secret_key']
                && $postData['shipping_dvbusiness_cms_module_api_callback_secret_key'] !== $dvOptions->getApiCallbackSecretKey()
            ) {
                $moduleMetricManager->callbackKeyInstall();
            }

            if ($postData['shipping_dvbusiness_status'] != $this->getConfig()->get('shipping_dvbusiness_status')) {
                if ($postData['shipping_dvbusiness_status']) {
                    $moduleMetricManager->deliveryInstall();
                } else {
                    $moduleMetricManager->deliveryUninstall();
                }
            }

            $this->response->redirect(
                $this->url->link(
                    'extension/shipping/dvbusiness',
                    http_build_query(['user_token' => $this->session->data['user_token']]),
                    true
                )
            );
        } else {
            if (!$this->isWizardFinished()) {
                $this->response->redirect(
                    $this->url->link(
                        'extension/shipping/dvbusiness/wizard',
                        http_build_query(['user_token' => $this->session->data['user_token']]),
                        true
                    )
                );
            }
        }

        $warehouseManager = new WarehouseManager($this->db);
        $warehouses = $warehouseManager->getList();
        $warehousesEnum = [];
        foreach ($warehouses as $warehouse) {
            $warehousesEnum[$warehouse->id] = $warehouse->name;
        }

        $this->document->addScript('view/javascript/dvbusiness/frontendHttpClient.js');
        $this->document->addScript('view/javascript/dvbusiness/licode.preloader.js');
        $this->document->addScript('view/javascript/dvbusiness/index.js');

        $data = [
            'heading' => $this->language->get('dvbusiness_head'),
        ];

        $data += $filteredSettings;
        $data += $this->load->language('extension/shipping/dvbusiness');
        $data += $this->getBreadCrumbs();

        // Ключевые поля службы доставки, т.к. они берутся из конфига
        $data['shipping_dvbusiness_status'] = isset($this->request->post['shipping_dvbusiness_status'])
            ? $this->request->post['shipping_dvbusiness_status']
            : $this->getConfig()->get('shipping_dvbusiness_status');

        if (isset($this->request->post['shipping_dvbusiness_sort_order'])) {
            $data['shipping_dvbusiness_sort_order'] = $this->request->post['shipping_dvbusiness_sort_order'];
        } else {
            $data['shipping_dvbusiness_sort_order'] = $this->getConfig()->get('shipping_dvbusiness_sort_order');
            if (empty($data['shipping_dvbusiness_sort_order'])) {
                $data['shipping_dvbusiness_sort_order'] = 1;
            }
        }

        if (isset($this->request->post['shipping_dvbusiness_delivery_title'])) {
            $data['shipping_dvbusiness_delivery_title'] = $this->request->post['shipping_dvbusiness_delivery_title'];
        } else {
            $data['shipping_dvbusiness_delivery_title'] = $this->dvOptions->getDeliveryServiceTitle();
            if (empty($data['shipping_dvbusiness_delivery_title'])) {
                $data['shipping_dvbusiness_delivery_title'] = $this->language->get('dvbusiness_tab_delivery_default_title');
            }
        }

        if (isset($this->request->post['shipping_dvbusiness_delivery_description'])) {
            $data['shipping_dvbusiness_delivery_description'] = $this->request->post['shipping_dvbusiness_delivery_description'];
        } else {
            $data['shipping_dvbusiness_delivery_description'] = $this->dvOptions->getDeliveryServiceDescription();
            if (empty($data['shipping_dvbusiness_delivery_description'])) {
                $data['shipping_dvbusiness_delivery_description'] = $this->language->get('dvbusiness_tab_delivery_default_description');
            }
        }

        $data['api_callback_url'] = str_replace('/admin', '', $this->url->link('extension/shipping/dvbusiness/apiCallbackHandler'));

        $this->load->model('setting/extension');
        $paymentMethodsEnum = (new PaymentMethods($this->model_setting_extension))->getEnum();

        /** @var ModelLocalisationOrderStatus $modelLocalisationOrderStatus */
        $this->load->model('localisation/order_status');
        $modelLocalisationOrderStatus = $this->model_localisation_order_status;

        $data['vehicle_types_enum']   = $this->getVehicleTypeEnum();
        $data['required_time_enum']   = $this->getRequiredTimeEnum();
        $data['payment_methods_enum'] = $paymentMethodsEnum;
        $data['warehouses_enum']      = $warehousesEnum;
        $data['order_statuses']       = $modelLocalisationOrderStatus->getOrderStatuses();
        $data['payment_types']        = $this->getPaymentTypes();
        $data['bank_cards']           = $this->getPaymentBankCards();
        $data['bank_card_payment_types'] = [PaymentMethodEnum::PAYMENT_METHOD_BANK, PaymentMethodEnum::PAYMENT_METHOD_QIWI];

        $data['action'] = $this->url->link('extension/shipping/dvbusiness', http_build_query(['user_token' => $this->session->data['user_token']]), true);
        $data['cancel'] = $this->url->link('marketplace/extension', http_build_query(['user_token' => $this->session->data['user_token'], 'type' => 'shipping']), true);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $data['shipping_dvbusiness_is_api_test_server']             = $dvOptions->getIsApiTestServer();
        $data['shipping_dvbusiness_cms_module_api_test_auth_token'] = $dvOptions->getTestAuthToken();
        $data['shipping_dvbusiness_cms_module_api_prod_auth_token'] = $dvOptions->getProdAuthToken();

        // Показывать ли способ доставки
        $allowedPaymentsMethod = DostavistaClientManager::getAllowedPaymentTypes($dvOptions);
        $data['is_new_card_link_enabled'] = !in_array(PaymentMethodEnum::PAYMENT_METHOD_NON_CASH, $allowedPaymentsMethod) && $moduleConfig->getCountry() === 'ru';

        $this->response->setOutput($this->load->view('extension/shipping/dvbusiness', $data));
    }

    /**
     * Список складов
     */
    public function warehouses()
    {
        $this->loadCore();

        if (!$this->isWizardFinished()) {
            $this->response->redirect(
                $this->url->link(
                    'extension/shipping/dvbusiness/wizard',
                    http_build_query(['user_token' => $this->session->data['user_token']]),
                    true
                )
            );
        }

        $warehouseManager = new WarehouseManager($this->db);
        $warehouses = $warehouseManager->getList();
        $warehousesData = [];
        foreach ($warehouses as $warehouse) {
            $warehousesData[] = [
                'id'               => $warehouse->id,
                'name'             => $warehouse->name,
                'city'             => $warehouse->city,
                'address'          => $warehouse->address,
                'work_start_time'  => $warehouse->workStartTime,
                'work_finish_time' => $warehouse->workFinishTime,
                'contact_name'     => $warehouse->contactName,
                'contact_phone'    => $warehouse->contactPhone,
                'note'             => $warehouse->note,
            ];
        }

        $this->document->setTitle($this->language->get('dvbusiness_warehouses_head') . ' / ' . $this->language->get('heading_title'));

        $this->document->addScript('view/javascript/dvbusiness/frontendHttpClient.js');
        $this->document->addScript('view/javascript/dvbusiness/licode.preloader.js');
        $this->document->addScript('view/javascript/dvbusiness/warehouses.js');

        $data = [
            'heading' => $this->language->get('dvbusiness_warehouses_head'),
        ];

        $data += $this->load->language('extension/shipping/dvbusiness');
        $data += $this->getBreadCrumbs();

        $data['warehouse_form_action'] = $this->url->link('extension/shipping/dvbusiness/warehouseForm', 'user_token=' . $this->session->data['user_token'], true);
        $data['warehouses']            = $warehousesData;
        $data['header']                = $this->load->controller('common/header');
        $data['column_left']           = $this->load->controller('common/column_left');
        $data['footer']                = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/dvbusiness_warehouses', $data));
    }

    /**
     * Форма создания/редактирования склада
     */
    public function warehouseForm()
    {
        $this->loadCore();

        $id = $this->request->get['id'] ?? null;

        $warehouseManager = new WarehouseManager($this->db);
        if ($id) {
            $warehouse = $warehouseManager->getById($id);
            if (!$warehouse) {
                return new Action('error/not_found');
            }
        } else {
            $warehouse = new Warehouse();
        }

        $warehouseData = [
            'id'               => $warehouse->id,
            'name'             => $warehouse->name,
            'city'             => $warehouse->city,
            'address'          => $warehouse->address,
            'work_start_time'  => $warehouse->workStartTime,
            'work_finish_time' => $warehouse->workFinishTime,
            'contact_name'     => $warehouse->contactName,
            'contact_phone'    => $warehouse->contactPhone,
            'note'             => $warehouse->note,
            'workdays'         => $warehouse->getWorkdays(),
        ];

        $this->document->setTitle($this->language->get('dvbusiness_warehouse_form_head') . ' / ' . $this->language->get('heading_title'));

        $this->document->addScript('view/javascript/dvbusiness/frontendHttpClient.js');
        $this->document->addScript('view/javascript/dvbusiness/licode.preloader.js');
        $this->document->addScript('view/javascript/dvbusiness/warehouseForm.js');

        $data = [
            'heading' => $this->language->get('dvbusiness_warehouse_form_head'),
        ];

        $data += $this->load->language('extension/shipping/dvbusiness');
        $data += $this->getBreadCrumbs();

        $data['warehouse']          = $warehouseData;
        $data['required_time_enum'] = $this->getRequiredTimeEnum();
        $data['user_token']         = $this->session->data['user_token'];
        $data['route']              = 'extension/shipping/dvbusiness/orders';
        $data['header']             = $this->load->controller('common/header');
        $data['column_left']        = $this->load->controller('common/column_left');
        $data['footer']             = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/dvbusiness_warehouse_form', $data));
    }

    /**
     * Ajax Сохранение склада с формы админки
     */
    public function warehouseFormSave()
    {
        $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $warehouseManager = new WarehouseManager($this->db);

        $postBody = file_get_contents('php://input');
        $data = json_decode($postBody, true) ?? [];

        if (!empty($data['warehouse_id'])) {
            $warehouseId = (int) $data['warehouse_id'];
            $warehouse = $warehouseManager->getById($warehouseId);
            if (!$warehouse) {
                $this->renderFrontendAjaxError('warehouse_not_found');
            }
        } else {
            $warehouse = new Warehouse;
        }

        $warehouse->name           = $data['name'] ?: 'default';
        $warehouse->city           = $data['city'] ?? '';
        $warehouse->address        = $data['address'] ?? '';
        $warehouse->workStartTime  = $data['work_start_time'] ?? '';
        $warehouse->workFinishTime = $data['work_finish_time'] ?? '';
        $warehouse->contactName    = $data['contact_name'] ?? '';
        $warehouse->contactPhone   = $data['contact_phone'] ?? '';
        $warehouse->note           = $data['note'] ?? '';

        $warehouse->setWorkdays($data['workdays'] ?? []);

        $warehouseManager->save($warehouse);

        $this->renderFrontendAjaxSuccess(
            [
                'warehouse_id'   => $warehouse->id,
                'redirect_route' => 'extension/shipping/dvbusiness/warehouses',
                'user_token'     => $this->session->data['user_token'],
            ]
        );
    }

    /**
     * Ajax Удаление склада из админки
     */
    public function warehouseDelete()
    {
        $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $warehouseManager = new WarehouseManager($this->db);

        $postBody = file_get_contents('php://input');
        $data = json_decode($postBody, true) ?? [];

        $warehouseId = $data['warehouse_id'] ?? null;
        if (!$warehouseId) {
            $this->renderFrontendAjaxError('warehouse_not_found');
        }

        $warehouse = $warehouseManager->getById($warehouseId);
        if (!$warehouse) {
            $this->renderFrontendAjaxError('warehouse_not_found');
        }

        $result = $warehouseManager->delete($warehouse->id);
        if ($result) {
            $this->renderFrontendAjaxSuccess([]);
        } else {
            $this->renderFrontendAjaxError('warehouse_deleting_error');
        }
    }

    /**
     * Список заказов на отгрузку
     */
    public function orders()
    {
        if (!$this->isWizardFinished()) {
            $this->response->redirect(
                $this->url->link(
                    'extension/shipping/dvbusiness/wizard',
                    http_build_query(['user_token' => $this->session->data['user_token']]),
                    true
                )
            );
        }

        $this->loadCore();

        $filter = [
            'order_status_id'          => $this->request->get['order_status_id'] ?? 1,
            'are_all_shipping_methods' => $this->request->get['are_all_shipping_methods'] ?? false,
        ];

        $ordersFilter = [
            'start' => 0,
            'limit' => 50,
            'order' => 'DESC',
        ];

        if ($filter['order_status_id']) {
            $ordersFilter['filter_order_status_id'] = $filter['order_status_id'];
        }

        if (!$filter['are_all_shipping_methods']) {
            $ordersFilter['filter_shipping_code'] = 'dvbusiness.general';
        }

        $orderManager = new OrderManager($this->db);
        $ordersResult = $orderManager->getOpenCartOrderRows($ordersFilter, $this->getConfig()->get('config_language_id'));

        /** @var ModelLocalisationOrderStatus $modelLocalisationOrderStatus */
        $this->load->model('localisation/order_status');
        $modelLocalisationOrderStatus = $this->model_localisation_order_status;

        $dostavistaOrderManager = new DostavistaOrderManager($this->db);
        $openCartOrderIds = array_column($ordersResult, 'order_id');
        $dostavistaOrders = $dostavistaOrderManager->getByOpenCartOrderIds($openCartOrderIds);

        $orders = [];
        foreach ($ordersResult as $orderData) {
            $item = [
                'order_id'                 => $orderData['order_id'],
                'customer'                 => $orderData['customer'],
                'order_status'             => $orderData['order_status'] ? $orderData['order_status'] : $this->language->get('text_missing'),
                'total'                    => $this->currency->format($orderData['total'], $orderData['currency_code'], $orderData['currency_value']),
                'date_added'               => date('d.m.Y H:i:s', strtotime($orderData['date_added'])),
                'shipping_code'            => $orderData['shipping_code'],
                'dostavista_order_id'      => null,
                'dostavista_courier_name'  => null,
                'dostavista_courier_phone' => null,
            ];

            foreach ($dostavistaOrders as $dostavistaOrder) {
                if (in_array($orderData['order_id'], $dostavistaOrder->openCartOrderIds)) {
                    $item['dostavista_order_id']      = $dostavistaOrder->dostavistaOrderId;
                    $item['dostavista_courier_name']  = $dostavistaOrder->courierName;
                    $item['dostavista_courier_phone'] = $dostavistaOrder->courierPhone;
                    break;
                }
            }

            $orders[] = $item;
        }

        $this->document->setTitle($this->language->get('dvbusiness_orders_head') . ' / ' . $this->language->get('heading_title'));
        $this->document->addScript('view/javascript/dvbusiness/orders.js');

        $data = [
            'heading' => $this->language->get('dvbusiness_orders_head'),
        ];
        $data += $this->load->language('extension/shipping/dvbusiness');
        $data += $this->getBreadCrumbs();

        $data['order_form_action'] = $this->url->link('extension/shipping/dvbusiness/orderForm', 'user_token=' . $this->session->data['user_token'], true);
        $data['filter']            = $filter;
        $data['order_statuses']    = $modelLocalisationOrderStatus->getOrderStatuses();
        $data['orders']            = $orders;
        $data['user_token']        = $this->session->data['user_token'];
        $data['route']             = 'extension/shipping/dvbusiness/orders';
        $data['header']            = $this->load->controller('common/header');
        $data['column_left']       = $this->load->controller('common/column_left');
        $data['footer']            = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/dvbusiness_orders', $data));
    }

    /**
     * Форма заказа для отправки в Достависту
     */
    public function orderForm()
    {
        $dvOptions = $this->loadCore();

        $orderIds = $this->request->get['ids'] ?? [];

        /** @var ModelSaleOrder $modelSaleOrder */
        $this->load->model('sale/order');
        $modelSaleOrder = $this->model_sale_order;

        /** @var ModelCatalogProduct $modelCatalogProduct */
        $this->load->model('catalog/product');
        $modelCatalogProduct = $this->model_catalog_product;

        $orderManager = new OrderManager($this->db);

        /** @var DostavistaOpenCartOrder[] $dostavistaOpenCartOrders */
        $dostavistaOpenCartOrders = [];
        foreach ($orderIds as $orderId) {
            $order = new DostavistaOpenCartOrder(
                (int) $orderId,
                $modelSaleOrder,
                $modelCatalogProduct,
                $orderManager->getShippingDetails($orderId),
                $dvOptions
            );

            $dostavistaOpenCartOrders[] = $order;
        }

        $warehouseManager = new WarehouseManager($this->db);
        $warehouse = null;
        if ($dvOptions->getDefaultPickupWarehouseId()) {
            $warehouse = $warehouseManager->getById($dvOptions->getDefaultPickupWarehouseId());
        }

        if (!$warehouse) {
            $warehouse = new Warehouse();
        }

        $orderForm = new OrderForm(
            $dostavistaOpenCartOrders,
            $dvOptions,
            $warehouse,
            $this->language->get('dvbusiness_order_form_weight'),
            $this->language->get('dvbusiness_order_form_weight_kg')
        );

        $dostavistaOpenCartOrdersData = [];
        foreach ($dostavistaOpenCartOrders as $order) {
            $dostavistaOpenCartOrdersData[] = [
                'id'                   => $order->getId(),
                'items_price'          => $order->getItemsPrice(),
                'contact_name'         => $order->getContactName(),
                'contact_phone'        => $order->getContactPhone(),
                'shipping_address'     => $order->getShippingAddress(),
                'shipping_price'       => $order->getShippingPrice(),
                'shipping_comment'     => $order->getShippingComment(),
                'shipping_date'        => $orderForm->getDeliveryRequiredDate($order),
                'shipping_start_time'  => $orderForm->getDeliveryRequiredStartTime($order),
                'shipping_finish_time' => $orderForm->getDeliveryRequiredFinishTime($order),
                'taking_amount'        => $order->getTakingAmount(),
            ];
        }

        $generalOrder = [
            'default_vehicle_type_id'             => $dvOptions->getDefaultVehicleTypeId(),
            'matter'                              => $orderForm->getMatterWithPrefix(),
            'total_weight_kg'                     => $orderForm->getItemsTotalWeightKg(),
            'loaders_count'                       => 0,
            'insurance_amount'                    => $orderForm->getInsuranceAmount(),
            'contact_person_notification_enabled' => $dvOptions->isContactPersonNotificationEnabled(),
            'pickup_address'                      => $warehouse->getFullAddress(),
            'pickup_date'                         => $orderForm->getPickupRequiredDate(),
            'pickup_work_start_time'              => $orderForm->getPickupRequiredStartTime(),
            'pickup_work_finish_time'             => $warehouse->workFinishTime,
            'pickup_contact_name'                 => $warehouse->contactName,
            'pickup_contact_phone'                => $warehouse->contactPhone,
            'pickup_note'                         => $warehouse->note,
            'pickup_buyout_amount'                => $orderForm->getPickupBuyoutAmount(),
            'bank_card_id'                        => $orderForm->getBankCardId(),
            'payment_type'                        => $orderForm->getPaymentType(),
        ];

        $dateEnum = [];
        for ($i = 0; $i <= 14; $i++) {
            $timestamp = strtotime("+$i days");
            $title = date('d.m.Y', $timestamp);
            $value = date('Y-m-d', $timestamp);
            $textTitles = [$this->language->get('select_date_today'), $this->language->get('select_date_tomorrow')];
            $dateEnum[$value] = $textTitles[$i] ?? $title;
        }

        $dostavistaOrderManager = new DostavistaOrderManager($this->db);
        $dostavistaOrders = $dostavistaOrderManager->getByOpenCartOrderIds($orderIds);
        $dostavistaOrdersData = [];
        foreach ($dostavistaOrders as $dostavistaOrder) {
            $dostavistaOrdersData[] = [
                'dostavista_order_id' => $dostavistaOrder->dostavistaOrderId,
                'created_datetime'    => date('d.m.Y H:i:s', strtotime($dostavistaOrder->createdDatetime)),
            ];
        }

        $warehouseManager = new WarehouseManager($this->db);
        $warehouses = $warehouseManager->getList();
        $warehousesData = [];
        foreach ($warehouses as $warehouse) {
            $warehousesData[] = [
                'id'               => $warehouse->id,
                'name'             => $warehouse->name,
                'address'          => $warehouse->getFullAddress(),
                'work_start_time'  => $warehouse->workStartTime,
                'work_finish_time' => $warehouse->workFinishTime,
                'contact_name'     => $warehouse->contactName,
                'contact_phone'    => $warehouse->contactPhone,
                'note'             => $warehouse->note,
            ];
        }

        $this->document->setTitle($this->language->get('dvbusiness_order_form_head') . ' / ' . $this->language->get('heading_title'));

        $this->document->addScript('view/javascript/dvbusiness/frontendHttpClient.js');
        $this->document->addScript('view/javascript/dvbusiness/licode.preloader.js');
        $this->document->addScript('view/javascript/dvbusiness/orderForm.js');

        $data = [
            'heading' => $this->language->get('dvbusiness_order_form_head'),
        ];

        $data += $this->load->language('extension/shipping/dvbusiness');
        $data += $this->getBreadCrumbs();

        $data['general_order']              = $generalOrder;
        $data['dostavista_opencart_orders'] = $dostavistaOpenCartOrdersData;
        $data['dostavista_orders']          = $dostavistaOrdersData;
        $data['vehicle_types_enum']         = $this->getVehicleTypeEnum();
        $data['required_time_enum']         = $this->getRequiredTimeEnum();
        $data['required_date_enum']         = $dateEnum;
        $data['today_date']                 = date('Y-m-d');
        $data['warehouses']                 = $warehousesData;
        $data['header']                     = $this->load->controller('common/header');
        $data['column_left']                = $this->load->controller('common/column_left');
        $data['footer']                     = $this->load->controller('common/footer');
        $data['payment_types']              = $this->getPaymentTypes();
        $data['bank_cards']                 = $this->getPaymentBankCards();
        $data['bank_card_payment_types']    = [PaymentMethodEnum::PAYMENT_METHOD_BANK, PaymentMethodEnum::PAYMENT_METHOD_QIWI];

        $this->response->setOutput($this->load->view('extension/shipping/dvbusiness_order_form', $data));
    }

    /**
     * Ajax Создание заказа с формы админки
     */
    public function orderFormCreate()
    {
        $dvOptions = $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $dvBusinessApiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());
        $postBody = file_get_contents('php://input');
        $data = json_decode($postBody, true) ?? [];

        // Получим доступные методы оплаты
        $allowedPaymentMethods = DostavistaClientManager::getAllowedPaymentTypes($dvOptions);

        $orderFormProcessor = new OrderFormProcessor($dvBusinessApiClient, $data, $allowedPaymentMethods);

        try {
            $dvApiResponse = $orderFormProcessor->createOrder();
            $orderId = $dvApiResponse->getData()['order']['order_id'] ?? null;
            if ($orderId) {
                $dostavistaOrderManager = new DostavistaOrderManager($this->db);
                $dostavistaOrder = $dostavistaOrderManager->getByDostavistaOrderId($orderId);
                if (!$dostavistaOrder) {
                    $dostavistaOrder = new DostavistaOrder;
                    $dostavistaOrder->dostavistaOrderId = $orderId;
                }

                $dostavistaOrder->openCartOrderIds = $orderFormProcessor->getClientOrderIds();
                $dostavistaOrderManager->save($dostavistaOrder);

                // Отправим метрику создания заказа
                $moduleConfig = new ModuleConfig();
                $moduleMetricManager = new ModuleMetricManager(
                    $this->db,
                    $moduleConfig->getDvCmsModuleApiProdUrl(),
                    $dvOptions->getApiUrl(),
                    $dvOptions->getAuthToken()
                );

                $moduleMetricManager->moduleOrder();

                $this->renderFrontendAjaxSuccess(
                    [
                        'order_id' => (int) $orderId,
                    ]
                );
            } else {
                $this->renderFrontendAjaxError($dvApiResponse->getErrors()[0] ?? 'dostavista_business_api_order_creation_error');
            }
        } catch (DvCmsModuleApiHttpException $exception) {
            $responseBody = $exception->getResponseBody();
            $responseData = json_decode($responseBody, true);
            if (!is_array($responseData)) {
                $responseData = [];
            }

            $this->renderFrontendAjaxError($responseData['errors'][0] ?? 'dostavista_business_api_order_creation_error');
        }
    }

    /**
     * Ajax Расчет заказа с формы админки
     */
    public function orderFormCalculate()
    {
        $dvOptions = $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $dvBusinessApiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());
        $postBody = file_get_contents('php://input');
        $data = json_decode($postBody, true) ?? [];

        // Получим доступные методы оплаты
        $allowedPaymentMethods = DostavistaClientManager::getAllowedPaymentTypes($dvOptions);

        $orderFormProcessor = new OrderFormProcessor($dvBusinessApiClient, $data, $allowedPaymentMethods);

        try {
            $dvApiResponse = $orderFormProcessor->calculateOrder();
            $orderResponseModel = new OrderResponseModel($dvApiResponse->getData()['order'] ?? []);

            $orderCalculationResultResponseModel = new OrderCalculationResultResponseModel($orderResponseModel);

            $this->renderFrontendAjaxSuccess(
                [
                    'order_calculation_result' => $orderCalculationResultResponseModel->getData(),
                    'form_parameter_errors'    => $orderFormProcessor->getFormParameterErrors($dvApiResponse),
                ]
            );
        } catch (DvCmsModuleApiHttpException $exception) {
            $this->renderFrontendAjaxError('dostavista_business_api_order_calculation_error');
        }
    }

    public function wizard()
    {
        $dvOptions = $this->loadCore();

        /** @var ModelLocalisationOrderStatus $modelLocalisationOrderStatus */
        $this->load->model('localisation/order_status');
        $modelLocalisationOrderStatus = $this->model_localisation_order_status;

        $this->load->model('setting/extension');
        $paymentMethodsEnum = (new PaymentMethods($this->model_setting_extension))->getEnum();

        $this->document->setTitle($this->language->get('dvbusiness_wizard_head') . ' / ' . $this->language->get('heading_title'));

        $this->document->addScript('view/javascript/dvbusiness/frontendHttpClient.js');
        $this->document->addScript('view/javascript/dvbusiness/licode.preloader.js');
        $this->document->addScript('view/javascript/dvbusiness/wizard.js');

        $data = [
            'heading' => $this->language->get('dvbusiness_wizard_head'),
        ];
        $data += $this->load->language('extension/shipping/dvbusiness');
        $data += $this->getBreadCrumbs();

        $wizardSettings = [
            'shipping_dvbusiness_auth_token'                     => $dvOptions->getBusinessApiAuthToken(),
            'shipping_dvbusiness_cms_module_api_prod_auth_token' => $dvOptions->getProdAuthToken(),
            'shipping_dvbusiness_cms_module_api_test_auth_token' => $dvOptions->getTestAuthToken(),
            'shipping_dvbusiness_is_api_test_server'             => $dvOptions->getIsApiTestServer(),

            'shipping_dvbusiness_default_pickup_warehouse_id'         => $dvOptions->getDefaultPickupWarehouseId(),
            'shipping_dvbusiness_default_order_weight_kg'             => $dvOptions->getDefaultOrderWeightKg(),
            'shipping_dvbusiness_dostavista_payment_markup_amount'    => $dvOptions->getDostavistaPaymentMarkupAmount(),
            'shipping_dvbusiness_dostavista_payment_discount_amount'  => $dvOptions->getDostavistaPaymentDiscountAmount(),
            'shipping_dvbusiness_fix_order_payment_amount'            => $dvOptions->getFixOrderPaymentAmount(),
            'shipping_dvbusiness_free_delivery_opencart_order_sum'    => $dvOptions->getFreeDeliveryOpencartOrderSum(),
            'shipping_dvbusiness_default_vehicle_type_id'             => $dvOptions->getDefaultVehicleTypeId(),
            'shipping_dvbusiness_insurance_enabled'                   => $dvOptions->isInsuranceEnabled(),
            'shipping_dvbusiness_buyout_enabled'                      => $dvOptions->isBuyoutEnabled(),
            'shipping_dvbusiness_matter_weight_prefix_enabled'        => $dvOptions->isMatterWeightPrefixEnabled(),
            'shipping_dvbusiness_contact_person_notification_enabled' => $dvOptions->isContactPersonNotificationEnabled(),
            'shipping_dvbusiness_delivery_point_note_prefix'          => $dvOptions->getDeliveryPointNotePrefix(),

            'shipping_dvbusiness_opencart_cash_payment_code'          => $dvOptions->getOpenCartCashPaymentCode(),

            'shipping_dvbusiness_api_callback_secret_key'                   => $dvOptions->getApiCallbackSecretKey(),
            'shipping_dvbusiness_integration_order_status_available'        => $dvOptions->getIntegrationOrderStatusAvailable(),
            'shipping_dvbusiness_integration_order_status_active'           => $dvOptions->getIntegrationOrderStatusActive(),
            'shipping_dvbusiness_integration_order_status_completed'        => $dvOptions->getIntegrationOrderStatusCompleted(),
            'shipping_dvbusiness_integration_order_status_canceled'         => $dvOptions->getIntegrationOrderStatusCanceled(),
            'shipping_dvbusiness_integration_order_status_failed'           => $dvOptions->getIntegrationOrderStatusFailed(),
            'shipping_dvbusiness_integration_order_status_draft'            => $dvOptions->getIntegrationOrderStatusDraft(),
            'shipping_dvbusiness_integration_order_status_courier_assigned' => $dvOptions->getIntegrationOrderStatusCourierAssigned(),
            'shipping_dvbusiness_integration_order_status_parcel_picked_up' => $dvOptions->getIntegrationOrderStatusParcelPickedUp(),
            'shipping_dvbusiness_integration_order_status_courier_departed' => $dvOptions->getIntegrationOrderStatusCourierDeparted(),
            'shipping_dvbusiness_integration_order_status_courier_arrived'  => $dvOptions->getIntegrationOrderStatusCourierArrived(),
            'shipping_dvbusiness_integration_order_status_delayed'          => $dvOptions->getIntegrationOrderStatusDelayed(),
            'shipping_dvbusiness_default_payment_card_id'                   => $dvOptions->getDefaultPaymentCardId(),
            'shipping_dvbusiness_default_payment_type'                      => $dvOptions->getDefaultPaymentType(),
        ];

        $data += $wizardSettings;

        $data['step_1_success'] = (bool) $dvOptions->getAuthToken() && $this->isTokenValid();

        $warehouseManager = new WarehouseManager($this->db);
        if ($dvOptions->getDefaultPickupWarehouseId()) {
            $warehouse = $warehouseManager->getById($dvOptions->getDefaultPickupWarehouseId());
            if (!$warehouse) {
                $warehouse = new Warehouse();
            }
        } else {
            $warehouse = new Warehouse();
        }

        $warehouseData = [
            'id'               => $warehouse->id,
            'name'             => $warehouse->name,
            'city'             => $warehouse->city,
            'address'          => $warehouse->address,
            'work_start_time'  => $warehouse->workStartTime,
            'work_finish_time' => $warehouse->workFinishTime,
            'contact_name'     => $warehouse->contactName,
            'contact_phone'    => $warehouse->contactPhone,
            'note'             => $warehouse->note,
            'workdays'         => $warehouse->getWorkdays(),
        ];
        $data['warehouse'] = $warehouseData;

        $data['api_callback_url']        = str_replace('/admin', '', $this->url->link('extension/shipping/dvbusiness/apiCallbackHandler'));
        $data['vehicle_types_enum']      = $this->getVehicleTypeEnum();
        $data['required_time_enum']      = $this->getRequiredTimeEnum();
        $data['payment_methods_enum']    = $paymentMethodsEnum;
        $data['order_statuses']          = $modelLocalisationOrderStatus->getOrderStatuses();
        $data['route']                   = 'extension/shipping/dvbusiness/wizard';
        $data['orders_url']              = $this->url->link('extension/shipping/dvbusiness/orders', 'user_token=' . $this->session->data['user_token'], true);
        $data['header']                  = $this->load->controller('common/header');
        $data['column_left']             = $this->load->controller('common/column_left');
        $data['footer']                  = $this->load->controller('common/footer');
        $data['payment_types']           = $this->getPaymentTypes();
        $data['bank_cards']              = $this->getPaymentBankCards();
        $data['bank_card_payment_types'] = [PaymentMethodEnum::PAYMENT_METHOD_BANK, PaymentMethodEnum::PAYMENT_METHOD_QIWI];

        $wizardResultManager = new WizardResultManager($dvOptions);
        $data['start_step'] = $wizardResultManager->getLastFinishedStep() + 1;

        $this->response->setOutput($this->load->view('extension/shipping/dvbusiness_wizard', $data));
    }

    /**
     * Ajax создание токена в Business API
     */
    public function createAuthToken()
    {
        $dvOptions = $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $postBody = file_get_contents('php://input');
        $data     = json_decode($postBody, true) ?? [];

        $clientLogin    = $data['client_login'] ?? '';
        $clientPassword = $data['client_password'] ?? '';
        $isApiTest      = (bool) ($data['is_apitest'] ?? false);

        $moduleConfig = new ModuleConfig();

        $apiUrl = $isApiTest
            ? $moduleConfig->getDvCmsModuleApiTestUrl()
            : $moduleConfig->getDvCmsModuleApiProdUrl();

        $dvBusinessApiClient = new DvCmsModuleApiClient($apiUrl);

        if (filter_var($clientLogin, FILTER_VALIDATE_EMAIL) !== false) {
            $response = $dvBusinessApiClient->createOrganizationAuthToken(
                $clientLogin, $clientPassword
            );
        } else {
            $response = $dvBusinessApiClient->createPersonAuthToken(
                $clientLogin, $clientPassword
            );
        }

        $authToken = $response->getData()['auth_token'] ?? null;
        if ($authToken) {
            $settings = $dvOptions->getSettings();


            $settings['shipping_dvbusiness_is_api_test_server'] = $isApiTest;
            if (!$isApiTest) {
                $settings['shipping_dvbusiness_cms_module_api_prod_auth_token'] = $authToken;
            } else {
                $settings['shipping_dvbusiness_cms_module_api_test_auth_token'] = $authToken;
            }

            try {
                $dvBusinessApiClient = new DvCmsModuleApiClient($apiUrl, $authToken);
                $apiEditSettingsResponse = $dvBusinessApiClient->editApiSettings(str_replace('/admin', '', $this->url->link('extension/shipping/dvbusiness/apiCallbackHandler')));
                $settings['shipping_dvbusiness_cms_module_api_callback_secret_key'] = $apiEditSettingsResponse->getCallbackSecretKey();
            } catch (DvCmsModuleApiHttpException $businessApiHttpException) {

            }

            $dvOptions->updateSettings($settings);

            $moduleConfig = new ModuleConfig();
            $moduleMetricManager = new ModuleMetricManager(
                $this->db,
                $moduleConfig->getDvCmsModuleApiProdUrl(),
                $dvOptions->getApiUrl(),
                $dvOptions->getAuthToken()
            );

            $moduleMetricManager->tokenCreate();
            $moduleMetricManager->tokenInstall();

            if (!empty($settings['shipping_dvbusiness_cms_module_api_callback_secret_key'])) {
                $moduleMetricManager->callbackKeyInstall();
            }

            $this->renderFrontendAjaxSuccess(
                [
                    'api_url'                 => $apiUrl,
                    'is_api_test'             => $isApiTest,
                    'api_auth_token'          => $authToken,
                    'api_callback_secret_key' => $settings['shipping_dvbusiness_cms_module_api_callback_secret_key'] ?? '',
                ]
            );
        } else {
            $this->renderFrontendAjaxError('invalid_client');
        }
    }

    /**
     * Ajax сохранение настроек магазина
     */
    public function storeSettings()
    {
        $dvOptions = $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $postBody = file_get_contents('php://input');
        $data     = json_decode($postBody, true) ?? [];
        $settings = $dvOptions->getSettings();

        $isDeliveryServiceInstalled = (bool) ($settings['shipping_dvbusiness_status'] ?? false);

        foreach ($data as $key => $value) {
            // Не хотим сохранять мусорные поля из формы
            if (strpos($key, 'shipping_dvbusiness_') !== 0) {
                continue;
            }
            $settings[$key] = $value;
        }

        // Отдельно включаем службу доставки
        $settings['shipping_dvbusiness_status'] = 1;

        $dvOptions->updateSettings($settings);

        $moduleConfig = new ModuleConfig();
        $moduleMetricManager = new ModuleMetricManager(
            $this->db,
            $moduleConfig->getDvCmsModuleApiProdUrl(),
            $dvOptions->getApiUrl(),
            $dvOptions->getAuthToken()
        );

        if (!$isDeliveryServiceInstalled) {
            $moduleMetricManager->deliveryInstall();
        }

        $this->renderFrontendAjaxSuccess([]);
    }

    private function renderFrontendAjaxError(string $error, array $parameterErrors = [])
    {
        http_response_code(400);
        echo json_encode([
            'error'            => $error,
            'parameter_errors' => $parameterErrors,
        ]);
        exit;
    }

    private function renderFrontendAjaxSuccess($data)
    {
        echo json_encode($data);
        exit;
    }

    private function getBreadCrumbs(): array
    {
        $data = [
            'breadcrumbs' => [],
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', http_build_query(['user_token' => $this->session->data['user_token']]), true)
        ];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', http_build_query(['user_token' => $this->session->data['user_token'], 'type' => 'shipping']), true)
        ];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/dvbusiness', http_build_query(['user_token' => $this->session->data['user_token']]), true)
        ];

        return $data;
    }

    private function getRequiredTimeEnum(): array
    {
        $enum = [];
        for ($h = 0; $h < 24; $h++) {
            for ($i = 0; $i < 60; $i += 30) {
                $value = date('H:i', strtotime("today {$h}:{$i}"));
                $enum[$value] = $value;
            }
        }

        return $enum;
    }

    private function getVehicleTypeEnum(): array
    {
        return [
            6 => $this->language->get('dvbusiness_vehicle_type_enum_walk'),
            8 => $this->language->get('dvbusiness_vehicle_type_enum_motorbike'),
            7 => $this->language->get('dvbusiness_vehicle_type_enum_car'),
            1 => $this->language->get('dvbusiness_vehicle_type_enum_truck_pickup'),
            2 => $this->language->get('dvbusiness_vehicle_type_enum_truck_minivan'),
            3 => $this->language->get('dvbusiness_vehicle_type_enum_truck_porter'),
            4 => $this->language->get('dvbusiness_vehicle_type_enum_truck_van'),
        ];
    }

    private function getPaymentTypes(): array
    {
        $dvOptions = $this->loadCore();
        $dvBusinessApiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());

        // Получим доступные методы оплаты
        $allowedPaymentsMethod = DostavistaClientManager::getAllowedPaymentTypes($dvOptions);

        $paymentTypes = [];
        foreach ($allowedPaymentsMethod as $allowedPaymentType) {
            $paymentTypes[$allowedPaymentType] = $this->language->get('dvbusiness_payment_type_' . $allowedPaymentType);
        }
        return $paymentTypes;
    }

    private function getPaymentBankCards(): array
    {
        $dvOptions           = $this->loadCore();
        $dvBusinessApiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());
        $bankCards           = BankCardsManager::getShopBankCards($dvBusinessApiClient);

        $cards = [];
        foreach ($bankCards as $bankCard) {
            $cards[$bankCard->getBankCardId()] = $this->language->get('dvbusiness_payment_type_card') . ' ' . $bankCard->getBankCardNumberMask();
        }

        return $cards;
    }

    /**
     * Ajax загрузка карт пользователя
     */
    public function getPaymentMethods()
    {
        $dvOptions = $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $dvBusinessApiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());
        $bankCards = BankCardsManager::updateShopBankCardsCache($dvBusinessApiClient);
        $bankCardsData = [];
        foreach ($bankCards as $bankCard) {
            $bankCardsData[] = [
                'id'   => $bankCard->getBankCardId(),
                'mask' => $bankCard->getBankCardNumberMask(),
                'type' => $bankCard->getCardType(),
            ];
        }

        // Получим доступные методы оплаты
        $paymentMethods = DostavistaClientManager::getAllowedPaymentTypes($dvOptions);
        $paymentMethodsData = [];

        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethodsData[] = [
                'code'    => $paymentMethod,
                'name'    => $this->language->get('dvbusiness_payment_type_' . $paymentMethod),
                'is_card' => in_array($paymentMethod, [PaymentMethodEnum::PAYMENT_METHOD_QIWI, PaymentMethodEnum::PAYMENT_METHOD_BANK])
            ];
        }

        $this->renderFrontendAjaxSuccess([
            'success'         => 'true',
            'cards'           => $bankCardsData,
            'payment_methods' => $paymentMethodsData,
        ]);
    }

    private function isTokenValid(): bool
    {
        $dvOptions = $this->loadCore();
        $dvApiTokenVerifier = new DostavistaApiTokenVerifier($dvOptions);

        return $dvApiTokenVerifier->isCmsModuleApiTokenValid();
    }

    private function isWizardFinished(): bool
    {
        $dvOptions = $this->loadCore();
        $wizardResultManager = new WizardResultManager($dvOptions);

        return $wizardResultManager->getIsWizardFinished();
    }

    public function setWizardLastFinishedStep()
    {
        $dvOptions = $this->loadCore();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->renderFrontendAjaxError('require_method_post');
        }

        $postBody = file_get_contents('php://input');
        $data     = json_decode($postBody, true) ?? [];

        $stepNumber = $data['step_number'] ?? false;

        if (!$stepNumber) {
            $this->renderFrontendAjaxError('missing_step_number');
        }

        $moduleConfig = new ModuleConfig();
        $moduleMetricManager = new ModuleMetricManager(
            $this->db,
            $moduleConfig->getDvCmsModuleApiProdUrl(),
            $dvOptions->getApiUrl(),
            $dvOptions->getAuthToken()
        );

        $wizardResultManager = new WizardResultManager($dvOptions);
        $wizardResultManager->setLastFinishedStep($stepNumber, $moduleMetricManager);

        $this->renderFrontendAjaxSuccess([
            'success'         => 'true',
        ]);
    }

    public function dostavistaLogout()
    {
        $dvOptions = $this->loadCore();
        DostavistaAuthManager::logoutFromDostavista($dvOptions);
        $this->renderFrontendAjaxSuccess([
            'success'         => 'true',
        ]);
    }
}
