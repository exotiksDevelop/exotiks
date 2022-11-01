<?php

namespace YandexTaxi\Delivery\Entities\ClaimItem;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Money
 *
 * @package YandexTaxi\Delivery\Entities\ClaimItem
 */
class Money
{
    /** @var string */
    private $value;

    /** @var string */
    private $currency;

    public function __construct(string $value, string $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
