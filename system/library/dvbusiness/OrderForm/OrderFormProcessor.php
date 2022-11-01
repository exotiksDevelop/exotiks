<?php

namespace DvBusiness\OrderForm;

use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\ApiClient\DvCmsModuleApiResponse;
use DvBusiness\ApiClient\Request\OrderRequestModel;
use DvBusiness\ApiClient\Request\PointRequestModel;
use DvBusiness\Enums\PaymentMethodEnum;

class OrderFormProcessor
{
    /** @var DvCmsModuleApiClient */
    private $dvBusinessApiClient;

    /** @var array */
    private $requestData;

    /** @var array */
    private $allowedPaymentMethods;

    public function  __construct(DvCmsModuleApiClient $dvBusinessApiClient, array $requestData, array $allowedPaymentMethods)
    {
        $this->dvBusinessApiClient   = $dvBusinessApiClient;
        $this->requestData           = $requestData;
        $this->allowedPaymentMethods = $allowedPaymentMethods;
    }

    private function getDeliveryPointIndexes(): array
    {
        $indexes = [];
        for ($i = 0; $i < 200; $i++) {
            if (!isset($this->requestData['delivery_address_' . $i])) {
                break;
            }
            $indexes[] = $i;
        }

        return $indexes;
    }

    public function getClientOrderIds(): array
    {
        $ids = [];
        foreach ($this->getDeliveryPointIndexes() as $index) {
            $id = (int) $this->requestData['delivery_client_order_id_' . $index];
            if ($id) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    private function getOrderRequestModelFromRequestData()
    {
        $data = $this->requestData;

        $orderRequestModel = (new OrderRequestModel())
            ->setMatter($data['matter'])
            ->setTotalWeightKg((int) $data['total_weight_kg'])
            ->setInsuranceAmount((float) $data['insurance_amount'])
            ->setVehicleTypeId((int) $data['vehicle_type_id'])
            ->setLoadersCount((int) $data['loaders_count'])
            ->setContactPersonNotification((bool) $data['contact_person_notification_enabled'])
        ;

        $pickupDate       = $data['pickup_required_date'];
        $pickupStartTime  = $data['pickup_required_start_time'];
        $pickupFinishTime = $data['pickup_required_finish_time'];

        $pickupPoint = new PointRequestModel();
        $pickupPoint
            ->setAddress($data['pickup_address'])
            ->setRequiredTimeInterval(
                date('c', strtotime("{$pickupDate} {$pickupStartTime}")),
                date('c', strtotime("{$pickupDate} {$pickupFinishTime}"))
            )
            ->setContactPerson($data['pickup_contact_name'], $data['pickup_contact_phone'])
            ->setNote($data['pickup_note'])
            ->setBuyoutAmount((float) $data['pickup_buyout_amount'])
        ;

        $orderRequestModel->addPoint($pickupPoint);

        foreach ($this->getDeliveryPointIndexes() as $index) {
            $deliveryDate       = $data['delivery_required_date_' . $index];
            $deliveryStartTime  = $data['delivery_required_start_time_' . $index];
            $deliveryFinishTime = $data['delivery_required_finish_time_' . $index];
            $deliveryPoint = (new PointRequestModel())
                ->setAddress($data['delivery_address_' . $index])
                ->setRequiredTimeInterval(
                    date('c', strtotime("{$deliveryDate} {$deliveryStartTime}")),
                    date('c', strtotime("{$deliveryDate} {$deliveryFinishTime}"))
                )
                ->setContactPerson($data['delivery_recipient_name_' . $index], $data['delivery_recipient_phone_' . $index])
                ->setNote($data['delivery_note_' . $index])
                ->setClientOrderId($data['delivery_client_order_id_' . $index])
                ->setTakingAmount((float) $data['delivery_taking_amount_' . $index])
            ;

            $orderRequestModel->addPoint($deliveryPoint);
        }

        // Установим метод оплаты и данные по карте. Если указана id карты, то выбираем тип оплаты картой (если такой метод доступен пользователю)
        if (in_array($data['payment_type'], $this->allowedPaymentMethods)) {
            $orderRequestModel->setPaymentMethod($data['payment_type']);
        }

        if (!empty($data['bank_card_id']) && in_array($data['payment_type'], [PaymentMethodEnum::PAYMENT_METHOD_BANK, PaymentMethodEnum::PAYMENT_METHOD_QIWI])) {
            $orderRequestModel->setBankCardId($data['bank_card_id']);
        }

        return $orderRequestModel;
    }

    /**
     * @return DvCmsModuleApiResponse
     * @throws DvCmsModuleApiHttpException
     */
    public function calculateOrder(): DvCmsModuleApiResponse
    {
        $orderRequestModel = $this->getOrderRequestModelFromRequestData();
        return $this->dvBusinessApiClient->calculateOrder($orderRequestModel);
    }

    /**
     * @return DvCmsModuleApiResponse
     * @throws DvCmsModuleApiHttpException
     */
    public function createOrder(): DvCmsModuleApiResponse
    {
        $orderRequestModel = $this->getOrderRequestModelFromRequestData();
        return $this->dvBusinessApiClient->createOrder($orderRequestModel);
    }

    public function getFormParameterErrors(DvCmsModuleApiResponse $apiResponse): array
    {
        $errors = [];

        $responseParameterErrors = $apiResponse->getParameterErrors();
        if (!count($responseParameterErrors)) {
            $responseParameterErrors = $apiResponse->getParameterWarnings();
        }

        foreach ($responseParameterErrors as $parameterName => $data) {
            if ($parameterName == 'points') {
                continue;
            }

            switch ($parameterName) {
                case 'matter':
                case 'total_weight_kg':
                case 'insurance_amount':
                    $errors[] = static::getMappedParameterError($data);
                    break;
            }
        }

        if (!empty($responseParameterErrors['points'])) {
            if (!empty($responseParameterErrors['points'][0])) {
                foreach ($responseParameterErrors['points'][0] as $parameterName => $data) {
                    if (empty($data)) {
                        continue;
                    }

                    switch ($parameterName) {
                        case 'address':
                            $errors['pickup_address'] = static::getMappedParameterError($data);
                            break;
                        case 'required_start_datetime':
                            $errors['pickup_required_start_time'] = static::getMappedParameterError($data);
                            break;
                        case 'required_finish_datetime':
                            $errors['pickup_required_finish_time'] = static::getMappedParameterError($data);
                            break;
                        case 'contact_person':
                            if (!empty($data['name'])) {
                                $errors['pickup_contact_name'] = static::getMappedParameterError($data['name']);
                            }
                            if (!empty($data['phone'])) {
                                $errors['pickup_contact_phone'] = static::getMappedParameterError($data['phone']);
                            }
                            break;
                        case 'note':
                            $errors['pickup_note'] = static::getMappedParameterError($data);
                            break;
                    }
                }
            }

            foreach ($responseParameterErrors['points'] as $index => $pointData) {
                if ($index == 0) {
                    continue;
                }

                $deliveryPointIndex = $index - 1;

                if (!$pointData) {
                    continue;
                }

                foreach ($pointData as $parameterName => $data) {
                    if (empty($data)) {
                        continue;
                    }

                    switch ($parameterName) {
                        case 'address':
                            $errors['delivery_address_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            break;
                        case 'required_start_datetime':
                            $errors['delivery_required_start_time_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            break;
                        case 'required_finish_datetime':
                            $errors['delivery_required_finish_time_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            break;
                        case 'contact_person':
                            if (!empty($data['name'])) {
                                $errors['delivery_recipient_name_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            }
                            if (!empty($data['phone'])) {
                                $errors['delivery_recipient_phone_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            }
                            break;
                        case 'note':
                            $errors['delivery_note_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            break;
                        case 'client_order_id':
                            $errors['delivery_client_order_id_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            break;
                        case 'taking_amount':
                            $errors['delivery_taking_amount_' . $deliveryPointIndex] = static::getMappedParameterError($data);
                            break;
                    }
                }
            }
        }

        return $errors;
    }

    private static function getMappedParameterError(array $apiParameterErrors): string
    {
        return $apiParameterErrors[0] ?? 'invalid_value';
    }
}
