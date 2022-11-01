<?php

namespace DvBusiness\BankCard;

use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\Response\BankCardsResponseModel;
use DvBusiness\GeneralCache\Cache;
use DvBusiness\GeneralCache\CacheItem;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;

class BankCardsManager
{
    const BANK_CARD_CACHE_TTL = 86400;
    /**
     * @param DvCmsModuleApiClient $dvCmsModuleApiClient
     * @param int $shopId
     * @return BankCard[]|array
     */
    public static function getShopBankCards(DvCmsModuleApiClient $dvCmsModuleApiClient): array
    {
        $cacheKey = static::getShopCardsCacheKey(); // Ключ кеша

        $cache = new Cache(static::BANK_CARD_CACHE_TTL);

        if ($cache->hasItem($cacheKey)) {
            $cacheItem = $cache->getItem($cacheKey);
            return BankCardCollectionSerializer::unserialize(
                $cacheItem->get()
            );
        } else {
            try {
                $responseData = $dvCmsModuleApiClient->bankCards();
                if ($responseData->isSuccessful()) {
                    $bankCardsResponceModel = new BankCardsResponseModel($responseData->getData());

                    $cards = $bankCardsResponceModel->getCards();

                    // Закешируем полученный результат
                    $cacheItem = new CacheItem(
                        $cacheKey,
                        BankCardCollectionSerializer::serialize($cards),
                        static::BANK_CARD_CACHE_TTL
                    );
                    $cache->save($cacheItem);
                    return $cards;
                }
            } catch (DvCmsModuleApiHttpException $e) {

            }
        }
        return [];
    }

    public static function updateShopBankCardsCache(DvCmsModuleApiClient $dvCmsModuleApiClient): array
    {
        $cache = new Cache(static::BANK_CARD_CACHE_TTL);

        $cacheKey   = static::getShopCardsCacheKey(); // Ключ кеша
        if ($cache->hasItem($cacheKey)) {
            $cache->deleteItem($cacheKey);
        }

        return static::getShopBankCards($dvCmsModuleApiClient);
    }

    public static function getShopCardsCacheKey(): string
    {
        return "shop:cards";
    }
}
