<?php

namespace YandexTaxi\Delivery\Entities\Order;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\ClaimItem\Money;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;
use YandexTaxi\Delivery\Entities\ClaimItem\Size;
use LogicException;

/**
 * Class Order
 *
 * @package YandexTaxi\Delivery\Entities\Order
 */
class Order
{
    /** @var ClaimItem[] */
    private $items = [];

    /** @var RoutePoint */
    private $destination;

    /**
     * @param RoutePoint  $destination
     * @param ClaimItem[] $items
     *
     * @return Order
     */
    public static function createReal(RoutePoint $destination, array $items): Order
    {
        if (empty($items)) {
            throw new LogicException('Хотя бы один груз должен ехать в точку доставки');
        }

        foreach ($items as $item) {
            $item->setDropOffPointId($destination->getId());
        }

        return new self($destination, $items);
    }
    
    public static function createFake(RoutePoint $destination): Order
    {
        $fakeItem = new ClaimItem(
            $id = null,
            $extraId = null,
            $orderId = null,
            'Fake item',
            new Size(0, 0, 0),
            new Money(0, 'RUB'),
            0,
            1
        );
        $fakeItem->setDropOffPointId($destination->getId());

        return new self($destination, [$fakeItem]);
    }

    /**
     * Order constructor.
     *
     * @param RoutePoint  $destination
     * @param ClaimItem[] $items
     */
    private function __construct(RoutePoint $destination, array $items)
    {
        $this->items = $items;
        $this->destination = $destination;
    }

    /**
     * @return ClaimItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getDestination(): RoutePoint
    {
        return $this->destination;
    }
}
