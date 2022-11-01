<?php

namespace YandexTaxi\Http;

use YandexTaxi\Delivery\Http\Client;
use YandexTaxi\Delivery\Http\Response;

/**
 * Class CurlClient
 *
 * @package YandexTaxi\Delivery\Http
 */
class CurlClient implements Client {
    public function sendPost(string $url, array $body, array $headers): Response {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        $json = !empty($body) ? json_encode($body) : '';

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        $preparedHeaders = $this->prepareHeaders($headers);
        $preparedHeaders[] = 'Content-Length: ' . strlen($json);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $preparedHeaders);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $rawResult = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return new Response($httpCode, $rawResult);
    }

    public function sendGet(string $url, array $headers): Response {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $rawResult = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return new Response($httpCode, $rawResult);
    }

    private function prepareHeaders(array $headers): array {
        $preparedHeaders = [];

        foreach ($headers as $key => $value)
        {
            $preparedHeaders[] = "{$key}: {$value}";
        }

        return $preparedHeaders;
    }
}
