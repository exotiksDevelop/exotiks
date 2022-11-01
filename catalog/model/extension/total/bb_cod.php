<?php
class ModelExtensionTotalBBCod extends Model {

    public function getTotal($total) {
        if ((!empty($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'bb_payment')
            || (isset($this->request->post['payment_code']) && $this->request->post['payment_code'] == 'bb_payment')) {
            if (!empty($this->session->data['shipping_method'])) {
                	$ov = explode('.', VERSION);
                	$opencartVersion = floatval($ov[0].$ov[1].$ov[2].'.'.(isset($ov[3]) ? $ov[3] : 0));

                        if ($opencartVersion < 230)
                            $this->load->language('total/bb');
                        else
                            $this->load->language('extension/total/bb');
                    $cost = round(($total['total'] / 100 * 2.5), 1, PHP_ROUND_HALF_UP);
                    $total['totals'][] = array(
                        'code'       => 'bb_cod',
                        'title'      => $this->language->get('text_bb_cod'),
                        'value'      => $cost,
                        'sort_order' => $this->config->get('bb_cod_sort_order')
                    );
                    
                    $total['total'] += $cost;
                }
    	}
    }
}
?>