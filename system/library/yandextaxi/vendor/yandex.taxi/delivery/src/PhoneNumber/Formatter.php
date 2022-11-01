<?php

namespace YandexTaxi\Delivery\PhoneNumber;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

/**
 * Class Formatter
 *
 * @package YandexTaxi\Delivery\PhoneNumber
 */
class Formatter
{
    private const DEFAULT_COUNTRY = 'RU';
    private const FORMAT = PhoneNumberFormat::E164;

    /**
     * @param string      $phoneNumber
     * @param string|null $country
     *
     * @return string
     * @throws NumberParseException
     */
    public static function format(string $phoneNumber, ?string $country = null): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        $phoneNumberPhoto = $phoneUtil->parse($phoneNumber, !is_null($country) ? $country : self::DEFAULT_COUNTRY);

        return $phoneUtil->format($phoneNumberPhoto, self::FORMAT);
    }
}
