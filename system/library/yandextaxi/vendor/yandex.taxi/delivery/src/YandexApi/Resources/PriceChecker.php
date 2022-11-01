<?php

namespace YandexTaxi\Delivery\YandexApi\Resources;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\CheckPrice\CheckPriceResult;
use YandexTaxi\Delivery\Entities\ClaimItem\Money;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\Entities\RoutePoint\Address;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;

/**
 * Class PriceChecker
 *
 * @package YandexTaxi\Delivery\YandexApi\Resources
 */
class PriceChecker extends Resource
{
    protected function getBasePath(): string
    {
        return 'cargo-matcher';
    }

    /**
     * @param ClaimItem[] $items
     * @param Address[]   $points
     *
     * @return CheckPriceResult
     * @throws YandexApiException
     */
    public function calculate(array $items, array $points): CheckPriceResult
    {
        $result = $this->call('check-price', Client::API_V1, [
            'json' => [
                'items' => $this->prepareItems($items),
                'route_points' => $this->preparePoints($points),
            ],
        ]);

        return $this->mapResult($result);
    }

    /**
     * @param ClaimItem[] $items
     *
     * @return array
     */
    private function prepareItems(array $items): array
    {
        $prepared = [];

        foreach ($items as $item) {
            $prepared[] = [
                'size' => [
                    'length' => $item->getSize()->getLength(),
                    'width' => $item->getSize()->getWidth(),
                    'height' => $item->getSize()->getHeight(),
                ],
                'weight' => $item->getWeight(),
                'quantity' => $item->getQuantity(),
            ];
        }

        return $prepared;
    }

    /**
     * @param Address[] $points
     *
     * @return array
     */
    private function preparePoints(array $points): array
    {
        $prepared = [];

        foreach ($points as $point) {
            $prepared[] = [
                'coordinates' => [$point->getLon(), $point->getLat()],
            ];
        }

        return $prepared;
    }

    private function mapResult(array $raw): CheckPriceResult
    {
        return new CheckPriceResult(
            new Money($raw['price'], $raw['currency_rules']['code']),
            $raw['eta'],
            $raw['requirements']['taxi_class'] ?? ''
        );
    }
}
