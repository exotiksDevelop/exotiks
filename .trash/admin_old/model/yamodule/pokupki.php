<?php

Class ModelYamodulePokupki extends Model
{
    protected function getId($id){
        $query =$this->db->query('SELECT * FROM '.DB_PREFIX.'pokupki_orders WHERE `id_order`='.$id);
        return $query->row;
    }
    public function sendOrder($id, $order_status_id, $comment = '')
    {
        $json = array();
        $newstatus = null;
        if ($order_status_id==$this->config->get('ya_pokupki_status_cancelled')){ // Canseled
            $newstatus = 'CANCELLED';
        }elseif ($order_status_id==$this->config->get('ya_pokupki_status_delivery')){ // Shipped
            $newstatus = 'DELIVERY';
        }elseif ($order_status_id==$this->config->get('ya_pokupki_status_delivered')){ //Processed
            $newstatus = 'DELIVERED';
        }elseif ($order_status_id==$this->config->get('ya_pokupki_status_pickup')){ //Voided
            $newstatus = 'PICKUP';
        }
        $params = array(
            'order' => array(
                'status' => $newstatus,
            )
        );
        if($newstatus == 'CANCELLED') {
            $substatus = 'SHOP_FAILED';
            /*
             * Возможные значения:
                 PROCESSING_EXPIRED — магазин не обработал заказ вовремя;
                 REPLACING_ORDER — покупатель изменяет состав заказа;
                 RESERVATION_EXPIRED — покупатель не завершил оформление зарезервированного заказа вовремя;
                 SHOP_FAILED — магазин не может выполнить заказ;
                 USER_CHANGED_MIND — покупатель отменил заказ по собственным причинам;
                 USER_NOT_PAID — покупатель не оплатил заказ (для типа оплаты PREPAID);
                 USER_REFUSED_DELIVERY — покупателя не устраивают условия доставки;
                 USER_REFUSED_PRODUCT — покупателю не подошел товар;
                 USER_REFUSED_QUALITY — покупателя не устраивает качество товара;
                 USER_UNREACHABLE — не удалось связаться с покупателем.
             */
            foreach (array('PROCESSING_EXPIRED','REPLACING_ORDER','RESERVATION_EXPIRED','SHOP_FAILED','SHOP_FAILED',
                         'USER_CHANGED_MIND','USER_NOT_PAID','USER_REFUSED_DELIVERY','USER_REFUSED_PRODUCT',
                         'USER_REFUSED_QUALITY','USER_UNREACHABLE') as $value) if (preg_match('/'.$value.'/',$comment)==1) $substatus = $value;
            $params['order']['substatus'] = $substatus;
        }
        if ($newstatus!==null){
            $select = $this->getId($id);
            $id_market  = $select['id_market_order'];
            $number = $this->config->get('ya_pokupki_number');
            $answer = $this->SendResponse('/campaigns/'.$number.'/orders/'.$id_market.'/status', array(), $params, 'PUT');

            if (isset($answer->message)){
                $json['error'] = sprintf("Ответ по программе 'Заказы на Маркете':%s",$answer->message);
            }elseif (isset($answer->order->status)){
                $json['success'] = sprintf("Ответ по программе 'Заказы на Маркете': Заказу был присвоен статус %s",$answer->order->status);
            }
        }else{
            $json['error'] = sprintf("Новый статус %s для заказа %d не предопределен (%d, %d, %d, %d)",
                $order_status_id, $id, $this->config->get('ya_pokupki_status_cancelled'),$this->config->get('ya_pokupki_status_delivery'),
                $this->config->get('ya_pokupki_status_delivered'),$this->config->get('ya_pokupki_status_pickup')
                );
            $this->log_save("Текущий статус ".$order_status_id." не будет отправлен в Яндекс.Маркет");
        }
        return $json;
    }

    public function SendResponse($to, $headers, $params, $type)
    {
        $app_id = $this->config->get('ya_pokupki_idapp');
        $url = 'https://api.partner.market.yandex.ru/v2';
        $ya_token = $this->config->get('ya_pokupki_gtoken');
        $response = $this->post($url.$to.'.json?oauth_token='.$ya_token.'&oauth_client_id='.$app_id, $headers, $params, $type);
        $data = json_decode($response->body);

        if($response->status_code == 200){
            $this->log_save(print_r($response, true));
            return $data;
        }elseif (isset($data->error)){
            return $data->error;
        }else{
            $this->log_save(print_r($response, true));
            return false;
        }
    }

    protected static function log_save($logtext)
    {
        $error_log = new Log('error.log');
        $error_log->write($logtext.PHP_EOL);
        $error_log = null;
    }

    protected static function post($url, $headers, $params, $type){
        $curlOpt = array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLINFO_HEADER_OUT => 1,
        );

        switch (strtoupper($type)){
            case 'DELETE':
                $curlOpt[CURLOPT_CUSTOMREQUEST] = "DELETE";
            case 'GET':
                if (!empty($params))
                    $url .= (strpos($url, '?')===false ? '?' : '&') . http_build_query($params);
                break;
            case 'PUT':
                $headers[] = 'Content-Type: application/json;';
                $body = json_encode($params);
                $fp = tmpfile();
                fwrite($fp, $body, strlen($body));
                fseek($fp, 0);
                $curlOpt[CURLOPT_PUT] = true;
                $curlOpt[CURLOPT_INFILE] = $fp;
                $curlOpt[CURLOPT_INFILESIZE] = strlen($body);
                break;
        }

        $curlOpt[CURLOPT_HTTPHEADER] = $headers;
        $curl = curl_init($url);
        curl_setopt_array($curl, $curlOpt);
        $rbody = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Tools::d(curl_getinfo($curl, CURLINFO_HEADER_OUT));
        curl_close($curl);
        $result = new stdClass();
        $result->status_code = $rcode;
        $result->body = $rbody;
        $result->error = $error;
        return $result;
    }
}

Class ModelExtensionYamodulePokupki extends ModelYamodulePokupki{}