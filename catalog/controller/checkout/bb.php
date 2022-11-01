<?php
class ControllerCheckoutBB extends Controller {

    private $opencartVersion;

    public function index() {}

    public function __construct($registry) {
        parent::__construct($registry);
        $this->registry = $registry;
        $ov = explode('.', VERSION);
        $this->opencartVersion = floatval($ov[0].$ov[1].$ov[2].'.'.(isset($ov[3]) ? $ov[3] : 0));
    }

    private function getModelPath() {
        return $this->opencartVersion < 230 ? 'shipping/bb' : 'extension/shipping/bb';
    }

    private function cmp_func($a, $b) {
        return strcmp(mb_strtoupper($a['Name']), mb_strtoupper($b['Name']));
    }

    public function getPvzMapPoints() {
        $this->load->model($this->getModelPath());


        if ($this->opencartVersion < 230)
            $points = $this->model_shipping_bb->getPVZMapPoints();
        else
            $points = $this->model_extension_shipping_bb->getPVZMapPoints();

        $json = array();
        $om = array();
        $cities = array();

        $om['type'] = 'FeatureCollection';
        $features = array();

        $selected_pvz = (isset($this->session->data['bb_shipping_pvz_id']) && $this->session->data['bb_shipping_pvz_id']) ? $this->session->data['bb_shipping_pvz_id'] : 0;
        $position = array('location' => '55.76, 37.64', 'zoom' => 10);

        foreach($points as $point) {
            $gps = false;
            if (isset($point['Gps'])) {
                $gps = $point['Gps'];
            }
            if (isset($point['GPS'])) {
                $gps = $point['GPS'];
            }
            if (!$gps) continue;
	    $gps = preg_replace('/\s/', '', $gps);

            if (!isset($cities[$point['CityCode']])) {
                $cities[$point['CityCode']] = array('Name' => $point['CityName'], 'GPS' => $gps);
            }
            if ($selected_pvz && $point['Code'] === $selected_pvz) {
                $position['location'] = $gps;
                $position['zoom'] = 15;
            }
            $feature = array();
            $feature['type'] = 'Feature';
            $feature['id'] = $point['Code'];
            $geometry = array();
            $geometry['type'] = 'Point';
            $geometry['coordinates'] = explode(',', $gps);
            $feature['geometry'] = $geometry;
            $properties = array();
            $properties['pvz_id'] = $point['Code'];
            $pvz_name_arr = explode('_', $point['Name']);
            $properties['pvz_name'] = empty($pvz_name_arr) ? $point['Name'] : $pvz_name_arr[0];
            $properties['pvz_addr'] = $point['Address'];
            $properties['phone'] = $point['Phone'];
            $properties['cod'] = ($point['OnlyPrepaidOrders'] == 'No') ? 0 : 1;
            $properties['no_kd'] = isset($point['NalKD']) ? ($point['NalKD'] == 'No') : '';
            $schedule = array_key_exists('WorkSchedule', $point) ? $point['WorkSchedule'] : $point['WorkShedule'];
            $properties['work'] = $schedule;
            $properties['zone'] = $point['TariffZone'];
            $properties['city'] = $point['CityCode'];
	        $properties['period'] = $point['DeliveryPeriod'];
            $feature['properties'] = $properties;
            $features[] = $feature;
        }

        usort($cities, array($this, "cmp_func"));
        $ret = '<ul style="-webkit-padding-start: 10px; list-style: none;">';
        foreach($cities as $key=>$city) {
            $ret .= '<li style="margin-bottom: 1em;"><a href="#" style="outline:none; text-transform: uppercase; cursor: pointer; text-decoration: none;font-size: 18px;" data-city="'.$key.'" data-gps="'.$city['GPS'].'" class="select-city-anchor">'.$city['Name'].'</a></li>';
        }
        $ret .= '</ul>';

        $om['features'] = $features;
        $json['om'] = $om;
        $json['cities'] = $ret;

        if (!$selected_pvz) {
            if ($this->opencartVersion < 230)
                $position = $this->model_shipping_bb->getGeoCodingData();
            else
                $position = $this->model_extension_shipping_bb->getGeoCodingData();
        }
        $json['position'] = $position;

        $this->response->addHeader('Content-Type: application/json; charset=utf-8');
        $this->response->setOutput(json_encode($json));

    }

    public function fastorder_fix() {
        $json  = array();
        $payment_method = isset($this->request->post['payment_method']) ? $this->request->post['payment_method'] : false;
        if ($payment_method) {
            if (isset($this->session->data['payment_methods'][$payment_method]))
                $this->session->data['payment_method'] = $payment_method = $this->session->data['payment_methods'][$payment_method];
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function select_pvz() {
        $json = array();
        $pvz_id = isset($this->request->get['pvz_id']) ? $this->request->get['pvz_id'] : 0;
        $json['skip'] = 1;
        $json['id'] = $pvz_id;
        if ($pvz_id) {
            $this->load->model($this->getModelPath());
            if ($this->opencartVersion < 230)
                $result = $this->model_shipping_bb->getPVZById($pvz_id, true);
            else
                $result = $this->model_extension_shipping_bb->getPVZById($pvz_id, true);
            if ($result) {
                $this->session->data['bb_shipping_pvz_id'] = $pvz_id;
                $this->session->data['bb_shipping_city_id'] = $result['CityCode'];
                $this->session->data['bb_shipping_typed_city'] = $result['CityName'];
                $this->session->data['bb_shipping_typed_zone_id'] = $this->opencartVersion < 230 ? $this->model_shipping_bb->getZoneIdByName($result['Area']) : $this->model_extension_shipping_bb->getZoneIdByName($result['Area']);
                $this->session->data['bb_shipping_office_addr1'] = $result['Address'];
                $schedule = array_key_exists('WorkSchedule', $result) ? $result['WorkSchedule'] : $result['WorkShedule'];
                $this->session->data['bb_shipping_office_addr2'] = $result['Phone'].', '.$schedule;
                $json['city'] = $this->session->data['bb_shipping_typed_city'];
                $json['zone_id'] = $this->session->data['bb_shipping_typed_zone_id'];
                $json['addr1'] = $result['AddressReduce'];
                $json['skip'] = 0;
                $fast_order_enabled = false;
                $fast_order_data = $this->config->get('fastorder_data');
                if ($fast_order_data) {
                    $fast_order_enabled = $fast_order_data['status'] == 1;
                }
                if ($fast_order_enabled) {
                    $this->session->data['shipping_address']['zone_id'] = $json['zone_id'];
                    $this->session->data['payment_address']['zone_id']  = $json['zone_id'];
                    $this->session->data['payment_address']['address_1'] = $json['addr1'];
                    $this->session->data['shipping_address']['address_1'] = $json['addr1'];
                    $this->session->data['payment_address']['city'] = $json['city'];
                    $this->session->data['shipping_address']['city'] = $json['city'];
                }
            }
            else
                $json['id'] = 0;
        }
        $this->response->addHeader('Content-Type: application/json; charset=utf-8');
        $this->response->setOutput(json_encode($json));
    }
}
?>