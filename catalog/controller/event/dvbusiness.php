<?php

use DvBusiness\OpenCart\OrderManager;
use DvBusiness\ModuleMetric\ModuleMetricManager;
use DvBusiness\ModuleConfig\ModuleConfig;
use DvBusiness\DvOptions;

class ControllerEventDvBusiness extends Controller
{
    public function beforeHeader()
    {
        $this->document->addStyle('catalog/view/theme/default/stylesheet/dvbusiness.css');
        $this->document->addScript('catalog/view/javascript/dvbusiness.js');
    }

    public function afterOrderCreation($route, $inputData, int $orderId)
    {
        if (
            $route == 'checkout/order/addOrder'
            && $orderId
            && isset($inputData[0]['shipping_code'])
            && $inputData[0]['shipping_code'] == 'dvbusiness.general'
        ) {
            $this->rememberShippingDetails($orderId);
            $this->createNewCheckoutOrderMetric();
        }
    }

    private function createNewCheckoutOrderMetric()
    {
        $this->load->model('setting/setting');
        $this->load->library('dvbusiness/autoloader');

        $dvOptions = new DvOptions($this->model_setting_setting);
        $moduleConfig = new ModuleConfig();
        $moduleMetricManager = new ModuleMetricManager(
            $this->db,
            $moduleConfig->getDvCmsModuleApiProdUrl(),
            $dvOptions->getApiUrl(),
            $dvOptions->getAuthToken()
        );

        $moduleMetricManager->checkoutOrder();
    }

    private function rememberShippingDetails(int $orderId)
    {
        $requiredDate       = $this->session->data['dvbusiness']['required_date'] ?? date('Y-m-d');
        $requiredStartTime  = $this->session->data['dvbusiness']['required_start_time'] ?? '16:00';
        $requiredFinishTime = $this->session->data['dvbusiness']['required_finish_time'] ?? '20:00';

        $this->load->library('dvbusiness/autoloader');

        $orderManager = new OrderManager($this->db);
        $orderManager->saveShippingDetails($orderId, $requiredDate, $requiredStartTime, $requiredFinishTime);
    }
}
