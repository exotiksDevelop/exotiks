<?php

namespace YandexTaxi\Services;

/**
 * Class PriceManager
 *
 * @package YandexTaxi\Services
 */
class PriceManager
{
    /** @var int */
    private $priceExtra;

    /** @var bool */
    private $isDiscountOn;

    /** @var int */
    private $discount;

    /** @var int */
    private $discountFrom;

    public function __construct(int $priceExtra, bool $isDiscountOn = false, int $discount = 0, int $discountFrom = 0) {
        $this->priceExtra = $priceExtra;
        $this->isDiscountOn = $isDiscountOn;
        $this->discount = $discount;
        $this->discountFrom = $discountFrom;
    }

    public function prepare(float $deliveryPrice, float $packageCost): float {
        $deliveryPrice = $this->addPriceMarkup($deliveryPrice);
        $deliveryPrice = $this->addDiscount($deliveryPrice, $packageCost);

        return $deliveryPrice;
    }

    private function addPriceMarkup(float $price): float {
        if (empty($this->priceExtra)) {
            return $price;
        }

        $coefficient = ($this->priceExtra / 100) + 1;

        return $price * $coefficient;
    }

    private function addDiscount(float $price, float $packageCost): float {
        if (!$this->isDiscountOn) {
            return $price;
        }

        if ($packageCost < $this->discountFrom) {
            return $price;
        }

        $coefficient = 1 - ($this->discount / 100);

        if ($coefficient < 0) {
            return 0;
        }

        return $price * $coefficient;
    }
}

