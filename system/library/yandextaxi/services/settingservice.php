<?php

namespace YandexTaxi\Services;

use \ModelSettingSetting;

/**
 * Class SettingService
 *
 * @package YandexTaxi\Services
 */
class SettingService
{
    private const BASE = 'shipping_yandextaxi';

    /** @var ModelSettingSetting */
    private $model_setting_setting;

    public function __construct($model_setting_setting) {
        $this->model_setting_setting = $model_setting_setting;
    }

    public function getAll(): array {
        return array_merge($this->getEditable(), [
            self::BASE . '_default_warehouse_id' => $this->getOne(self::BASE . '_default_warehouse_id') ?? null,
            self::BASE . '_warehouse_was_synced' => $this->getOne(self::BASE . '_warehouse_was_synced') ?? false,
            self::BASE . '_warehouse_contact_name' => $this->getOne(self::BASE . '_warehouse_contact_name'),
            self::BASE . '_warehouse_contact_phone' => $this->getOne(self::BASE . '_warehouse_contact_phone'),
            self::BASE . '_warehouse_end_time' => $this->getOne(self::BASE . '_warehouse_end_time'),
            self::BASE . '_warehouse_start_time' => $this->getOne(self::BASE . '_warehouse_start_time'),
            self::BASE . '_warehouse_address' => $this->getOne(self::BASE . '_warehouse_address'),
            self::BASE . '_warehouse_latitude' => $this->getOne(self::BASE . '_warehouse_latitude'),
            self::BASE . '_warehouse_longitude' => $this->getOne(self::BASE . '_warehouse_longitude'),
            self::BASE . '_warehouse_email' => $this->getOne(self::BASE . '_warehouse_email'),
        ]);
    }

    public function getOne(string $key) {
        return $this->model_setting_setting->getSettingValue($key);
    }

    public function getEditable(): array {
        return [
            self::BASE . '_api_token' => $this->getOne(self::BASE . '_api_token'),
            self::BASE . '_geo_coder_api_token' => $this->getOne(self::BASE . '_geo_coder_api_token'),
            self::BASE . '_assembly_delay_minutes' => $this->getOne(self::BASE . '_assembly_delay_minutes'),
            self::BASE . '_geo_zone_id' => $this->getOne(self::BASE . '_geo_zone_id'),
            self::BASE . '_status' => $this->getOne(self::BASE . '_status') ?? 1,
            self::BASE . '_sort_order' => $this->getOne(self::BASE . '_sort_order'),
            self::BASE . '_change_status' => $this->getOne(self::BASE . '_change_status'),
            self::BASE . '_cart_enabled' => $this->getOne(self::BASE . '_cart_enabled'),
            self::BASE . '_cart_shipping_method_title' => $this->getOne(self::BASE . '_cart_shipping_method_title'),
            self::BASE . '_free_shipping_enabled' => $this->getOne(self::BASE . '_free_shipping_enabled'),
            self::BASE . '_free_shipping_value' => $this->getOne(self::BASE . '_free_shipping_value'),
            self::BASE . '_fixed_shipping_enabled' => $this->getOne(self::BASE . '_fixed_shipping_enabled'),
            self::BASE . '_fixed_shipping_value' => $this->getOne(self::BASE . '_fixed_shipping_value'),
            self::BASE . '_extra_charge_shipping_value' => $this->getOne(self::BASE . '_extra_charge_shipping_value'),
            self::BASE . '_discount_shipping_enabled' => $this->getOne(self::BASE . '_discount_shipping_enabled'),
            self::BASE . '_discount_shipping_value' => $this->getOne(self::BASE . '_discount_shipping_value'),
            self::BASE . '_discount_shipping_from' => $this->getOne(self::BASE . '_discount_shipping_from'),
        ];
    }

    public function storeAll(array $new): void {
        $settings = $this->getAll();

        foreach ($new as $key => $value) {
            $settings[$key] = $value;
        }

        $this->model_setting_setting->editSetting(self::BASE, $settings);
    }

    public function storeOne($key, $value): void {
        $settings = $this->getAll();
        $settings[$key] = $value;
        $this->storeAll($settings);
    }
}
