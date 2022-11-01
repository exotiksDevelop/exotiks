<?php

namespace YandexTaxi\Services;

use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;

/**
 * Class AvailableTariffChecker
 *
 * @package YandexTaxi\Services
 */
class AvailableTariffChecker
{
    private const LAT = 55.734148; // Yandex Office, coordinate to check tariffs
    private const LON = 37.5865588;

    /** @var Tariffs */
    private $tariffs;

    /**
     * AvailableTariffChecker constructor.
     *
     * @param Tariffs $tariffs
     */
    public function __construct(Tariffs $tariffs)
    {
        $this->tariffs = $tariffs;
    }

    /**
     * @param float|null $lat
     * @param float|null $lon
     *
     * @return bool
     * @throws YandexApiException
     */
    public function isAvailable(float $lat = null, float $lon = null): bool
    {
        if (is_null($lat) || is_null($lon)) {
            $tariffs = $this->tariffs->getAllForPoint(self::LAT, self::LON);
        } else {
            $tariffs = $this->tariffs->getAllForPoint($lat, $lon);
        }

        return !empty($tariffs);
    }
}
