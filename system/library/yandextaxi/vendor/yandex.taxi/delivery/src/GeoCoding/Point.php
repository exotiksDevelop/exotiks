<?php

namespace YandexTaxi\Delivery\GeoCoding;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Point
 *
 * @package YandexTaxi\Delivery\GeoCoding
 */
class Point
{
    /** @var float */
    private $lat;

    /** @var float */
    private $lon;

    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLon(): float
    {
        return $this->lon;
    }
}
