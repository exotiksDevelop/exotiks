<?php
class ControllerModuleCdlOzonSellerRequest extends Controller
{
  public function request()
  {
    $url = urldecode($this->request->get['url']);
    $data = file_get_contents('php://input');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Host: api-seller.ozon.ru',
      'Client-Id: ' . $this->config->get('ozon_seller_client_id'),
      'Api-Key: ' . $this->config->get('ozon_seller_api_key')
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    if (!curl_errno($ch)) {
      $info = curl_getinfo($ch);
      switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
       case 200:
         break;
       default:
         echo 'Неожиданный код HTTP: ', $http_code, "\n";
      }
    }
    curl_close($ch);
  }
}
