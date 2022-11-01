<?php

namespace DvBusiness\OrderForm;

use DateTime;
use DvBusiness\DvOptions;
use DvBusiness\OpenCart\DostavistaOpenCartOrder;
use DvBusiness\Warehouses\Warehouse;

class OrderForm
{
    /** @var DostavistaOpenCartOrder[] */
    private $dostavistaOpenCartOrders;

    /** @var DvOptions */
    private $dvOptions;

    /** @var Warehouse */
    private $defaultWarehouse;

    /** @var string */
    private $weightText;

    /** @var string */
    private $kgText;

    public function __construct(array $dostavistaOpenCartOrders, DvOptions $dvOptions, Warehouse $defaultWarehouse, string $weightText, string $kgText)
    {
        $this->dostavistaOpenCartOrders = $dostavistaOpenCartOrders;
        $this->dvOptions                = $dvOptions;
        $this->defaultWarehouse         = $defaultWarehouse;
        $this->weightText               = $weightText;
        $this->kgText                   = $kgText;
    }

    public function getMatterWithPrefix(): string
    {
        $matterWithPrefix = $this->dostavistaOpenCartOrders[0]->getMatter();
        if ($this->dvOptions->isMatterWeightPrefixEnabled()) {
            $matterWithPrefix = $this->weightText . ' ' . ($this->getItemsTotalWeightKg() ?: $this->dvOptions->getDefaultOrderWeightKg()) . $this->kgText
                . ' ' . $matterWithPrefix;
        }

        return$matterWithPrefix;
    }

    public function getItemsTotalWeightKg(): int
    {
        $result = 0;
        foreach ($this->dostavistaOpenCartOrders as $order) {
            $result += $order->getItemsTotalWeightKg();
        }

        return $result ?: $this->dvOptions->getDefaultOrderWeightKg();
    }

    public function getInsuranceAmount(): float
    {
        if ($this->dvOptions->isInsuranceEnabled()) {
            $insurance = 0;
            foreach ($this->dostavistaOpenCartOrders as $dostavistaOpenCartOrder) {
                $insurance += $dostavistaOpenCartOrder->getTakingAmount() ?: $dostavistaOpenCartOrder->getItemsPrice();
            }

            return $insurance;
        }

        return 0;
    }

    public function getPickupBuyoutAmount(): float
    {
        $result = 0;
        if ($this->dvOptions->isBuyoutEnabled() && $this->dvOptions->getOpenCartCashPaymentCode()) {
            foreach ($this->dostavistaOpenCartOrders as $order) {
                if ($order->isCashPayment()) {
                    $result += $order->getItemsPrice();
                }
            }
        }

        return $result;
    }

    public function getPickupRequiredDate(): string
    {
        return $this->isNextDayPickup()
            ? date('Y-m-d', strtotime($this->defaultWarehouse->getNearestWorkDate(date('c', strtotime('+1 day')))))
            : date('Y-m-d');
    }

    /**
     * @return string date('H:i')
     */
    public function getPickupRequiredStartTime(): string
    {
        if ($this->isNextDayPickup()) {
            return $this->defaultWarehouse->workStartTime;
        }

        $dateTime = (new DateTime('now'));

        $nowMinutes = (int) $dateTime->format('i');
        $nowHours   = (int) $dateTime->format('H');

        $warehouseStartDateTime = (new DateTime($this->defaultWarehouse->workStartTime));

        if ($dateTime->getTimestamp() >= $warehouseStartDateTime->getTimestamp()) {
            $m = $nowMinutes > 30 ? 0 : 30;
            $h = $nowMinutes > 30 ? $nowHours + 1 : $nowHours;

            return (new DateTime("$h:$m"))->format('H:i');
        }

        return $this->defaultWarehouse->workStartTime;
    }

    public function isNextDayPickup(): bool
    {
        $currentDate = time();
        $nearestDate = strtotime($this->defaultWarehouse->getNearestWorkDate());

        if ($nearestDate > $currentDate) {
            return true;
        }

        $processingHours   = 2;
        $processingMinutes = $processingHours ? $processingHours * 60 : 30;

        if (!$this->defaultWarehouse->workFinishTime) {
            return false;
        }

        $processingDateTime      = (new DateTime("+{$processingMinutes} minutes"));
        $warehouseFinishDateTime = (new DateTime($this->defaultWarehouse->workFinishTime));

        return $processingDateTime->getTimestamp() >= $warehouseFinishDateTime->getTimestamp();
    }

    public function getDeliveryRequiredDate(DostavistaOpenCartOrder $order): string
    {
        return date('Y-m-d', strtotime($this->defaultWarehouse->getNearestWorkDate($order->getShippingDate())));
    }

    public function getDeliveryRequiredStartTime(DostavistaOpenCartOrder $order): string
    {
        if ($order->getShippingDate() > date('Y-m-d')) {
            return $order->getShippingStartTime();
        }

        $nowDateTime    = new DateTime('now');
        $startDateTime  = new DateTime($order->getShippingStartTime());

        $nowMinutes = (int) $nowDateTime->format('i');
        $nowHours   = (int) $nowDateTime->format('H');

        if ($nowDateTime->getTimestamp() >= $startDateTime->getTimestamp()) {
            $m = $nowMinutes >= 30 ? 0 : 30;
            $h = $nowMinutes >= 30 ? $nowHours + 1 : $nowHours;

            return (new DateTime("$h:$m"))->format('H:i');
        }

        return $startDateTime->format('H:i');
    }

    public function getDeliveryRequiredFinishTime(DostavistaOpenCartOrder $order): string
    {
        return $order->getShippingFinishTime();
    }

    public function getBankCardId(): int
    {
        return $this->dvOptions->getDefaultPaymentCardId();
    }

    public function getPaymentType(): string
    {
        return $this->dvOptions->getDefaultPaymentType();
    }
}
