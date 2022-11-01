<?php

namespace YandexTaxi\Delivery\Entities\Claim;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Driver
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class Driver
{
    /** @var string */
    private $name;

    /** @var string */
    private $carModel;

    /** @var string */
    private $carNumber;

    public function __construct(string $name, string $carModel, string $carNumber)
    {
        $this->name = $name;
        $this->carModel = $carModel;
        $this->carNumber = $carNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCarModel(): string
    {
        return $this->carModel;
    }

    public function getCarNumber(): string
    {
        return $this->carNumber;
    }
}
