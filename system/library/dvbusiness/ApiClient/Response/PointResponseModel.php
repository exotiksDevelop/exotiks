<?php

namespace DvBusiness\ApiClient\Response;

class PointResponseModel
{
    /** @var array */
    private $responsePointData;

    /** @var string */
    private $address;

    /** @var string ISO 8601 */
    private $requiredStartDatetime;

    /** @var string ISO 8601 */
    private $requiredFinishDatetime;

    public function __construct(array $responsePointData)
    {
        $this->responsePointData = $responsePointData;

        $this->address                = $responsePointData['address'] ?? '';
        $this->requiredStartDatetime  = $responsePointData['required_start_datetime'] ?? '';
        $this->requiredFinishDatetime = $responsePointData['required_finish_datetime'] ?? '';
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getRequiredStartDatetime(): string
    {
        return $this->requiredStartDatetime;
    }

    public function getRequiredFinishDatetime(): string
    {
        return $this->requiredFinishDatetime;
    }
}
