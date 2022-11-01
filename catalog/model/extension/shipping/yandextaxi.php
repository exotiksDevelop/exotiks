<?php

use YandexTaxi\Delivery\GeoCoding\YandexMaps\YandexGeoCoder;
use YandexTaxi\Repositories\WarehouseRepository;
use YandexTaxi\Services\SettingService;
use YandexTaxi\YandexTaxi;
use YandexTaxi\Services\DefaultWarehouseFinder;
use YandexTaxi\Services\ClientService;
use YandexTaxi\Delivery\YandexApi\Resources\PriceChecker;
use YandexTaxi\Repositories\ProductRepository;
use YandexTaxi\Services\PriceManager;

/**
 * Class ModelExtensionShippingYandexTaxi
 *
 * @property-read YandexTaxi                       $yandextaxi
 * @property-read ModelExtensionShippingYandexTaxi $model_extension_shipping_yandextaxi
 * @property-read \Language                        $language
 * @property-read \ModelSettingSetting             $model_setting_setting
 * @property-read WarehouseRepository              $warehouse_repository
 * @property-read ProductRepository                $product_repository
 * @property-read PriceChecker                     $price_checker
 * @property-read YandexGeoCoder                   $geo_coder
 */
class ModelExtensionShippingYandexTaxi extends Model {
    private const CODE = 'yandextaxi';

    /** @var ClientService */
    private $client_service;

    /** @var SettingService */
    private $setting_service;

    /** @var boolean */
    private $enabled;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->init();

        $this->setting_service = new SettingService($this->model_setting_setting);
        $this->enabled = $this->isEnabled();

        if (!$this->enabled) {
            return;
        }

        $this->client_service = new ClientService(
            $this->price_checker,
            $this->geo_coder,
            new DefaultWarehouseFinder($this->setting_service, $this->warehouse_repository),
            $this->product_repository
        );
    }

    private function init(): void {
        $this->load->model('extension/shipping/yandextaxi');
        $this->load->model('setting/setting');
        $this->load->language('extension/shipping/yandextaxi');
        $this->load->library('yandextaxi/yandextaxi');
        $this->load->model('localisation/currency');
    }

    function getQuote($address) {
        if (!$this->enabled) {
            return[];
        }

        $total = $this->cart->getSubTotal();

        if ($this->isFreeDelivery($total)) {
            return $this->getRate(0, $total);
        }

        if ($this->isFixedPriceOn()) {
            return $this->getRate($this->getFixedPrice(), $total);
        }

        try {
            $price = $this->client_service->calculateSum($this->cart->getProducts(), $address);
            $priceManager = new PriceManager(
                $this->getPriceExtraPercentValue(),
                $this->isDiscountOn(),
                $this->getDiscountValue(),
                $this->getDiscountPriceFromValue()
            );
            $price = $priceManager->prepare($price, (float) $total);

            return $this->getRate($price, $total);
        } catch (Exception $exception) {
            // if error not show shipping method
            return [];
        }
    }

    private function getRate(float $cost, float $total): array {
        $text = $this->currency->format($cost, $this->config->get('config_currency'));
        $text .= $this->getExtraText($cost, $total);

        $quote_data[self::CODE] = [
            'code' => self::CODE . '.' . self::CODE,
            'title' => $this->getTitle(),
            'cost' => $cost,
            'tax_class_id' => $this->config->get('shipping_yandextaxi_tax_class_id'),
            'text' => $text,
        ];

        return [
            'code' => self::CODE,
            'title' => $this->language->get('yandex_go_delivery'),
            'quote' => $quote_data,
            'sort_order' => $this->config->get('shipping_yandextaxi_sort_order'),
            'error' => false,
        ];
    }

    private function getExtraText(float $deliveryCost, float $total): string {
        if (!$this->isFreeDeliveryAllowed()) {
            return '';
        }

        if ($deliveryCost === 0) {
            return '';
        }

        $diffCost = ($this->getOrderCostForFree() * 100 - $total * 100) / 100;

        return ' ' . sprintf(
            $this->language->get('order_more_for_free'),
            $this->currency->format($diffCost, $this->config->get('config_currency'))
        );
    }

    private function isFreeDelivery(float $cost): bool {
        if (!$this->isFreeDeliveryAllowed()) {
            return false;
        }

        return $cost >= $this->getOrderCostForFree();
    }

    private function isEnabled(): bool {
        return $this->setting_service->getOne('shipping_yandextaxi_cart_enabled') == 1;
    }

    private function isFreeDeliveryAllowed(): bool {
        return $this->setting_service->getOne('shipping_yandextaxi_free_shipping_enabled') == 1;
    }

    private function getOrderCostForFree(): int {
        return (int) $this->setting_service->getOne('shipping_yandextaxi_free_shipping_value');
    }

    private function getFixedPrice(): int {
        return (int) $this->setting_service->getOne('shipping_yandextaxi_fixed_shipping_value');
    }

    private function isFixedPriceOn(): bool {
        return $this->setting_service->getOne('shipping_yandextaxi_fixed_shipping_enabled') == 1;
    }

    private function getPriceExtraPercentValue(): float {
        return (float) $this->setting_service->getOne('shipping_yandextaxi_extra_charge_shipping_value');
    }

    private function isDiscountOn(): bool {
        return (float) $this->setting_service->getOne('shipping_yandextaxi_discount_shipping_enabled');
    }

    private function getDiscountValue(): float {
        return (float) $this->setting_service->getOne('shipping_yandextaxi_discount_shipping_value');
    }

    private function getDiscountPriceFromValue(): float {
        return (float) $this->setting_service->getOne('shipping_yandextaxi_discount_shipping_from');
    }

    private function getTitle(): string {
        $key = $this->setting_service->getOne('shipping_yandextaxi_cart_shipping_method_title');

        $labels = [
            'delivery' => $this->language->get('yandex_go_delivery'),
            'express_delivery' => $this->language->get('yandex_go_delivery_express'),
        ];

        return $labels[$key] ?? $labels['delivery'];
    }
}
