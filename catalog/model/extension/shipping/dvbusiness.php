<?php

use Cart\Cart;
use Cart\Weight;
use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\ApiClient\Request\OrderRequestModel;
use DvBusiness\ApiClient\Request\PointRequestModel;
use DvBusiness\ApiClient\Response\OrderResponseModel;
use DvBusiness\DvOptions;
use DvBusiness\Warehouses\WarehouseManager;
use DvBusiness\ModuleConfig\ModuleConfig;
use DvBusiness\ModuleMetric\ModuleMetricManager;

class ModelExtensionShippingDvBusiness extends Model
{
    /** @return Cart */
    private function getCart()
    {
        return $this->cart;
    }

    /** @return Weight */
    private function getWeight()
    {
        return $this->weight;
    }

    function getQuote($address)
    {
        $this->load->language('extension/shipping/dvbusiness');
        $this->load->model('setting/setting');
        $this->load->library('dvbusiness/autoloader');

        $dvOptions = new DvOptions($this->model_setting_setting);

        if (empty($dvOptions->getApiUrl()) || empty($dvOptions->getAuthToken())) {
            return [];
        }

        $apiClient = new DvCmsModuleApiClient($dvOptions->getApiUrl(), $dvOptions->getAuthToken());
        $calculationResult = $this->calculateOrder($apiClient, $dvOptions, $address);
        // Отправим метрику расчета
        $moduleConfig = new ModuleConfig();
        $moduleMetricManager = new ModuleMetricManager(
            $this->db,
            $moduleConfig->getDvCmsModuleApiProdUrl(),
            $dvOptions->getApiUrl(),
            $dvOptions->getAuthToken()
        );

        $moduleMetricManager->checkoutCalculation();

        if (!$calculationResult) {
            return [];
        }

        require_once(DIR_APPLICATION . '/controller/extension/shipping/dvbusiness.php');
        $controller = new ControllerExtensionShippingDvBusiness($this->registry);
        $descriptionHtml = $controller->renderDescriptionHtml($calculationResult['nearest_delivery_date']);

        $errors = $calculationResult['errors'] ?? false;

        if (isset($calculationResult['payment_amount'])) {
            // Ответ с ценой выведем в соответствии со страной, например в my "RM 100"
            $text = $calculationResult['payment_amount']
                ?  str_replace(
                    [
                        '{cost}',
                        '{currency}'
                    ],
                    [
                        $calculationResult['payment_amount'],
                        $this->language->get('text_currency_rub')
                    ],
                    $this->language->get('text_calculation_result_cost')
                )
                : $this->language->get('text_free_delivery');
        } else {
            $text = $this->language->get('text_delivery_calculation_error');
        }

        $quoteData['general'] = [
            'code'         => 'dvbusiness.general',
            'title'        => $dvOptions->getDeliveryServiceDescription() ?: $this->language->get('text_general_delivery'),
            'cost'         => $calculationResult['payment_amount'] ?? 0,
            'tax_class_id' => 0,
            'text'         => $text,
            'description'  => $descriptionHtml,
        ];

        $result = [
            'code'       => 'dvbusiness',
            'title'      => $dvOptions->getDeliveryServiceTitle() ?: $this->language->get('text_title'),
            'quote'      => $quoteData,
            'sort_order' => $this->config->get('shipping_dvbusiness_sort_order'),
            'error'      => $errors,
        ];

        return $result;
    }

    private function calculateOrder(DvCmsModuleApiClient $apiClient, DvOptions $dvOptions, array $address): array
    {
        if (!$dvOptions->getDefaultPickupWarehouseId()) {
            return [];
        }

        $warehouseManager = new WarehouseManager($this->db);
        $defaultPickupWarehouse = $warehouseManager->getById($dvOptions->getDefaultPickupWarehouseId());
        if (!$defaultPickupWarehouse) {
            return [];
        }

        $weightKg   = 0;
        $totalPrice = 0;
        foreach ($this->getCart()->getProducts() as $product) {
            $weightKg   += $this->getWeight()->convert($product['weight'], $product['weight_class_id'], 1);
            $totalPrice += $product['price'];
        }

        $deliveryAddress = $address['address_1'] ?? '';
        $deliveryCity    = $address['city'] ?? '';

        if ($deliveryCity && $deliveryAddress && strpos($deliveryAddress, $deliveryCity) === false) {
            $deliveryAddress = $deliveryCity . ', ' . $deliveryAddress;
        }

        $defaultPickupWarehouse = $warehouseManager->getFirstItemByCityName($deliveryCity) ?? $defaultPickupWarehouse;

        if (!$deliveryAddress) {
            return $this->getCalculationResult(0, '', $this->language->get('error_address_empty'));
        }

        $orderRequestModel = new OrderRequestModel();
        $orderRequestModel
            ->setMatter('Заказ')
            ->setInsuranceAmount($dvOptions->isInsuranceEnabled() ? $totalPrice : 0)
            ->setTotalWeightKg((int) ($weightKg ?: $dvOptions->getDefaultOrderWeightKg()))
            ->setVehicleTypeId($dvOptions->getDefaultVehicleTypeId());

        $processingHours   = 2;
        $processingMinutes = $processingHours ? $processingHours * 60 : 30;

        $depClockHours   = date('H');
        $depClockMinutes = date('i');

        $pickupTime = strtotime(
            $defaultPickupWarehouse->getNearestWorkDate(date('c', strtotime("{$depClockHours}:{$depClockMinutes} +{$processingMinutes} minutes")))
        );

        if (
            $defaultPickupWarehouse->workFinishTime
            && date('Y-m-d', $pickupTime) === date('Y-m-d')
            && $pickupTime > strtotime($defaultPickupWarehouse->workFinishTime)

        ) {
            $pickupTime = strtotime($defaultPickupWarehouse->getNearestWorkDate(date('c', strtotime('next day ' . $defaultPickupWarehouse->workStartTime, $pickupTime))));
        }

        $pickupPoint = (new PointRequestModel())
            ->setAddress($defaultPickupWarehouse->getFullAddress())
            ->setRequiredTimeInterval(
                date('c', $pickupTime),
                date('c', strtotime('+30 minutes', $pickupTime))
            );

        $deliveryPoint = (new PointRequestModel())
            ->setRequiredTimeInterval(
                date('c', strtotime('+1 hours', $pickupTime)),
                date('c', strtotime('+2 hours', $pickupTime))
            );

        if ($deliveryAddress) {
            $deliveryPoint->setAddress($deliveryAddress);
        }

        $orderRequestModel
            ->addPoint($pickupPoint)
            ->addPoint($deliveryPoint);

        try {
            $response = $apiClient->calculateOrder($orderRequestModel);
            $orderResponseModel = new OrderResponseModel($response->getData()['order'] ?? []);

            // Если есть ошибки расчета, то выведем их пользователю
            if ($response->getWarnings() && isset($response->getParameterWarnings()['points'])) {
                foreach ($response->getParameterWarnings()['points'] as $pointErrorData) {
                    if (isset($pointErrorData['address']) && in_array('address_not_found', $pointErrorData['address'])) {
                        return $this->getCalculationResult(0, '', $this->language->get('error_address_empty'));
                    }

                    if (isset($pointErrorData['address']) && in_array('invalid_region', $pointErrorData['address'])) {
                        return $this->getCalculationResult(0, '', $this->language->get('error_invalid_region'));
                    }

                    if (isset($pointErrorData['address']) && in_array('different_regions', $pointErrorData['address'])) {
                        return $this->getCalculationResult(0, '', $this->language->get('error_different_regions'));
                    }
                }
            }

            if (
                $dvOptions->getFreeDeliveryOpencartOrderSum() > 0
                && $totalPrice
                && $totalPrice >= $dvOptions->getFreeDeliveryOpencartOrderSum()
            ) {
                $paymentAmount = 0;
            } elseif ((int) $dvOptions->getFixOrderPaymentAmount()) {
                $paymentAmount = $dvOptions->getFixOrderPaymentAmount();
            } else {
                $paymentAmount = $orderResponseModel->getPaymentAmount();

                // Учитываем наценку и скидку магазина над стоимостью доставки Dostavista
                $paymentAmount += $dvOptions->getDostavistaPaymentMarkupAmount();
                $paymentAmount -= $dvOptions->getDostavistaPaymentDiscountAmount();
                $paymentAmount = max(0, $paymentAmount);
            }
        } catch (DvCmsModuleApiHttpException $exception) {
            $paymentAmount = 0;
        }

        return [
            'payment_amount'        => $paymentAmount,
            'nearest_delivery_date' => !empty($orderResponseModel) && !empty($orderResponseModel->getPoints()[1])
                ? $orderResponseModel->getPoints()[1]->getRequiredFinishDatetime()
                : date('c'),
        ];
    }

    private function getCalculationResult(int $paymentAmount, string $nearestDate, string $errors): array
    {
        return [
            'payment_amount'        => $paymentAmount,
            'nearest_delivery_date' => $nearestDate,
            'errors'                => $errors,
        ];
    }
}
