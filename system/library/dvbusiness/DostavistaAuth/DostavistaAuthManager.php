<?php


namespace DvBusiness\DostavistaAuth;


use DvBusiness\DvOptions;

class DostavistaAuthManager
{
    public static function logoutFromDostavista(DvOptions $dvOptions)
    {
        $settings = $dvOptions->getSettings();
        $settings['shipping_dvbusiness_cms_module_api_prod_auth_token'] = '';
        $settings['shipping_dvbusiness_cms_module_api_test_auth_token'] = '';
        $dvOptions->updateSettings($settings);
    }
}
