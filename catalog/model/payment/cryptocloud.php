<?php 
class ModelPaymentCryptocloud extends Model {
	public function getMethod($address, $total) {
		$title = $this->config->get('cryptocloud_title');

		return array(
			'code' => 'cryptocloud',
			'terms' => '',
			'title' => ($title ? $title : 'CRYPTOCLOUD'),
			'sort_order' => $this->config->get('cryptocloud_sort_order')
		);
	}
}
?>