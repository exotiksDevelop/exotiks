<?php

namespace DvBusiness\OrderForm;

use DvBusiness\ApiClient\Response\OrderResponseModel;

class OrderCalculationResultResponseModel
{
    /** @var OrderResponseModel */
    private $orderResponseModel;

    public function __construct(OrderResponseModel $orderResponseModel)
    {
        $this->orderResponseModel = $orderResponseModel;
    }

    public function getData(): array
    {
        return [
            'delivery_fee_amount'       => (float) ($this->orderResponseModel->getDeliveryFeeAmount()),
            'insurance_fee_amount'      => (float) ($this->orderResponseModel->getInsuranceFeeAmount()),
            'weight_fee_amount'         => (float) ($this->orderResponseModel->getWeightFeeAmount()),
            'money_transfer_fee_amount' => (float) ($this->orderResponseModel->getMoneyTransferFeeAmount()),
            'loading_fee_amount'        => (float) ($this->orderResponseModel->getLoadingFeeAmount()),
            'payment_amount'            => (float) ($this->orderResponseModel->getPaymentAmount()),
            'vehicle_type_id'           => (int) $this->orderResponseModel->getVehicleTypeId(),
        ];
    }
}
