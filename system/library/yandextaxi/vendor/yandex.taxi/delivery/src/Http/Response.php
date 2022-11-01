<?php

namespace YandexTaxi\Delivery\Http;

/**
 * Class Response
 *
 * @package YandexTaxi\Delivery\Http
 */
class Response
{
    /** @var int */
    private $code;

    /** @var string|null */
    private $content;

    public function __construct(int $code, ?string $content)
    {
        $this->code = $code;
        $this->content = $content;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
