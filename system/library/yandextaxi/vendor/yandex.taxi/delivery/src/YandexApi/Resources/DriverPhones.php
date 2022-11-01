<?php

namespace YandexTaxi\Delivery\YandexApi\Resources;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\DriverPhone\DriverPhone;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;

/**
 * Class DriverPhones
 *
 * @package YandexTaxi\Delivery\YandexApi\Resources
 */
class DriverPhones extends Resource
{
    /**
     * @param string $claimId
     *
     * @return DriverPhone
     * @throws YandexApiException
     */
    public function get(string $claimId): DriverPhone
    {
        $result = $this->call(
            'driver-voiceforwarding',
            Client::API_V1,
            ['json' => ['claim_id' => $claimId]]
        );

        return new DriverPhone($result['phone'], $result['ext']);
    }

    protected function getBasePath(): string
    {
        return 'cargo/integration';
    }
}
