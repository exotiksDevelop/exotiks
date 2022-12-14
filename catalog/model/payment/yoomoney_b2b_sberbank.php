<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'yoomoney'.DIRECTORY_SEPARATOR.'autoload.php';

use YooMoneyModule\Model\KassaModel;

class ModelExtensionPaymentYoomoneyB2bSberbank extends Model
{
    private $kassaModel;

    private $client;

    private $_prefix;

    /**
     * @return KassaModel
     */
    public function getKassaModel()
    {
        if ($this->kassaModel === null) {
            $this->kassaModel = new KassaModel($this->config);
        }

        return $this->kassaModel;
    }

    /**
     * @return \YooMoneyModule\Model\AbstractPaymentModel|null
     */
    public function getPaymentModel()
    {
        if ($this->getKassaModel()->isEnabled()) {
            return $this->getKassaModel();
        }

        return null;
    }

    public function getMethod($address, $total)
    {
        $result = array();
        $this->load->language($this->getPrefix().'payment/yoomoney');

        $model = $this->getPaymentModel();
        if (is_null($model) || $model->getMinPaymentAmount() > 0 && $model->getMinPaymentAmount() > $total) {
            return $result;
        }

        if ($model->getGeoZoneId() > 0) {
            $query = $this->db->query(
                "SELECT * FROM `".DB_PREFIX."zone_to_geo_zone` WHERE `geo_zone_id` = '"
                .(int)$model->getGeoZoneId()."' AND country_id = '".(int)$address['country_id']
                ."' AND (zone_id = '".(int)$address['zone_id']."' OR zone_id = '0')"
            );
            if (empty($query->num_rows)) {
                return $result;
            }
        }
        $result = array(
            'code'       => 'yoomoney_b2b_sberbank',
            'title'      => $this->language->get('yoomoney_b2b_sberbank'),
            'terms'      => '',
            'sort_order' => $model->getSortOrder(),
        );

        return $result;
    }

    public function getClient()
    {
        if ($this->client === null) {
            $this->client = new \YooKassa\Client();
            $this->setClientAuth();
            $this->client->setLogger($this);
        }

        return $this->client;
    }

    /**
     * ?????????????????????????? ?????????? ?????????????????????? ?????????????? ??????????,
     * ?????????????????? - OAuth ??????????
     *
     * @return void
     */
    private function setClientAuth()
    {
        if (!empty($this->getKassaModel()->getOauthToken())) {
            $this->client->setAuthToken($this->getKassaModel()->getOauthToken());
            return;
        }

        $this->client->setAuth(
            $this->getKassaModel()->getShopId(),
            $this->getKassaModel()->getPassword()
        );
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, $context = array())
    {
        if ($this->getKassaModel()->getDebugLog()) {
            $log     = new Log('yoomoney.log');
            $search  = array();
            $replace = array();
            if (!empty($context)) {
                foreach ($context as $key => $value) {
                    $search[]  = '{'.$key.'}';
                    $replace[] = (is_array($value)||is_object($value)) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
                }
            }
            $sessionId = $this->session->getId();
            $userId    = 0;
            if (isset($this->session->data['user_id'])) {
                $userId = $this->session->data['user_id'];
            }
            $message = strip_tags($message);
            if (empty($search)) {
                $log->write('['.$level.'] ['.$userId.'] ['.$sessionId.'] - '.$message);
            } else {
                $log->write(
                    '['.$level.'] ['.$userId.'] ['.$sessionId.'] - '
                    .str_replace($search, $replace, $message)
                );
            }
        }
    }

    /**
     * @param int $orderId
     */
    public function confirmOrder($orderId)
    {
        $this->load->model('checkout/order');
        $url     = $this->url->link($this->getPrefix().'payment/yoomoney/repay', 'order_id='.$orderId, true);
        $comment = '<a href="'.$url.'" class="button">'.$this->language->get('text_repay').'</a>';
        $this->model_checkout_order->addOrderHistory($orderId, 1, $comment);
    }

    private function getPrefix()
    {
        if ($this->_prefix === null) {
            $this->_prefix = '';
            if (version_compare(VERSION, '2.3.0') >= 0) {
                $this->_prefix = 'extension/';
            }
        }

        return $this->_prefix;
    }
}

class ModelPaymentYoomoneyB2bSberbank extends ModelExtensionPaymentYoomoneyB2bSberbank
{
}