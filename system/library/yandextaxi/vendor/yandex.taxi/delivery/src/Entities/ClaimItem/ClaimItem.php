<?php

namespace YandexTaxi\Delivery\Entities\ClaimItem;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use LogicException;

/**
 * Class ClaimItem
 *
 * @package YandexTaxi\Delivery\Entities\ClaimItem
 */
class ClaimItem
{
    /** @var string */
    private $id;

    /** @var ?string */
    private $extraId;

    /** @var string|null */
    private $orderId;

    /** @var string */
    private $title;

    /** @var Size */
    private $size;

    /** @var Money */
    private $cost;

    /** @var float */
    private $weight;

    /** @var int */
    private $quantity;

    /** @var int */
    private $dropOffPointId;

    public function __construct(
        ?string $id,
        ?string $extraId,
        ?string $orderId,
        string $title,
        Size $size,
        Money $cost,
        float $weight,
        int $quantity
    ) {
        $this->id = $id;
        $this->extraId = $extraId;
        $this->orderId = $orderId;
        $this->title = $title;
        $this->size = $size;
        $this->cost = $cost;
        $this->weight = $weight;
        $this->quantity = $quantity;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getExtraId(): ?string
    {
        return $this->extraId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getCost(): Money
    {
        return $this->cost;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    /**
     * @return int
     * @throws LogicException
     */
    public function getDropOffPointId(): int
    {
        if (is_null($this->dropOffPointId)) {
            throw new LogicException('Drop off point is not set for item');
        }

        return $this->dropOffPointId;
    }

    public function setDropOffPointId(int $dropOffPointId): void
    {
        $this->dropOffPointId = $dropOffPointId;
    }
}
