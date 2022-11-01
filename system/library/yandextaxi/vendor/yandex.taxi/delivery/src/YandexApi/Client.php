<?php

namespace YandexTaxi\Delivery\YandexApi;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\YandexApi\Exceptions\InvalidCursor;
use YandexTaxi\Delivery\YandexApi\Exceptions\NotAuthorizedException;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\Http\Client as HttpClient;

/**
 * Class Client
 *
 * @package YandexTaxi\Delivery\YandexApi
 */
class Client
{
    public const API_V1 = 1;
    public const API_V2 = 2;

    private const BASE_URL_TEST = 'https://b2b.taxi.tst.yandex.net/b2b';
    private const BASE_URL_PROD = 'https://b2b.taxi.yandex.net/b2b';

    /** @var string */
    private $token;

    /** @var boolean */
    private $useProdApi;

    /** @var string */
    private $referralSourceName;

    /** @var HttpClient */
    private $httpClient;

    /** @var string */
    private $acceptLanguage;

    public function __construct(
        HttpClient $httpClient,
        string $token,
        bool $userProdApi = true,
        string $referralSourceName = '',
        string $acceptLanguage = 'ru_RU'
    )
    {
        $this->token = $token;
        $this->useProdApi = $userProdApi;
        $this->referralSourceName = $referralSourceName;
        $this->httpClient = $httpClient;
        $this->acceptLanguage = $acceptLanguage;
    }

    public function getReferralSourceName(): string
    {
        return $this->referralSourceName;
    }

    /**
     * @param string $base
     * @param string $path
     * @param int    $apiVersion
     * @param array  $options
     *
     * @return mixed
     * @throws YandexApiException
     */
    public function call(string $base, string $path, int $apiVersion, array $options)
    {
        $response = $this->httpClient->sendPost(
            $this->buildUrl($base, $apiVersion, $path, $options['query'] ?? []),
            $options['json'] ?? [],
            [
                'Authorization' => "Bearer {$this->token}",
                'Accept-Language' => $this->acceptLanguage,
                'Content-Type' => 'application/json',
            ]
        );

        $result = json_decode($response->getContent(), true);

        if (is_null($result)) {
            throw new YandexApiException("Was not able to decode response, raw: {$response->getContent()}");
        }

        if ($response->getCode() !== 200) {
            $this->throwYandexApiException($result);
        }

        return $result;
    }

    /**
     * @param array $result
     *
     * @throws InvalidCursor
     * @throws YandexApiException
     */
    private function throwYandexApiException(array $result): void
    {
        $code = $result['code'];
        $message = $result['message'];

        if ($code === 'invalid_cursor') {
            throw new InvalidCursor($message);
        }

        if ($code === 'unauthorized') {
            throw new NotAuthorizedException($message);
        }

        throw new YandexApiException($message);
    }

    private function buildUrl(string $base, int $apiVersion, string $path, array $query = []): string
    {
        $query = http_build_query(array_merge($query, [
            'request_id' => uniqid(),
        ]));

        return $this->getBaseUrl() . "/{$base}/v{$apiVersion}/{$path}?{$query}";
    }

    private function getBaseUrl(): string
    {
        return $this->useProdApi ? self::BASE_URL_PROD : self::BASE_URL_TEST;
    }
}
