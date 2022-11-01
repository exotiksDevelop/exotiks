<?php
class ModelTotalBBCod extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {
        if ((!empty($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'bb_payment')
            || (isset($this->request->post['payment_code']) && $this->request->post['payment_code'] == 'bb_payment')) {
            if (!empty($this->session->data['shipping_method'])) {

                    $this->language->load('total/bb');
                    $cost = round(($total / 100 * 2.5), 1, PHP_ROUND_HALF_UP);
                    $total_data[] = array(
                        'code'       => 'bb_cod',
                        'title'      => $this->language->get('text_bb_cod'),
                        'text'       => $this->currency->format($cost),
                        'value'      => $cost,
                        'sort_order' => $this->config->get('bb_cod_sort_order')
                    );
                    
                    $total += $cost;
                }
    }
    }
}
?>