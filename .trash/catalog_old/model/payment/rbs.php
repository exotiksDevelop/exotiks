<?php
class ModelPaymentRbs extends Model {
    public function getMethod($address, $total) {
        $this->load->language('payment/rbs');

        $method_data = array(
            'code'     => 'rbs',
            'title'    => $this->language->get('text_title'),
            'terms'      => '',
            'sort_order' => $this->config->get('custom_sort_order')
        );

        return $method_data;
    }
}