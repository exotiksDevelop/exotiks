<?php

namespace DvBusiness\ApiClient\Request;

use DvBusiness\Enums\PaymentMethodEnum;

class OrderRequestModel
{
    /** @var string|null */
    private $matter;

    /** @var int|null */
    private $vehicleTypeId;

    /** @var int */
    private $totalWeightKg = 0;

    /** @var float */
    private $insuranceAmount = 0;

    /** @var int */
    private $loadersCount = 0;

    /** @var string|null */
    private $backpaymentDetails;

    /** @var bool|null */
    private $isContactPersonNotificationEnabled;

    /** @var PointRequestModel[] */
    private $points = [];

    /** @var string|null */
    private $paymentMethod;

    /** @var int|null */
    private $bankCardId;

    public function setMatter(string $matter): OrderRequestModel
    {
        $this->matter = $matter;
        return $this;
    }

    public function setVehicleTypeId(int $vehicleTypeId): OrderRequestModel
    {
        $this->vehicleTypeId = $vehicleTypeId;
        return $this;
    }

    public function setTotalWeightKg(int $totalWeightKg): OrderRequestModel
    {
        $this->totalWeightKg = $totalWeightKg;
        return $this;
    }

    public function setInsuranceAmount(float $insuranceAmount): OrderRequestModel
    {
        $this->insuranceAmount = $insuranceAmount;
        return $this;
    }

    public function setLoadersCount(int $loadersCount): OrderRequestModel
    {
        $this->loadersCount = $loadersCount;
        return $this;
    }

    public function setBackpaymentDetails(string $backpaymentDetails): OrderRequestModel
    {
        $this->backpaymentDetails = $backpaymentDetails;
        return $this;
    }

    public function setContactPersonNotification(bool $isEnabled): OrderRequestModel
    {
        $this->isContactPersonNotificationEnabled = $isEnabled;
        return $this;
    }

    public function addPoint(PointRequestModel $pointRequestModel): OrderRequestModel
    {
        $this->points[] = $pointRequestModel;
        return $this;
    }

    public function setPaymentMethod(string $paymentMethod): OrderRequestModel
    {
        $this->paymentMethod = in_array($paymentMethod, [
            PaymentMethodEnum::PAYMENT_METHOD_BANK,
            PaymentMethodEnum::PAYMENT_METHOD_QIWI,
            PaymentMethodEnum::PAYMENT_METHOD_CASH,
            PaymentMethodEnum::PAYMENT_METHOD_NON_CASH,
        ]) ? $paymentMethod : null;
        return $this;
    }

    public function setBankCardId(int $bankCardId): OrderRequestModel
    {
        $this->bankCardId = $bankCardId;
        return  $this;
    }

    public function getRequestData(): array
    {
        $data = [
            'matter'              => $this->matter,
            'total_weight_kg'     => $this->totalWeightKg,
            'insurance_amount'    => $this->insuranceAmount,
            'loaders_count'       => $this->loadersCount,
            'backpayment_details' => $this->backpaymentDetails,
            'points'              => [],
        ];

        if ($this->vehicleTypeId !== null) {
            $data['vehicle_type_id'] = $this->vehicleTypeId;
        }

        if ($this->isContactPersonNotificationEnabled !== null) {
            $data['is_contact_person_notification_enabled'] = $this->isContactPersonNotificationEnabled;
        }

        foreach ($this->points as $point) {
            $data['points'][] = $point->getRequestData();
        }

        if ($this->bankCardId) {
            $data['bank_card_id'] = $this->bankCardId;
        }

        if (!empty($this->paymentMethod)) {
            $data['payment_method'] = $this->paymentMethod;
        }

        return $data;
    }
}
