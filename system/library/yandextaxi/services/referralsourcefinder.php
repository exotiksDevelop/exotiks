<?php

namespace YandexTaxi\Services;

/**
 * Class ReferralSourceFinder
 *
 * @package YandexTaxi\Services
 */
class ReferralSourceFinder
{
    public static function find(): string {
        return 'OpenCart – ' . Constants::VERSION;
    }
}
