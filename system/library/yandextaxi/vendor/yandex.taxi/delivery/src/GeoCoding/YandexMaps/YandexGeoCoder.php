<?php

namespace YandexTaxi\Delivery\GeoCoding\YandexMaps;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\GeoCoding\Exceptions\GeoCodingException;
use YandexTaxi\Delivery\GeoCoding\GeoCoderInterface;
use YandexTaxi\Delivery\GeoCoding\Point;
use YandexTaxi\Delivery\Http\Client as HttpClient;

/**
 * Class YandexGeoCoding
 *
 * @package YandexTaxi\Delivery\GeoCoding\YandexMaps
 */
final class YandexGeoCoder implements GeoCoderInterface
{
    private const BASE_URL = 'https://geocode-maps.yandex.ru/1.x';

    /** @var string */
    private $token;

    /** @var HttpClient */
    private $httpClient;

    public function __construct(HttpClient $httpClient, string $token)
    {
        $this->token = $token;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $address
     *
     * @return Point
     * @throws GeoCodingException
     */
    public function decode(string $address): Point
    {
        $result = $this->call(['geocode' => $address]);

        if (!isset($result['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
            throw new GeoCodingException('Не удалось расшифровать адрес');
        }

        $position = $result['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];

        list($lon, $lat) = explode(' ', $position);

        return new Point($lat, $lon);
    }

    /**
     * @param array $options
     *
     * @return mixed
     * @throws GeoCodingException
     */
    public function call(array $options)
    {
        $response = $this->httpClient->sendGet($this->buildUrl($options), []);

        if ($response->getCode() !== 200) {
            throw new GeoCodingException($response->getContent());
        }

        return json_decode($response->getContent(), true);
    }

    private function buildUrl(array $query = []): string
    {
        $query = http_build_query(array_merge($query, [
            'format' => 'json',
            'apikey' => $this->token,
        ]));

        return self::BASE_URL . "?{$query}";
    }
}
