<?php

namespace YandexTaxi\Delivery\GeoCoding;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\GeoCoding\Exceptions\GeoCodingException;

/**
 * Interface GeoCoderInterface
 *
 * @package YandexTaxi\Delivery\GeoCoding
 */
interface GeoCoderInterface
{
    /**
     * @param string $address
     *
     * @return Point
     * @throws GeoCodingException
     */
    public function decode(string $address): Point;
}
