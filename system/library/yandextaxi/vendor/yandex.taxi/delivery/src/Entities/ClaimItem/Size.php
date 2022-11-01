<?php

namespace YandexTaxi\Delivery\Entities\ClaimItem;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Size
 *
 * @package YandexTaxi\Delivery\Entities\ClaimItem
 */
class Size
{
    /** @var float */
    private $width;

    /** @var float */
    private $length;

    /** @var float */
    private $height;

    public function __construct(float $width, float $length, float $height)
    {
        $this->width = $width;
        $this->length = $length;
        $this->height = $height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getHeight(): float
    {
        return $this->height;
    }
}
