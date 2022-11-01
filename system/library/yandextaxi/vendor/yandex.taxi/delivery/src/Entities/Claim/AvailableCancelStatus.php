<?php

namespace YandexTaxi\Delivery\Entities\Claim;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\Enum;

/**
 * Class AvailableCancelStatus
 *
 * @method static AvailableCancelStatus free()
 * @method static AvailableCancelStatus paid()
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class AvailableCancelStatus extends Enum
{
    private const FREE = 'free';
    private const PAID = 'paid';

    /**
     * @return string[]
     */
    public static function namesList(): array
    {
        return [
            self::FREE => 'Отмена бесплатная',
            self::PAID => 'Отмена платная',
        ];
    }

    public function getName(): string
    {
        if (isset(self::namesList()[$this->getValue()])) {
            return self::namesList()[$this->getValue()];
        }

        return $this->getValue();
    }
}
