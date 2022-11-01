<?php

namespace YandexTaxi\Delivery\Http;

/**
 * Interface Client
 *
 * @package YandexTaxi\Delivery\Http
 */
interface Client
{
    public function sendPost(string $url, array $body, array $headers): Response;

    public function sendGet(string $url, array $headers): Response;
}
