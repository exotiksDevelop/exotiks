<?php
/**
 * Контроль лицензии на модули. Оборащнение идет к серверу разработчика. Предаваемые Вами данные не сохраняются.
 */
class WbCdlRequest
{
  private $pass = 'cdlwb';

  private function domain()
  {
    $domain = parse_url(HTTPS_SERVER);
    return $domain['host'];
  }

  public function cdlRequest($request, $token, $data = array())
  {
    $url = 'https://shop.cdl-it.ru/index.php?route=extension/module/cdl_request_wb/pass&pass=' . $this->pass . '&domain=' . $this->domain() . '&request=' . $request . '&ver=1&token=' . $token;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		if (!empty($data)) {
			$data = json_encode($data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$response = curl_exec($ch);
		curl_close($ch);
		$response = @json_decode($response, true);
		return $response;
	}
}
?>
