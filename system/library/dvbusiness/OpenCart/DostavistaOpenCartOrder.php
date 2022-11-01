<?php

namespace DvBusiness\OpenCart;

use DvBusiness\DvOptions;
use InvalidArgumentException;
use ModelCatalogProduct;
use ModelSaleOrder;

/**
 * Модель заказа OpenCart с учетом логики Dostavista
 */
class DostavistaOpenCartOrder
{
    /** @var ModelSaleOrder */
    private $modelSaleOrder;

    /** @var DvOptions */
    private $dvOptions;

    /** @var array */
    private $orderData;

    /** @var array */
    private $orderTotals;

    /** @var array */
    private $orderCatalogProducts;

    /** @var array */
    private $shippingDetails;

    /**
     * @param int $orderId
     * @param ModelSaleOrder $modelSaleOrder (Proxy-объект)
     * @param ModelCatalogProduct $modelCatalogProduct (Proxy-объект)
     * @param array $shippingDetails
     * @param DvOptions $dvOptions
     */
    public function __construct(int $orderId, $modelSaleOrder, $modelCatalogProduct, array $shippingDetails, DvOptions $dvOptions)
    {
        $this->orderData = $modelSaleOrder->getOrder($orderId);

        if (empty($this->orderData['order_id'])) {
            throw new InvalidArgumentException('Невалидная структура заказа');
        }

        $this->dvOptions      = $dvOptions;
        $this->modelSaleOrder = $modelSaleOrder;
        $this->orderTotals    = $modelSaleOrder->getOrderTotals($orderId);

        $orderProducts = $modelSaleOrder->getOrderProducts($orderId);
        $orderCatalogProducts = [];
        foreach ($orderProducts as $orderProduct) {
            $orderCatalogProducts[] = $modelCatalogProduct->getProduct($orderProduct['product_id']);
        }
        $this->orderCatalogProducts = $orderCatalogProducts;

        $this->shippingDetails = $shippingDetails;
    }

    public function getId(): int
    {
        return (int) $this->orderData['order_id'];
    }

    public function getMatter(): string
    {
        return (string) ($this->orderCatalogProducts[0]['name'] ?? '');
    }

    public function getContactName(): string
    {
        return (string) ($this->orderData['customer'] ?? '');
    }

    public function getContactPhone(): string
    {
        return (string) ($this->orderData['telephone'] ?? '');
    }

    public function getItemsPrice(): float
    {
        $itemsPrice = 0;
        foreach ($this->orderTotals as $orderTotal) {
            if ($orderTotal['code'] == 'sub_total') {
                $itemsPrice = $orderTotal['value'];
            }
        }

        return (float) $itemsPrice;
    }

    public function getShippingCity(): string
    {
        return (string) ($this->orderData['shipping_city'] ?? '');
    }

    public function getShippingAddress(): string
    {
        $city    = $this->getShippingCity();
        $address = (string) ($this->orderData['shipping_address_1'] ?? '');

        if ($city && $address && strpos($address, $city) === false) {
            $address = $city . ', ' . $address;
        }

        return $address;
    }

    public function getShippingDate(): string
    {
        $date = $this->shippingDetails['required_date'] ?? date('Y-m-d');
        return date('Y-m-d', strtotime($date));
    }

    public function getShippingStartTime(): string
    {
        $time = $this->shippingDetails['required_start_time'] ?? '16:00';
        return date('H:i', strtotime($time));
    }

    public function getShippingFinishTime(): string
    {
        $time = $this->shippingDetails['required_finish_time'] ?? '21:00';
        return date('H:i', strtotime($time));
    }

    public function getShippingComment(): string
    {
        $commentParts = [];
        if ($this->dvOptions->getDeliveryPointNotePrefix()) {
            $commentParts[] = $this->dvOptions->getDeliveryPointNotePrefix();
        }
        $commentParts[] = $this->orderData['comment'] ?? '';

        return trim(join(' ', $commentParts));
    }

    public function getShippingPrice(): float
    {
        $shippingPrice = 0;
        foreach ($this->orderTotals as $orderTotal) {
            if ($orderTotal['code'] == 'shipping') {
                $shippingPrice = $orderTotal['value'];
            }
        }

        return (float) $shippingPrice;
    }

    public function getItemsTotalWeightKg(): int
    {
        $result = 0;
        foreach ($this->orderCatalogProducts as $productData)
        {
            $result += $productData['weight'] / 1000;
        }

        return (int) ceil($result);
    }

    public function getTakingAmount(): float
    {
        return $this->isCashPayment() ? $this->getItemsPrice() + $this->getShippingPrice() : 0;
    }

    public function isCashPayment(): bool
    {
        return
            $this->dvOptions->getOpenCartCashPaymentCode()
            && $this->orderData['payment_code'] == $this->dvOptions->getOpenCartCashPaymentCode();
    }
}
