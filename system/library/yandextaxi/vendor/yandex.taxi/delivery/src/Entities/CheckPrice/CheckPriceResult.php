<?php

namespace YandexTaxi\Delivery\Entities\CheckPrice;

use YandexTaxi\Delivery\Entities\ClaimItem\Money;

/**
 * Class CheckPriceResult
 *
 * @package YandexTaxi\Delivery\Entities\CheckPrice
 */
class CheckPriceResult
{
    /** @var Money */
    private $price;

    /** @var float */
    private $eta;

    /** @var string */
    private $tariffName;

    public function __construct(Money $price, float $eta, string $tariffName)
    {
        $this->price = $price;
        $this->eta = $eta;
        $this->tariffName = $tariffName;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getEta(): float
    {
        return $this->eta;
    }

    public function getTariffName(): string
    {
        return $this->tariffName;
    }
}
