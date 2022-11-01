<?php

namespace DvBusiness\DostavistaAuth;

use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\DvOptions;
use DvBusiness\GeneralCache\Cache;
use DvBusiness\GeneralCache\CacheItem;

class DostavistaApiTokenVerifier
{
    public function __construct(DvOptions $dvOptions)
    {
        $this->dvOptions = $dvOptions;
    }

    public function isCmsModuleApiTokenValid(): bool
    {
        if (!$this->dvOptions->getAuthToken()) {
            return false;
        }

        $dvCmsModuleApiClient = new DvCmsModuleApiClient(
            $this->dvOptions->getApiUrl(),
            $this->dvOptions->getAuthToken()
        );

        // Закешируем результат
        $cacheKey = "dv:api:token:{$this->dvOptions->getAuthToken()}:valid";
        $cacheTtl = 86400; // Храним сутки

        $cache = new Cache($cacheTtl);

        if ($cache->hasItem($cacheKey)) {
            $cacheResult = $cache->getItem($cacheKey);
            return (bool) $cacheResult->get();
        } else {
            try {
                $responseResult = false;
                // Оправим любой запрос на достависту и обработаем ответ
                $response = $dvCmsModuleApiClient->getVehicleTypes();
                $errors = $response->getErrors();
                if ($response->isSuccessful()) {
                    $responseResult = true;
                } elseif (!$response->isSuccessful() && isset($errors[0]) && $errors[0] === 'invalid_auth_token') {
                    $responseResult = false;
                }
            } catch (DvCmsModuleApiHttpException $exception) {
                return true;
            } catch (\Throwable $exception) {
                // Если апи урл - это валидный Url, то не считаем, что токен невалидный
                if (!filter_var($this->dvOptions->getApiUrl(), FILTER_VALIDATE_URL)) {
                    $responseResult = false;
                } else {
                    return true;
                }
            }

            $cacheItem = new CacheItem(
                $cacheKey,
                (int) $responseResult,
                $cacheTtl
            );

            $cache->save($cacheItem);

            return (bool) $responseResult;
        }

        return false;
    }
}
