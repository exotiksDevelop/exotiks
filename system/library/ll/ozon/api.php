<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
namespace LL\OZON;

/**
 * Класс для работы с API
 */
class API {
	protected $ll;
	protected $config;

	public function __construct($ll) {
		$this->ll = $ll;
		$this->config = $this->ll->config;
	}

	/**
	 * Получение токена
	 */
	public function auth() {
		$token = null;

		$params = [
			'grant_type'    => 'client_credentials',
			'client_id'     => $this->config->get($this->ll->getPrefix() . '_client_id'),
			'client_secret' => $this->config->get($this->ll->getPrefix() . '_client_secret'),
		];

		$result = $this->ll->curl('', $params, 'form');

		if (isset($result['access_token'])) {
			$token = $result['access_token'];
		}

		return $token;
	}

	/**
	 * Создание отправления
	 * 
	 * @param  array $order
	 * @return array $result
	 */
	public function send_order($order) {
		$method = 'order';
		$params = json_encode($order);
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header);

		if ($result) {
			return $result;
		}
	}

	/**
	 * Изменение отправления
	 * 
	 * @param  array $order
	 * @return array $result
	 */
	public function update_order($order) {
		$method = 'order';
		$params = json_encode($order);
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header, false, true);

		if ($result) {
			return $result;
		}
	}

	/**
	 * Отмена отправлений
	 * 
	 * @param  array $ids
	 * @return array $result
	 */
	public function canceled($ids) {
		$method = 'order/status/canceled';
		$params = json_encode($ids);
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header, false, true);

		if ($result) {
			return $result;
		}
	}

	/**
	 * Получение этикетки
	 * 
	 * @param  array $orderIds
	 * @return array $result
	 */
	public function get_label($orderIds) {
		$method = 'ticket';
		$params = $orderIds;
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header, false);

		if ($result && substr($result, 0, 4) == '%PDF') {
			return file_put_contents(DIR_UPLOAD . $this->ll->code . '_label.pdf', $result);
		}
	}

	/**
	 * Обновление статусов отправления
	 * 
	 * @param  array $articles
	 * @return array $result
	 */
	public function update_status($articles) {
		$method = 'tracking/list';
		$params = json_encode($articles);
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header);

		if ($result) {
			return $result;
		}
	}

	/**
	 * Создание отгрузки dropoff
	 * 
	 * @param  array $orderIds
	 * @return array $result
	 */
	public function send_dropoff($orderIds) {
		$method = 'shipmentRequest';
		$params = json_encode($orderIds);
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header);

		if ($result) {
			return $result;
		}
	}

	/**
	 * Создание отгрузки pickup
	 * 
	 * @param  array $orderIds
	 * @return array $result
	 */
	public function send_pickup($orderIds) {
		$method = 'shipmentRequest/pickup';
		$params = json_encode($orderIds);
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header);

		if ($result) {
			return $result;
		}
	}

	/**
	 * Получение документов
	 * 
	 * @param  array $orderIds
	 * @return array $result
	 */
	public function get_documents($shipmentRequestIds) {
		$method = 'shipmentRequest/documents';
		$params = ['shipmentRequestIds' => $shipmentRequestIds, 'type' => 'WaybillAndAct', 'format' => 'PDF'];
		$header = ['Authorization: Bearer ' . $this->auth(), 'Content-Type: application/json'];

		$result = $this->ll->curl($method, $params, $header, false);

		if ($result && substr($result, 0, 4) == '%PDF') {
			return file_put_contents(DIR_UPLOAD . $this->ll->code . '_print.pdf', $result);
		}
	}
}
