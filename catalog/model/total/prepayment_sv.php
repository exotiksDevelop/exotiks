<?php
class ModelTotalPrepaymentSV extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
    $value = $this->config->get('prepayment_sv_value');
    if (($value > 0) && (isset($this->session->data['shipping_method']))) {
    	$shipping_method = $this->session->data['shipping_method'];
    	$shipping_method = explode('.', $shipping_method['code']);
    	$shipping_method = array_shift($shipping_method);
      $shipping = $this->config->get('prepayment_sv_shipping');
      if (in_array($shipping_method, $shipping)) {
        $value = 0;
        $total_code = $this->config->get('prepayment_sv_total');
        foreach ($total_data as $td) {
          if (in_array($td['code'], $total_code)) {
            $value += $td['value'];
          }
        }
        
        if ($value > 0) {
          $prepayment = 0;
          $min_value = (float)$this->config->get('prepayment_sv_min_value');
          if ($this->config->get('prepayment_sv_type') == 'P') {
            $prepayment = $value * $this->config->get('prepayment_sv_value')/ 100;
            if (($min_value > 0) && ($prepayment < $min_value)) {
            	if ($this->config->get('prepayment_sv_reduce_prepayment')) {
								$prepayment = $min_value;
							} elseif  ($min_value > $value) {
	              $prepayment = $value;
              } else {
              	$prepayment = $min_value;
							}
            }
          } else {
            $prepayment = $this->config->get('prepayment_sv_value');
            if ($prepayment > $value) {
            	if ($this->config->get('prepayment_sv_reduce_prepayment')) {
            		$prepayment = $min_value;
            	} else {
	              $prepayment = $value;
							}
            }
          }
          
          
          $this->language->load('total/prepayment_sv');
          $total_data[] = array(
            'code'       => 'prepayment_sv',
            'title'      => $this->language->get('text_total'),
            'text'       => $this->currency->format(max(0, $prepayment)),
            'value'      => max(0, $prepayment),
            'sort_order' => $this->config->get('prepayment_sv_sort_order')
          );
          
          $total = max(0, $prepayment);
          
        }
      }
    }
	}
}
?>