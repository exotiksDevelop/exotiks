<?php

namespace DvBusiness\ApiClient;

class DvCmsModuleApiResponse
{
    /** @var string */
    private $rawResponseBody;

    /** @var array */
    private $data;

    public function __construct(string $rawResponseBody)
    {
        $this->rawResponseBody = $rawResponseBody;

        $responseAsArray = json_decode($rawResponseBody, true);
        if (!is_array($responseAsArray)) {
            $responseAsArray = [];
        }

        $this->data = $responseAsArray;
    }

    public function getRawResponseBody(): string
    {
        return $this->rawResponseBody;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isSuccessful(): bool
    {
        return empty($this->data['errors']);
    }

    public function getErrors(): array
    {
        return $this->data['errors'] ?? [];
    }

    public function getParameterErrors(): array
    {
        return $this->data['parameter_errors'] ?? [];
    }

    public function getWarnings(): array
    {
        return $this->data['warnings'] ?? [];
    }

    public function getParameterWarnings(): array
    {
        return $this->data['parameter_warnings'] ?? [];
    }
}
