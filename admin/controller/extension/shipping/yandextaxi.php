<?php

if (!defined('CALLED_FROM_PLUGIN')) {
    define('CALLED_FROM_PLUGIN', 1); // define constant that prevents core direct call
}

use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Exceptions\NotAuthorizedException;
use YandexTaxi\Exceptions\ShipmentException;
use YandexTaxi\Delivery\GeoCoding\YandexMaps\YandexGeoCoder;
use YandexTaxi\Delivery\PhoneNumber\Formatter as PhoneFormatter;
use YandexTaxi\Presenters\ShipmentPresenter;
use YandexTaxi\Repositories\WarehouseRepository;
use libphonenumber\NumberParseException;
use YandexTaxi\Repositories\OrderRepository;
use YandexTaxi\Services\OrderService;
use YandexTaxi\Services\ReferralSourceFinder;
use YandexTaxi\Services\ShipmentService;
use YandexTaxi\Utils\Responds;
use YandexTaxi\Services\SettingService;
use YandexTaxi\Services\OrderStatusService;
use YandexTaxi\YandexTaxi;
use YandexTaxi\Services\AvailableTariffChecker;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;
use YandexTaxi\Services\Constants;
use YandexTaxi\Http\CurlClient;
use YandexTaxi\Controllers\OrderController;
use YandexTaxi\Controllers\WarehouseController;
use YandexTaxi\Services\DefaultWarehouseFinder;

/**
 * Class ControllerExtensionShippingYandexTaxi
 *
 * @property-read \Url                             $url
 * @property-read \Loader                          $load
 * @property-read \Request                         $request
 * @property-read \Language                        $language
 * @property-read \Document                        $document
 * @property-read \Session                         $session
 * @property-read \Response                        $response
 * @property-read \Cart\User                       $user
 * @property-read \ModelSettingSetting             $model_setting_setting
 * @property-read \ModelLocalisationTaxClass       $model_localisation_tax_class
 * @property-read \ModelLocalisationGeoZone        $model_localisation_geo_zone
 * @property-read \ModelSaleOrder                  $model_sale_order
 * @property-read \ModelCatalogProduct             $model_catalog_product
 * @property-read \ModelLocalisationOrderStatus    $model_localisation_order_status
 * @property-read YandexTaxi                       $yandextaxi
 * @property-read Responds                         $responds
 * @property-read YandexGeoCoder                   $geo_coder
 * @property-read ShipmentService                  $shipment_service
 * @property-read ModelExtensionShippingYandexTaxi $model_extension_shipping_yandextaxi
 * @property-read OrderRepository                  $order_repository
 * @property-read OrderService                     $order_service
 * @property-read WarehouseRepository              $warehouse_repository
 * @property-read Tariffs                          $tariffs
 */
class ControllerExtensionShippingYandexTaxi extends Controller {

    /** @var SettingService */
    private $setting_service;

    /** @var OrderController */
    private $order_controller;

    /** @var WarehouseController */
    private $warehouse_controller;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->init();

        $this->order_controller = new OrderController(
            $this->model_sale_order,
            $this->currency,
            $this->language,
            $this->config,
            $this->session,
            $this->url,
            $this->responds,
            $this->order_service,
            $this->shipment_service
        );

        $this->warehouse_controller = new WarehouseController(
            $this->language,
            $this->session,
            $this->url,
            $this->responds,
            $this->config,
            $this->warehouse_repository,
            $this->setting_service,
            $this->tariffs
        );
    }

    private function init(): void {
        $this->load->model('localisation/tax_class');
        $this->load->model('localisation/geo_zone');
        $this->load->model('extension/shipping/yandextaxi');
        $this->load->model('setting/setting');
        $this->load->model('sale/order');
        $this->load->model('catalog/product');
        $this->load->language('extension/shipping/yandextaxi');
        $this->load->library('yandextaxi/yandextaxi');
        $this->load->model('localisation/order_status');

        $this->document->addStyle('view/stylesheet/yandextaxi.css?v=' . Constants::VERSION);
        $this->document->addScript('view/javascript/yandextaxi/map.js?v=' . Constants::VERSION);
        $this->document->addScript('view/javascript/yandextaxi/settings.js?v=' . Constants::VERSION);
        $this->document->addScript('view/javascript/yandextaxi/form-validation.js?v=' . Constants::VERSION);
        $this->document->addScript('view/javascript/yandextaxi/lib/intlTelInput/js/intlTelInput-jquery.min.js?v=' . Constants::VERSION);
        $this->document->addStyle('view/javascript/yandextaxi/lib/intlTelInput/css/intlTelInput.min.css?v=' . Constants::VERSION);

        $this->setting_service = new SettingService($this->model_setting_setting);
    }

    public function install() {
        $this->model_extension_shipping_yandextaxi->install();
    }

    public function uninstall () {
        //
    }

    public function index() {
        $this->document->setTitle($this->language->get('heading_title'));
        $token = $this->session->data['user_token'];

        $settings = $this->getEditableSettingValues();

        $message = null;

        if ($this->responds->isPost() && $this->validateSettings($this->request->post)) {
            try {
                $this->setting_service->storeAll($settings);
            } catch (Exception $e) {
                $this->session->data['error'] = $e->getMessage();
            }

            try {
                $config = require __DIR__ . '/../../../../system/library/yandextaxi/config/config.php';
                $api_token = $this->setting_service->getOne('shipping_yandextaxi_api_token');
                $api = new Client(
                    new CurlClient(),
                    $api_token,
                    !$config['use_test_env'],
                    ReferralSourceFinder::find(),
                    $this->config->get('config_admin_language')
                );
                
                if (!(new AvailableTariffChecker(new Tariffs($api)))->isAvailable()) {
                    $message = $this->responds->output('partial/_no_tariffs', []);
                } else {
                    $this->response->redirect(
                        $this->url->link('extension/shipping/yandextaxi', 'user_token=' . $token, true)
                    );
                }
            } catch (NotAuthorizedException $exception) {
                $message = $this->responds->output('partial/_bad_token');
            } catch (YandexApiException $exception) {
                $message = $this->responds->output('partial/_error', ['message' => $exception->getMessage()]);
            }
        }

        $this->responds->view('yandextaxi_settings_form', array_merge([
            'action' => $this->url->link('extension/shipping/yandextaxi', 'user_token=' . $token, true),
            'cancel' => $this->url->link('marketplace/extension', 'user_token=' . $token . '&type=shipping', true),
            'warehouses_index_url' => $this->url->link('extension/shipping/yandextaxi/indexWarehouses', 'user_token=' . $token, true),
            'orders_index_url' => $this->url->link('extension/shipping/yandextaxi/indexOrders', 'user_token=' . $token, true),
            'assembly_minutes' => $this->getAssemblyMinutes(),
            'tax_classes' => $this->model_localisation_tax_class->getTaxClasses(),
            'time_zone_offset' => date('P'),
            'geo_zones' => $this->model_localisation_geo_zone->getGeoZones(),
            'message' => $message,
            'support_contact' => $this->responds->output('partial/_support_contact', ['plugin_version' => Constants::VERSION]),
            'cabinet_modal' => $this->responds->output('partial/_create_cabinet_modal'),
            'title_options' => [
                'delivery' => $this->language->get('yandex_go_delivery'),
                'express_delivery' => $this->language->get('yandex_go_delivery_express'),
                ],
        ], $settings));
    }

    public function createOrder() {
        $geo_coder_token = $this->setting_service->getOne('shipping_yandextaxi_geo_coder_api_token');
        $token = $this->session->data['user_token'];

        $breadcrumbs[] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', "user_token={$token}", true),
        ];

        $breadcrumbs[] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/yandextaxi', "user_token={$token}", true),
        ];

        $breadcrumbs[] = [
            'text' => $this->language->get('heading_order_index'),
            'href' => $this->url->link('extension/shipping/yandextaxi/indexOrders', "user_token={$token}", true),
        ];

        $order_ids = $this->request->get['order_ids'] ?? [];
        $orders = $this->order_repository->getOrdersByIds($order_ids);

        if (empty($orders)) {
            return $this->responds->view('yandextaxi_order_not_found', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }

        $warehouses = $this->warehouse_repository->all();
        $source = (new DefaultWarehouseFinder($this->setting_service, $this->warehouse_repository))->find();

        if (is_null($source)) {
            if (!empty($warehouses)) {
                $source = $warehouses[0];
            }
        }

        $this->responds->view('yandextaxi_create_order', array_merge([
            'title' => $this->language->get('heading_title_create_order'),
            'action' => $this->url->link('extension/shipping/yandextaxi/confirmClaim', 'user_token=' . $token, true),
            'geo_coder_token' => $geo_coder_token,
            'user_token' => $token,
            'claim_link_key' => uniqid(),
            'source' => $this->responds->output('partial/_source', [
                'warehouses' => $warehouses,
                'source' => $source,
                'time_zone_offset' => date('P'),
            ]),
            'destinations' => $this->getDestinationsHtml($orders),
            'destinationTemplate' => $this->responds->output('partial/_destination', ['index' => 'index']),
            'breadcrumbs' => $breadcrumbs,
            'base_url' => $this->getBaseUrl(),
            'support_contact' => $this->responds->output('partial/_support_contact', ['plugin_version' => Constants::VERSION]),
            'translations_map' => $this->responds->output('translations/_map'),
            'translations_validation' => $this->responds->output('translations/_validation'),
            'settings_url' => $this->url->link('extension/shipping/yandextaxi', 'user_token=' . $token, true),
        ], $this->setting_service->getAll()));
    }

    public function getDestinationForOrder() {
        $order_id = (int) $this->request->get['order_id'];
        $order = !empty($order_id) ? $this->order_repository->getOrderById($order_id) : null;

        if (is_null($order)) {
            return $this->responds->json([
                'error' => $this->language->get('text_order_not_found'),
            ]);
        }

        return $this->responds->json([
            'destination' => $this->getDestination($order),
            'order_already_in_shipment' => !$this->order_service->checkAvailableForShipping($order['order_id']),
        ]);
    }

    public function getCancelInfo() {
        $order_id = $this->request->get['order_id'];
        $shipment = $this->shipment_service->getForOrder($order_id);
        if (is_null($shipment)) {
            return $this->responds->json(['error' => $this->language->get('error_during_cancellation')]);
        }

        try {
            $claim = $this->shipment_service->getClaim($shipment->getClaimId());
        } catch (YandexApiException $e) {
            return $this->responds->json(['error' => $this->language->get('error_during_cancellation')]);
        }

        if (is_null($claim->getAvailableCancelStatus())) {
            return $this->responds->json(['error' => $this->language->get('error_cannot_cancel_order')]);
        }

        $cancelStatus = $this->language->get('cancel_status_' . $claim->getAvailableCancelStatus()->getValue());

        if ($claim->isMulti()) {
            $orderIds = [];
            foreach ($claim->getDestinations() as $destination) {
                if (!empty($destination->getOrderId())) {
                    $orderIds[] = "№{$destination->getOrderId()}";
                }
            }

            $message = sprintf(
                $this->language->get('text_cancel_multiple_confirm'),
                $order_id,
                $cancelStatus,
                implode(', ', $orderIds)
            );
        } else {
            $message = sprintf($this->language->get('text_cancel_confirm'), $order_id, $cancelStatus);
        }

        return $this->responds->json([
            'status' => (string)$claim->getAvailableCancelStatus(),
            'version' => $claim->getVersion(),
            'message' => $message,
        ]);
    }

    public function cancelOrder() {
        try  {
            $this->shipment_service->cancel(
                $this->request->post['order_id'],
                $this->request->post['version'],
                $this->request->post['status']
            );
        } catch (ShipmentException $e) {
            return $this->responds->json(['error' => $this->language->get('error_during_cancellation')]);
        }
    }

    public function getTariffs() {
        $lat = $this->request->post['lat'];
        $lon = $this->request->post['lon'];

        try {
            if (empty($lat) || empty($lon)) {
                $tariffs = [];
            } else {
                $tariffs = $this->shipment_service->getTariffsList($lat, $lon);
            }

            if (empty($tariffs)) {
                $html = $this->responds->output('partial/_no_tariffs', []);
            } else {
                $html = $this->responds->output('partial/_tariffs', ['tariffs' => $tariffs]);
            }

            $labels = [];

            foreach ($tariffs as $tariff) {
                $labels[$tariff->getName()] = $tariff->getTitle();
            }

            return $this->responds->json([
                'html' => $html,
                'labels' => $labels,
            ]);
        } catch (YandexApiException $exception) {
            return $this->renderException($exception);
        }
    }

    public function createClaim() {
        $order_ids = array_column($this->request->post['destinations'], 'order_id');
        $tariff = filter_var($this->request->post['tariff'] ?? null, FILTER_SANITIZE_STRING);

        try {
            $this->shipment_service->createClaimForCalculation([
                'claim_link_key' => $this->request->post['claim_link_key'] ?? uniqid(),
                'source' => $this->request->post['source'],
                'destinations' => $this->request->post['destinations'],
                'due' => filter_var($this->request->post['due'] ?? null, FILTER_SANITIZE_STRING),
                'products' => $this->order_repository->getOrdersProductsByOrderIds($order_ids),
                'tariff' => empty($tariff) ? null : $tariff,
                'tariff_requirements' => $this->request->post['tariff_requirements'] ?? [],
            ]);
        } catch (Exception $exception) {
            return $this->renderException($exception);
        }

        return $this->responds->json([]);
    }

    public function getPrice() {
        $key = $this->request->post['claim_link_key'];

        try {
            $claim = $this->shipment_service->getClaimByKey($key);
        } catch (Exception $exception) {
            return $this->renderException($exception);
        }

        if (is_null($claim->getPrice())) {
            $response = [
                'calculated' => false,
            ];
        } else {
            $response = [
                'calculated' => true,
                'price' => round($claim->getPrice()->getValue(), 2),
                'tariff' => $claim->getTariffName(),
            ];
        }

        if ($claim->getWarnings()) {
            $response['warnings'] = $claim->getWarnings();
        }

        return $this->responds->json($response);
    }

    public function confirmClaim() {
        $order_ids = array_column($this->request->post['destinations'], 'order_id');

        $this->shipment_service->confirmClaim($this->request->post['claim_link_key'], $order_ids);

        $token = $this->session->data['user_token'];
        $this->session->data['success'] = $this->language->get('text_order_created');

        $this->response->redirect(
            $this->url->link('sale/order', "user_token=$token", true)
        );
    }

    public function getShipment() {
        if (!$this->shipment_service) {
            return '';
        }

        $order_id = $this->request->get['order_id'];
        if (empty($order_id)) {
            return '';
        }

        $shipment = $this->shipment_service->getForOrder($order_id);
        if (is_null($shipment)) {
            return '';
        }

        $this->responds->view('partial/_order_shipping_info', [
            'shipment' => (new ShipmentPresenter($this->language, $this->url, $this->session->data['user_token']))->present($shipment, $order_id),
        ]);
    }

    public function syncShipment() {
        $order_id = $this->request->get['order_id'];
        if (empty($order_id)) {
            return '';
        }

        try {
            $old_shipment = $this->shipment_service->getForOrder($order_id);
            $shipment = $this->shipment_service->syncShipmentForClaimByOrder($order_id);

            $service = new OrderStatusService(
                $this->db,
                $this->model_localisation_order_status,
                $this->setting_service->getOne('shipping_yandextaxi_change_status'),
                $this->language->get('message_auto_status_change_history_comment')
            );

            $service->changeIfNeeded($order_id, $old_shipment, $shipment);
        } catch (ShipmentException $e) {
            return $this->responds->json([
                'error' => $this->language->get('error_during_status_synchronization'),
            ]);
        }

        $this->responds->view('partial/_order_shipping_info', [
            'shipment' => (new ShipmentPresenter($this->language, $this->url, $this->session->data['user_token']))->present($shipment, $order_id),
        ]);
    }

    public function indexOrders() {
        $this->order_controller->index($this->request);
    }

    public function viewOrder() {
        $this->order_controller->view($this->request, $this->response);
    }

    public function indexWarehouses() {
        $this->warehouse_controller->index();
    }

    public function editWarehouse() {
        $this->warehouse_controller->edit($this->request, $this->response);
    }

    public function deleteWarehouse(){
        $this->warehouse_controller->delete($this->request);
    }

    public function cron() {
        try {
            $orderStatusService = new OrderStatusService(
                $this->db,
                $this->model_localisation_order_status,
                $this->setting_service->getOne('shipping_yandextaxi_change_status'),
                $this->language->get('message_auto_status_change_history_comment')
            );

            $this->shipment_service->syncShipments($orderStatusService);
        } catch (Exception $e) {
            echo 'An errors occurred during shipment synchronization: ' . $e->getMessage() . PHP_EOL;
            return;
        }

        echo 'Shipment information was synchronized' . PHP_EOL;
    }

    private function getDestinationsHtml(array $orders): array {
        $destinations = [];
        foreach ($orders as $i => $order) {
            $destinations[] = $this->responds->output('partial/_destination', [
                    'index' => $i,
                    'destination' => [
                        'order_id' => $order['order_id'],
                        'address' => $this->getOrderFullAddress($order),
                        'name' => "{$order['shipping_firstname']} {$order['shipping_lastname']}",
                        'phone' => $this->preparePhone($order['telephone']),
                        'comment' => $this->getCommentPlaceholder($order),
                    ]
            ]);
        }

        return $destinations;
    }

    private function getDestination(array $order): array {
        return [
            'order_id' => $order['order_id'],
            'address' => $this->getOrderFullAddress($order),
            'name' => "{$order['shipping_firstname']} {$order['shipping_lastname']}",
            'phone' => $this->preparePhone($order['telephone']),
            'comment' => $this->getCommentPlaceholder($order),
        ];
    }

    private function getCommentPlaceholder(array $order): string
    {
        $placeholder = $this->language->get('text_intercom') . ':';

        $comment = trim($order['comment']);
        if (empty($comment)) {
            return $placeholder;
        }

        $placeholder .= PHP_EOL . "{$this->language->get('text_order_comment')}: $comment";
        return $placeholder;
    }

    private function getOrderFullAddress(array $order): string {
        return "{$order['shipping_city']}, {$order['shipping_address_1']}";
    }

    private function preparePhone(string $phone): string {
        try {
            return PhoneFormatter::format($phone);
        } catch (NumberParseException $exception) {
            return $phone;
        }
    }

    private function getEditableSettingValues(): array {
        $settings = $this->setting_service->getEditable();

        foreach ($settings as $key => $value) {
            if (isset($this->request->post[$key])) {
                $settings[$key] = $this->request->post[$key];
            }
        }

        return $settings;
    }

    /**
     * @return string[]
     */
    private function getAssemblyMinutes(): array {
        $minutes = [];
        foreach (range(0, 100, 10) as $step) {
            $minutes[$step] = $step . ' ' . $this->language->get('text_minutes');
        }

        return $minutes;
    }

    private function validateSettings(array $data): bool {
        if (!$this->user->hasPermission('modify', 'extension/shipping/yandextaxi')) {
            $this->session->data['error'] = $this->language->get('error_permission');
            return false;
        }

        $require = [];

        if (empty($data['shipping_yandextaxi_api_token'])) {
            $require[] = $this->language->get('entry_yandex_taxi_api_token');
        }
        if (empty($data['shipping_yandextaxi_geo_coder_api_token'])) {
            $require[] = $this->language->get('entry_yandex_geo_coder_api_token');
        }

        if (!empty($require)) {
            $message = $this->language->get('error_settings_validation') . ' ' . implode(', ', $require);
            $this->session->data['error'] = $message;
        }

        return empty($require);
    }

    private function getBaseUrl(): ?string {
        if (isset($this->request->server['HTTPS'])
            && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            return $this->config->get('config_ssl');
        }

        return $this->config->get('config_url');
    }

    private function renderException(Exception $exception): void {
        if ($exception instanceof NotAuthorizedException) {
            $this->responds->json([
                'error' => implode(' ', [
                    $this->language->get('yandex_go_delivery_token_not_works'),
                    $this->language->get('yandex_go_delivery_token_not_works_description'),
                ]),
            ]);
            return;
        }

        $this->responds->json([
            'error' => $exception->getMessage(),
        ]);
    }
}
