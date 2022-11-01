<?php

use DvBusiness\ApiClient\Response\DeliveryResponseModel;
use DvBusiness\ApiClient\Response\OrderResponseModel;
use DvBusiness\DostavistaOrders\DostavistaOrderManager;
use DvBusiness\DvOptions;
use DvBusiness\OpenCart\OrderManager;

class ControllerExtensionShippingDvBusiness extends Controller
{
    /**
     * Обработчик колбеков от Достависты Business API
     */
    public function apiCallbackHandler()
    {
        $this->load->model('setting/setting');
        $this->load->library('dvbusiness/autoloader');

        $dvOptions = new DvOptions($this->model_setting_setting);

        $callbackSecretKey = $dvOptions->getApiCallbackSecretKey();
        if (!$callbackSecretKey) {
            echo 'ERROR: API callback secret key is empty';
            exit;
        }

        if (!isset($_SERVER['HTTP_X_DV_SIGNATURE'])) {
            echo 'ERROR: Signature not found';
            exit;
        }

        $dataJson = file_get_contents('php://input');

        $signature = hash_hmac('sha256', $dataJson, $callbackSecretKey);
        if ($signature != $_SERVER['HTTP_X_DV_SIGNATURE']) {
            echo 'ERROR: Signature not found';
            exit;
        }

        $data = json_decode($dataJson, true);
        if (!empty($data['order'])) {
            $responseDostavistaOrder = new OrderResponseModel($data['order']);

            $dostavistaOrderManager = new DostavistaOrderManager($this->db);
            $dostavistaOrder = $dostavistaOrderManager->getByDostavistaOrderId($responseDostavistaOrder->getOrderId());
            if (!$dostavistaOrder) {
                echo 'INFO: Dostavista order not found';
                exit;
            }

            $dostavistaOrder->courierName  = $responseDostavistaOrder->getCourier() ? $responseDostavistaOrder->getCourier()->getName() : '';
            $dostavistaOrder->courierPhone = $responseDostavistaOrder->getCourier() ? $responseDostavistaOrder->getCourier()->getName() : '';
            $dostavistaOrderManager->save($dostavistaOrder);
        } else if (!empty($data['delivery'])) {
            $this->processDeliveryEvent($data['delivery'], $dvOptions);
        }
    }

    private function processDeliveryEvent(array $deliveryData, DvOptions $dvOptions)
    {
        $deliveryDto = new DeliveryResponseModel($deliveryData);

        $clientOrderId = $deliveryDto->getClientOrderId();
        if (!$clientOrderId) {
            return;
        }

        $deliveryStatusMap = [
            DeliveryResponseModel::STATUS_DRAFT            => $dvOptions->getIntegrationOrderStatusDraft(),
            DeliveryResponseModel::STATUS_PLANNED          => $dvOptions->getIntegrationOrderStatusAvailable(),
            DeliveryResponseModel::STATUS_COURIER_ASSIGNED => $dvOptions->getIntegrationOrderStatusCourierAssigned(),
            DeliveryResponseModel::STATUS_ACTIVE           => $dvOptions->getIntegrationOrderStatusActive(),
            DeliveryResponseModel::STATUS_PARCEL_PICKED_UP => $dvOptions->getIntegrationOrderStatusParcelPickedUp(),
            DeliveryResponseModel::STATUS_COURIER_DEPARTED => $dvOptions->getIntegrationOrderStatusCourierDeparted(),
            DeliveryResponseModel::STATUS_COURIER_ARRIVED  => $dvOptions->getIntegrationOrderStatusCourierArrived(),
            DeliveryResponseModel::STATUS_FINISHED         => $dvOptions->getIntegrationOrderStatusCompleted(),
            DeliveryResponseModel::STATUS_FAILED           => $dvOptions->getIntegrationOrderStatusFailed(),
            DeliveryResponseModel::STATUS_CANCELED         => $dvOptions->getIntegrationOrderStatusCanceled(),
            DeliveryResponseModel::STATUS_DELAYED          => $dvOptions->getIntegrationOrderStatusDelayed(),
        ];

        $orderManager = new OrderManager($this->db);

        foreach ($deliveryStatusMap as $dvStatus => $openCartOrderStatusId) {
            if ($deliveryDto->getStatus() === $dvStatus && $openCartOrderStatusId) {
                $orderManager->updateOrderStatus((int) $clientOrderId, $openCartOrderStatusId);
            }
        }
    }

    public function setShippingFields()
    {
        $date       = $this->request->post['shipping_dvbusiness_required_date'] ?? null;
        $startTime  = $this->request->post['shipping_dvbusiness_required_start_time'] ?? null;
        $finishTime = $this->request->post['shipping_dvbusiness_required_finish_time'] ?? null;

        if ($date) {
            $this->session->data['dvbusiness']['required_date'] = $date;
        }

        if ($startTime) {
            $this->session->data['dvbusiness']['required_start_time'] = $startTime;
        }

        if ($finishTime) {
            $this->session->data['dvbusiness']['required_finish_time'] = $finishTime;
        }
    }

    public function renderDescriptionHtml(string $nearestDeliveryDate)
    {
        $this->load->language('extension/shipping/dvbusiness');

        $dateEnum = [];
        for ($i = 0; $i <= 14; $i++) {
            $timestamp = strtotime("+$i days");
            $title = date('d.m.Y', $timestamp);
            $value = date('Y-m-d', $timestamp);
            $textTitles = [$this->language->get('text_today'), $this->language->get('text_tomorrow')];
            $dateEnum[$value] = $textTitles[$i] ?? $title;
        }

        $timeEnum = [];
        for ($h = 0; $h < 24; $h++) {
            for ($i = 0; $i < 60; $i += 30) {
                $value = date('H:i', strtotime("today {$h}:{$i}"));
                $timeEnum[$value] = $value;
            }
        }

        $today    = new DateTime(date('c'));
        $dateTime = new DateTime($nearestDeliveryDate);

        if ($today->format('d') === $dateTime->format('d')) {
            $deliveryDateDescription = $this->language->get('text_nearest_date') . ' ' . $this->language->get('text_today');
        } elseif ($dateTime->format('d') - $today->format('d') === 1) {
            $deliveryDateDescription = $this->language->get('text_nearest_date') . ' ' . $this->language->get('text_tomorrow');
        } else {
            $formattedDate = $dateTime->format('d.m.Y');
            $deliveryDateDescription = $this->language->get('text_nearest_date') . ' ' . $formattedDate;
        }

        $data = [
            'date_enum'                 => $dateEnum,
            'date'                      => date('Y-m-d'),
            'time_enum'                 => $timeEnum,
            'required_start_time'       => '16:00',
            'required_finish_time'      => '20:00',
            'delivery_date_description' => $deliveryDateDescription,
        ];

        return $this->load->view('extension/shipping/dvbusiness', $data);
    }
}
