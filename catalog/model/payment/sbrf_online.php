<?php 
class ModelPaymentSbrfOnline extends Model {
    public function getMethod($address, $total) {
        $this->language->load('payment/sbrf_online');
        
        if (($this->config->get('sbrf_online_status')) && ($total) &&
            (!$this->config->get('sbrf_online_minimal_order') || ($total >= (float)$this->config->get('sbrf_online_minimal_order'))) &&
            (!$this->config->get('sbrf_online_maximal_order') || ($total <= (float)$this->config->get('sbrf_online_maximal_order')))) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sbrf_online_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
          
            if (!$this->config->get('sbrf_online_geo_zone_id')) {
                    $status = true;
                } elseif ($query->num_rows) {
                      $status = true;
                } else {
                    $status = false;
            }
        } else {
            $status = false;
        }
        
        $method_data = array();

        if ($status) {  
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $server = $this->config->get('config_ssl') . 'catalog/view/theme/default/image/';
            } else {
                $server = $this->config->get('config_url') . 'catalog/view/theme/default/image/';
            }

            $icon = $this->config->get('sbrf_online_icon') ? '<img src="' . $server . 'payment/sbrf.png" align="middle" /> ' : '';
      
            $langdata = $this->config->get('sbrf_online_langdata');
            $method_data = array( 
                'code'        => 'sbrf_online',
                'title'       => $icon . $langdata[$this->config->get('config_language_id')]['title'],
                'description' => html_entity_decode($langdata[$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8'),
                'terms'       => '',
                'sort_order'  => $this->config->get('sbrf_online_sort_order')
            );
        }
       
        return $method_data;
    }

    public function getShippingTotal($order_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' AND code = 'shipping'");
        return $query->num_rows ? $query->row['value'] : '';
    }
}
?>