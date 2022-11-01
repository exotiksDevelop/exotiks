<?php

namespace DvBusiness\ApiClient\Response;

class CourierResponseModel
{
    /** @var array */
    private $responsePointData;

    /** @var string */
    private $name;

    /** @var string */
    private $phone;

    public function __construct(array $responsePointData)
    {
        $this->responsePointData = $responsePointData;

        $this->name  = $responsePointData['name'] ?? '';
        $this->phone = $responsePointData['phone'] ?? '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
