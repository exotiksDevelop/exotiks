<?php

namespace YandexTaxi\Delivery\Services;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\Claim\Driver;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\Claims;

/**
 * Class DriverService
 *
 * @package YandexTaxi\Delivery\Services
 */
class DriverService
{
    /** @var Claims */
    private $claims;

    public function __construct(Claims $claims)
    {
        $this->claims = $claims;
    }

    /**
     * @param string $claimId
     *
     * @return Driver|null
     * @throws YandexApiException
     */
    public function getForClaim(string $claimId): ?Driver
    {
        return $this->claims->get($claimId)->getDriver();
    }
}
