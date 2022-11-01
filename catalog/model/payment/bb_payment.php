<?php
class ModelPaymentBBPayment extends Model {

    public function getMethod($address, $total) {
        $status = $this->config->get('bb_payment_status');

        $method_data = array();

        if ($status) {
            $from = floatval($this->config->get('bb_payment_total_from'));
            $to = floatval($this->config->get('bb_payment_total_to'));
            if (intval($from) || intval($to)) {
                $subtotal = $this->cart->getSubTotal();
                if (intval($from) && intval($to)) {
                    if ($subtotal > $to || $subtotal < $from) $status = false;
                }
                else if (!intval($from)) {
                    if ($subtotal > $to) $status = false;
                }
                else if ($subtotal < $from) $status = false;
            }
        }

        if ($status) {
            if (!empty($this->session->data['shipping_method'])) {
                $sm = $this->session->data['shipping_method'];
                if (($sm['code'] == 'bb.pickup' || $sm['code'] == 'bb.kd') && isset($this->session->data['bb_shipping_cod'])) {
                    $kd = ($sm['code'] == 'bb.kd');
                    $pvz_id = isset($this->session->data['bb_shipping_pvz_id']) ? $this->session->data['bb_shipping_pvz_id'] : 0;
                    $cod = $this->session->data['bb_shipping_cod'] && ($kd || $pvz_id);
                    if ($cod) {
                        $show_icons = $this->config->get('bb_show_icons');
                        //$img_path  = ($show_icons) ? HTTP_SERVER.'image/delivery_bb/pickup.gif' : '';
                        $this->language->load('payment/bb');
                        $method_data = array(
                            'code'        => 'bb_payment',
                            //'image'      => $img_path,
                            'terms'      => '',
                            'title'       => $kd ? $this->language->get('text_title_courier') : $this->language->get('text_title_office'),
                            'sort_order'  => $this->config->get('bb_payment_sort_order')
                        );
                    }
                }
            }
        }

        return $method_data;
    }
}
?>