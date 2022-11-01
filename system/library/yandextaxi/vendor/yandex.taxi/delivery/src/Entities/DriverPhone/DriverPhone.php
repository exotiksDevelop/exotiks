<?php

namespace YandexTaxi\Delivery\Entities\DriverPhone;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class DriverPhone
 *
 * @package YandexTaxi\Delivery\Entities\DriverPhone
 */
class DriverPhone
{
    /** @var string */
    private $phone;

    /** @var string */
    private $ext;

    public function __construct(string $phone, string $ext)
    {
        $this->phone = $phone;
        $this->ext = $ext;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getExt(): string
    {
        return $this->ext;
    }
}
