<?php

namespace YandexTaxi;

if (!defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN')) {
    define('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN', 1); // define constant that prevents core direct call
}

use YandexTaxi\Delivery\Services\ClaimService;
use YandexTaxi\Delivery\GeoCoding\GeoCoderInterface;
use YandexTaxi\Delivery\GeoCoding\YandexMaps\YandexGeoCoder;
use YandexTaxi\Delivery\Services\EventService;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Resources\Claims;
use YandexTaxi\Delivery\YandexApi\Resources\DriverPhones;
use YandexTaxi\Delivery\YandexApi\Resources\Journal;
use YandexTaxi\Delivery\YandexApi\Resources\PriceChecker;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;
use YandexTaxi\Http\CurlClient;
use YandexTaxi\Repositories\ClaimLinkRepository;
use YandexTaxi\Repositories\CursorRepository;
use YandexTaxi\Repositories\OrderRepository;
use YandexTaxi\Repositories\ShipmentRepository;
use YandexTaxi\Repositories\WarehouseRepository;
use YandexTaxi\Repositories\ProductRepository;
use YandexTaxi\Services\ReferralSourceFinder;
use YandexTaxi\Services\ShipmentService;
use YandexTaxi\Services\OrderService;
use YandexTaxi\Delivery\Http\Client as HttpClient;

/**
 * Class YandexTaxi
 *
 * @property-read \Loader              $load
 * @property-read \DB                  $db
 * @property-read \ModelSettingSetting $model_setting_setting
 *
 * @package YandexTaxi
 */
class YandexTaxi extends Library {
    /** @var HttpClient */
    private $httpClient;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->autoload();
        $this->init();
    }

    private function autoload(): void {
        $autoloader = __DIR__ . '/vendor/autoload.php';

        if (file_exists($autoloader)) {
            require_once __DIR__ . '/vendor/autoload.php';
        }
    }

    private function init(): void {
        $this->load->model('setting/setting');
        $this->httpClient = new CurlClient();

        $this->register();
    }

    public function makeGeoCoder(string $token): GeoCoderInterface {
        return new YandexGeoCoder($this->httpClient, $token);
    }

    private function register(): void {
        $this->load->library('yandextaxi/utils/responds');

        $this->registerGeoCoder();
        $this->registerShipmentService();
        $this->registerOrderService();
        $this->registerOrderRepository();
        $this->registerWarehouseRepository();
        $this->registerProductRepository();
    }

    private function registerGeoCoder()
    {
        $token = $this->getSettingValue('shipping_yandextaxi_geo_coder_api_token');
        if (empty($token)) {
            return;
        }

        $this->registry->set('geo_coder', $this->makeGeoCoder($token));
    }

    private function registerShipmentService(): void {
        $config = require __DIR__ . '/config/config.php';

        $api_token = $this->getSettingValue('shipping_yandextaxi_api_token');
        $geo_coder_api_token = $this->getSettingValue('shipping_yandextaxi_geo_coder_api_token');
        if (empty($api_token) && empty($api_token)) {
            return;
        }

        $claim_link_repository = new ClaimLinkRepository($this->db);
        $shipment_repository = new ShipmentRepository($this->db);
        $cursor_repository = new CursorRepository($this->db);

        $api = new Client(
            $this->httpClient,
            $api_token,
            !$config['use_test_env'],
            ReferralSourceFinder::find(),
            $this->getLocale()
        );
        $claims = new Claims($api);
        $tariffs = new Tariffs($api);
        $driver_phones = new DriverPhones($api);
        $journal = new Journal($api);
        $geo_coder = $this->makeGeoCoder($geo_coder_api_token);
        $event_service = new EventService($journal, $cursor_repository);

        $claim_service = new ClaimService($claim_link_repository, $claims, $geo_coder);
        $delay = $this->getSettingValue('shipping_yandextaxi_assembly_delay_minutes') ?? 0;

        $this->registry->set('shipment_service', new ShipmentService(
            $claim_service,
            $event_service,
            $shipment_repository,
            $tariffs,
            $driver_phones,
            $delay
        ));
        $this->registry->set('tariffs', $tariffs);
        $this->registry->set('price_checker', new PriceChecker($api));
    }

    private function registerOrderService(): void {
        $shipment_repository = new ShipmentRepository($this->db);

        $this->registry->set('order_service', new OrderService($shipment_repository));
    }

    private function registerOrderRepository(): void {
        $this->registry->set('order_repository', new OrderRepository($this->db));
    }

    private function registerWarehouseRepository(): void{
        $this->registry->set('warehouse_repository', new WarehouseRepository($this->db));
    }

    private function registerProductRepository(): void{
        $this->registry->set('product_repository', new ProductRepository($this->db));
    }

    private function getSettingValue(string $key)
    {
        return $this->model_setting_setting->getSettingValue($key);
    }
    
    private function getLocale(): string {
        return $this->config->get('config_admin_language');
    }
}
