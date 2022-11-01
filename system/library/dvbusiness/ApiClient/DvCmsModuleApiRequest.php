<?php

namespace DvBusiness\ApiClient;

class DvCmsModuleApiRequest
{
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET  = 'GET';

    /** @var array */
    private $data = [];

    /** @var string */
    private $httpMethod;

    /** @var string */
    private $apiMethod;

    public function __construct(array $data, string $httpMethod, string $apiMethod)
    {
        $this->data = $data;
        $this->httpMethod = $httpMethod;
        $this->apiMethod = $apiMethod;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getApiMethod(): string
    {
        return $this->apiMethod;
    }

    public function getRequestUrl(): string
    {
        return $this->getHttpMethod() === static::HTTP_METHOD_POST
            ? $this->getApiMethod()
            : $this->getApiMethod() . '?' . http_build_query($this->getData());
    }
}
