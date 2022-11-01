<?php

namespace DvBusiness\Updater;

use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\DvOptions;
use ModelSettingSetting;

class UpdateManager
{
    /** @var ModelSettingSetting */
    private $settingsModel;

    /** @var string */
    private $callbackUrl;

    /**
     * @param ModelSettingSetting $settingsModel
     * @param string $callbackUrl
     */
    public function __construct($settingsModel, string $callbackUrl)
    {
        $this->settingsModel = $settingsModel;
        $this->callbackUrl = $callbackUrl;
    }

    public function update()
    {
        $this->changeBusinessApiToCmsModuleApi();
    }

    public function changeBusinessApiToCmsModuleApi()
    {
        $dvOptions = new DvOptions($this->settingsModel);
        $settings  = $dvOptions->getSettings();

        $isUpdated = false;
        $isApiTest = isset($settings['shipping_dvbusiness_api_url']) ? strpos($settings['shipping_dvbusiness_api_url'], 'robotapitest') !== false : true;
        $oldApiToken = $settings['shipping_dvbusiness_auth_token'] ?? '';

        if (!isset($settings['shipping_dvbusiness_is_api_test_server'])) {
            $settings['shipping_dvbusiness_is_api_test_server'] = $isApiTest;
            $isUpdated = true;
        }

        if (!isset($settings['shipping_dvbusiness_cms_module_api_test_auth_token'])) {
            $settings['shipping_dvbusiness_cms_module_api_test_auth_token'] = $isApiTest ? $oldApiToken : '';
            $isUpdated = true;
        }

        if (!isset($settings['shipping_dvbusiness_cms_module_api_prod_auth_token'])) {
            $settings['shipping_dvbusiness_cms_module_api_prod_auth_token'] = !$isApiTest ? $oldApiToken : '';
            $isUpdated = true;
        }

        if (!isset($settings['shipping_dvbusiness_cms_module_api_callback_secret_key'])) {
            $settings['shipping_dvbusiness_cms_module_api_callback_secret_key'] = $settings['shipping_dvbusiness_api_callback_secret_key'] ?? "";
            $isUpdated = true;
        }

        if ($isUpdated) {
            $dvOptions->updateSettings($settings);

            $apiUrl = $isApiTest
                ? $dvOptions->getCmsModuleApiTestUrl()
                : $dvOptions->getCmsModuleApiProdUrl();
            $authToken = $dvOptions->getAuthToken();
            try {
                $dvBusinessApiClient = new DvCmsModuleApiClient($apiUrl, $authToken);
                $apiEditSettingsResponse = $dvBusinessApiClient->editApiSettings($this->callbackUrl);
                $settings['shipping_dvbusiness_cms_module_api_callback_secret_key'] = $apiEditSettingsResponse->getCallbackSecretKey();
            } catch (DvCmsModuleApiHttpException $businessApiHttpException) {

            } catch (\Throwable $e) {
            }

            $dvOptions->updateSettings($settings);
        }
    }
}
