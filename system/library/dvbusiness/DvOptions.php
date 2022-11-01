<?php

namespace DvBusiness;

use DvBusiness\ModuleConfig\ModuleConfig;
use ModelSettingSetting;

class DvOptions
{
    const DEFAULT_VEHICLE_TYPE_ID = 6;

    /** @var array */
    private $settings;

    /** @var ModelSettingSetting */
    private $settingModel;

    /**
     * @param ModelSettingSetting $settingModel
     */
    public function __construct($settingModel)
    {
        $this->settingModel = $settingModel;
        $this->settings = $this->settingModel->getSetting('shipping_dvbusiness');
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function updateSettings(array $data)
    {
        $this->settingModel->editSetting('shipping_dvbusiness', $data);
        $this->settings = $this->settingModel->getSetting('shipping_dvbusiness');
    }

    public function getAuthToken(): string
    {
        return $this->getIsApiTestServer() ? $this->getTestAuthToken() : $this->getProdAuthToken();
    }

    public function getBusinessApiAuthToken(): string
    {
        return $this->settings['shipping_dvbusiness_auth_token'] ?? '';
    }

    public function getProdAuthToken(): string
    {
        return $this->settings['shipping_dvbusiness_cms_module_api_prod_auth_token'] ?? '';
    }

    public function getTestAuthToken(): string
    {
        return $this->settings['shipping_dvbusiness_cms_module_api_test_auth_token'] ?? '';
    }

    public function getCmsModuleApiTestUrl(): string
    {
        return (new ModuleConfig())->getDvCmsModuleApiTestUrl();
    }

    public function getCmsModuleApiProdUrl(): string
    {
        return (new ModuleConfig())->getDvCmsModuleApiProdUrl();
    }

    public function getApiUrl(): string
    {
        return $this->getIsApiTestServer() ? $this->getCmsModuleApiTestUrl() : $this->getCmsModuleApiProdUrl();
    }

    public function getIsApiTestServer(): bool
    {
        return (bool) $this->settings['shipping_dvbusiness_is_api_test_server'] ?? false;
    }

    public function getDefaultPickupWarehouseId(): int
    {
        return (int) ($this->settings['shipping_dvbusiness_default_pickup_warehouse_id'] ?? 0);
    }

    public function getDefaultOrderWeightKg(): int
    {
        return max(0, (int) ($this->settings['shipping_dvbusiness_default_order_weight_kg'] ?? 1));
    }

    public function getDostavistaPaymentMarkupAmount(): float
    {
        return round(max(0, (float) ($this->settings['shipping_dvbusiness_dostavista_payment_markup_amount'] ?? 0)), 2);
    }

    public function getDostavistaPaymentDiscountAmount(): float
    {
        return round(max(0, (float) ($this->settings['shipping_dvbusiness_dostavista_payment_discount_amount'] ?? 0)), 2);
    }

    public function getFixOrderPaymentAmount(): float
    {
        return round(max(0, (float) ($this->settings['shipping_dvbusiness_fix_order_payment_amount'] ?? 0)), 2);
    }

    public function getFreeDeliveryOpencartOrderSum(): int
    {
        return max(0, (int) ($this->settings['shipping_dvbusiness_free_delivery_opencart_order_sum'] ?? 0));
    }

    public function getDefaultVehicleTypeId(): int
    {
        return (int) ($this->settings['shipping_dvbusiness_default_vehicle_type_id'] ?? static::DEFAULT_VEHICLE_TYPE_ID);
    }

    public function isInsuranceEnabled(): bool
    {
        return (bool) ($this->settings['shipping_dvbusiness_insurance_enabled'] ?? true);
    }

    public function isBuyoutEnabled(): bool
    {
        return (bool) ($this->settings['shipping_dvbusiness_buyout_enabled'] ?? false);
    }

    public function isMatterWeightPrefixEnabled(): bool
    {
        return (bool) ($this->settings['shipping_dvbusiness_matter_weight_prefix_enabled'] ?? false);
    }

    public function isContactPersonNotificationEnabled(): bool
    {
        return (bool) ($this->settings['shipping_dvbusiness_contact_person_notification_enabled'] ?? false);
    }

    public function getDeliveryPointNotePrefix(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_delivery_point_note_prefix'] ?? '');
    }

    public function getOpenCartCashPaymentCode(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_opencart_cash_payment_code'] ?? '');
    }

    public function getApiCallbackSecretKey(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_cms_module_api_callback_secret_key'] ?? '');
    }

    public function getIntegrationOrderStatusDraft(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_draft'] ?? '');
    }

    public function getIntegrationOrderStatusAvailable(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_available'] ?? '');
    }

    public function getIntegrationOrderStatusCourierAssigned(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_courier_assigned'] ?? '');
    }

    public function getIntegrationOrderStatusActive(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_active'] ?? '');
    }

    public function getIntegrationOrderStatusParcelPickedUp(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_parcel_picked_up'] ?? '');
    }

    public function getIntegrationOrderStatusCourierDeparted(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_courier_departed'] ?? '');
    }

    public function getIntegrationOrderStatusCourierArrived(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_courier_arrived'] ?? '');
    }

    public function getIntegrationOrderStatusCompleted(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_completed'] ?? '');
    }

    public function getIntegrationOrderStatusFailed(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_failed'] ?? '');
    }

    public function getIntegrationOrderStatusCanceled(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_canceled'] ?? '');
    }

    public function getIntegrationOrderStatusDelayed(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_integration_order_status_delayed'] ?? '');
    }

    public function getDeliveryServiceTitle(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_delivery_title'] ?? '');
    }

    public function getDeliveryServiceDescription(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_delivery_description'] ?? '');
    }

    public function getDefaultPaymentCardId(): int
    {
        return (int) ($this->settings['shipping_dvbusiness_default_payment_card_id'] ?? 0);
    }

    public function getWizardLastFinishedStep(): int
    {
        return (int) ($this->settings['shipping_dvbusiness_wizard_last_finished_step'] ?? 0);
    }

    public function getDefaultPaymentType(): string
    {
        return (string) ($this->settings['shipping_dvbusiness_default_payment_type'] ?? '');
    }
}
