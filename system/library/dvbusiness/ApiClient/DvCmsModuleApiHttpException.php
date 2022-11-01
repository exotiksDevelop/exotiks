<?php

namespace DvBusiness\ApiClient;

use Exception;

class DvCmsModuleApiHttpException extends Exception
{
    /** @var string */
    private $responseBody = '';

    /** @var string */
    private $httpCode = '';

    public function setResponseBody(string $responseBody): DvCmsModuleApiHttpException
    {
        $this->responseBody = $responseBody;
        return $this;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    public function setHttpCode(string $httpCode): DvCmsModuleApiHttpException
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    public function getHttpCode(): string
    {
        return $this->httpCode;
    }
}
