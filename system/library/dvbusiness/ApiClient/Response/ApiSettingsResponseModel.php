<?php

namespace DvBusiness\ApiClient\Response;

class ApiSettingsResponseModel
{
    /** @var string */
    private $callbackUrl;

    /** @var string */
    private $callbackSecretKey;

    public function __construct(array $responseData = null)
    {
        $this->callbackUrl       = $responseData['callback_url'] ?? '';
        $this->callbackSecretKey = $responseData['callback_secret_key'] ?? '';
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function getCallbackSecretKey(): string
    {
        return $this->callbackSecretKey;
    }
}
