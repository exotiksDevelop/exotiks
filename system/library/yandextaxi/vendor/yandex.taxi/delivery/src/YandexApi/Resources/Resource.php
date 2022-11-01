<?php

namespace YandexTaxi\Delivery\YandexApi\Resources;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;

/**
 * Class Resource
 *
 * @package YandexTaxi\Delivery\YandexApi\Resources
 */
abstract class Resource
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    abstract protected function getBasePath(): string;

    public function getReferralSourceName(): string
    {
        return $this->client->getReferralSourceName();
    }

    /**
     * @param string $path
     * @param int    $apiVersion
     * @param array  $options
     *
     * @return mixed
     * @throws YandexApiException
     */
    protected function call(string $path, int $apiVersion, array $options)
    {
        return $this->client->call($this->getBasePath(), $path, $apiVersion, $options);
    }
}
