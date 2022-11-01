<?php

namespace DvBusiness\DostavistaAuth;

use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\ApiClient\Response\ClientProfileResponseModel;
use DvBusiness\DvOptions;
use DvBusiness\GeneralCache\Cache;
use DvBusiness\GeneralCache\CacheItem;

class DostavistaClientManager
{
    public static function getAllowedPaymentTypes(DvOptions $dvOptions): array
    {
        $paymentMethods = [];
        $cacheKey = "dv:payment:methods:{$dvOptions->getAuthToken()}";
        $cacheTtl = 86400; // Храним сутки

        $cache = new Cache($cacheTtl);

        $dvCmsModuleApiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());

        if ($cache->hasItem($cacheKey)) {
            $cacheItem = $cache->getItem($cacheKey);
            $paymentMethods = unserialize($cacheItem->get());
        } else {
            // Получим данные профиля из достависты
            try {
                $dvResponse = $dvCmsModuleApiClient->getClientProfile();
                if ($dvResponse->isSuccessful() && isset($dvResponse->getData()['client'])) {
                    $clientProfileResponseModel = new ClientProfileResponseModel($dvResponse->getData()['client']);
                    $paymentMethods = $clientProfileResponseModel->getPaymentMethods();
                }
            } catch (DvCmsModuleApiHttpException $e) {
                return [];
            } catch (\Throwable $exception) {
                return [];
            }

            $cacheItem = new CacheItem(
                $cacheKey,
                serialize($paymentMethods),
                $cacheTtl
            );

            $cache->save($cacheItem);
        }

        return $paymentMethods;
    }
}
