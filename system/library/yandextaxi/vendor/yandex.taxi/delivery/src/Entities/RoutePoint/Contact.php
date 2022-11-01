<?php

namespace YandexTaxi\Delivery\Entities\RoutePoint;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\PhoneNumber\Formatter as PhoneFormatter;
use libphonenumber\NumberParseException;

/**
 * Class Contact
 *
 * @package YandexTaxi\Delivery\Entities\RoutePoint
 */
class Contact
{
    /** @var string */
    private $name;

    /** @var string */
    private $phone;

    /** @var string|null */
    private $email;

    /**
     * Contact constructor.
     *
     * @param string      $name
     * @param string      $phone
     * @param string|null $email
     * @param string|null $country
     *
     * @throws NumberParseException
     */
    public function __construct(string $name, string $phone, ?string $email = null, ?string $country = null)
    {
        $this->phone = PhoneFormatter::format($phone, $country);
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
