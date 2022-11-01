<?php

namespace DvBusiness\ApiClient;

use DvBusiness\ApiClient\Request\OrderRequestModel;
use DvBusiness\ApiClient\Response\ApiSettingsResponseModel;
use DvBusiness\ApiClient\Response\OrderResponseModel;
use DvBusiness\ApiClient\Request\AddEventRequestModel;

class DvCmsModuleApiClient
{
    const API_VERSION         = '1.0';
    const ENCODING_UTF8       = 'utf-8';
    const ENCODING_UTF8_ALIAS = 'utf8';

    /** @var string */
    private $apiUrl;

    /** @var string|null */
    private $authToken;

    /** @var int */
    private $timeout;

    /** @var string */
    private $encoding;

    public function __construct(string $apiUrl, string $authToken = null, int $timeout = 10, $encoding = null)
    {
        $this->apiUrl    = $apiUrl;
        $this->authToken = $authToken;
        $this->timeout   = $timeout;
        $this->encoding  = strtolower($encoding ?? ini_get('default_charset'));
    }

    private function isUtf8Encoding(): bool
    {
        return in_array($this->encoding, [static::ENCODING_UTF8, static::ENCODING_UTF8_ALIAS]);
    }

    /**
     * @param DvCmsModuleApiRequest $request
     * @return DvCmsModuleApiResponse
     *
     * @throws DvCmsModuleApiHttpException
     */
    private function sendRequest(DvCmsModuleApiRequest $request): DvCmsModuleApiResponse
    {
        /** @todo CEventLog */

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl . '/' . static::API_VERSION . '/'. $request->getRequestUrl());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getHttpMethod());

        $headers = [
            'User-Agent: OpenCart Dostavista Business module',
        ];
        if ($this->authToken) {
            $headers[] = 'X-DV-Auth-Token: ' . $this->authToken;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);

        if ($request->getHttpMethod() === DvCmsModuleApiRequest::HTTP_METHOD_POST) {
            $requestData = $request->getData();
            if ($this->encoding && !$this->isUtf8Encoding()) {
                array_walk_recursive($requestData, function(&$value) {
                    $value = iconv($this->encoding, static::ENCODING_UTF8, $value);
                });
            }

            $json = json_encode($requestData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }

        $result   = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($result === false || !in_array($httpCode, [200, 201, 400])) {
            throw (new DvCmsModuleApiHttpException(curl_error($curl), curl_errno($curl)))
                ->setResponseBody((string) $result)
                ->setHttpCode((string) $httpCode);
        }

        curl_close($curl);

        return new DvCmsModuleApiResponse($result);
    }

    /**
     * @param OrderRequestModel $orderRequestModel
     * @return DvCmsModuleApiResponse
     *
     * @throws DvCmsModuleApiHttpException
     */
    public function calculateOrder(OrderRequestModel $orderRequestModel): DvCmsModuleApiResponse
    {
        $requestData = $orderRequestModel->getRequestData();
        $request = new DvCmsModuleApiRequest(
            $requestData,
            DvCmsModuleApiRequest::HTTP_METHOD_POST,
            'calculate-order'
        );

        return $this->sendRequest($request);
    }

    /**
     * @param OrderRequestModel $orderRequestModel
     * @return DvCmsModuleApiResponse
     *
     * @throws DvCmsModuleApiHttpException
     */
    public function createOrder(OrderRequestModel $orderRequestModel): DvCmsModuleApiResponse
    {
        $requestData = $orderRequestModel->getRequestData();
        $request = new DvCmsModuleApiRequest(
            $requestData,
            DvCmsModuleApiRequest::HTTP_METHOD_POST,
            'create-order'
        );

        return $this->sendRequest($request);
    }

    /**
     * @param int $orderId
     * @return OrderResponseModel
     *
     * @throws DvCmsModuleApiHttpException
     */
    public function getOrder(int $orderId): OrderResponseModel
    {
        $request = new DvCmsModuleApiRequest(
            ['order_id' => $orderId],
            DvCmsModuleApiRequest::HTTP_METHOD_GET,
            'orders'
        );

        $response = $this->sendRequest($request);

        return new OrderResponseModel($response->getData()['orders'][0] ?? []);
    }

    /**
     * @param string $phone
     * @param string $password
     * @return DvCmsModuleApiResponse
     *
     * @throws DvCmsModuleApiHttpException
     */
    public function createPersonAuthToken(string $phone, string $password): DvCmsModuleApiResponse
    {
        $request = new DvCmsModuleApiRequest(
            ['phone' => $phone, 'password' => $password],
            DvCmsModuleApiRequest::HTTP_METHOD_POST,
            'create-auth-token'
        );

        return $this->sendRequest($request);
    }

    /**
     * @param string $email
     * @param string $password
     * @return DvCmsModuleApiResponse
     *
     * @throws DvCmsModuleApiHttpException
     */
    public function createOrganizationAuthToken(string $email, string $password): DvCmsModuleApiResponse
    {
        $request = new DvCmsModuleApiRequest(
            ['email' => $email, 'password' => $password],
            DvCmsModuleApiRequest::HTTP_METHOD_POST,
            'create-auth-token'
        );

        return $this->sendRequest($request);
    }

    /**
     * @param string $callbackUrl
     * @return ApiSettingsResponseModel
     *
     * @throws DvCmsModuleApiHttpException
     */
    public function editApiSettings(string $callbackUrl): ApiSettingsResponseModel
    {
        $request = new DvCmsModuleApiRequest(
            [
                'callback_url'                   => $callbackUrl,
                'are_delivery_callbacks_enabled' => true,
            ],
            DvCmsModuleApiRequest::HTTP_METHOD_POST,
            'edit-api-settings'
        );

        $response = $this->sendRequest($request);
        return new ApiSettingsResponseModel($response->getData()['api_settings'] ?? null);
    }

    public function bankCards(): DvCmsModuleApiResponse
    {
        $request = new DvCmsModuleApiRequest(
            [],
            DvCmsModuleApiRequest::HTTP_METHOD_GET,
            'bank-cards'
        );
        return $this->sendRequest($request);
    }

    public function getVehicleTypes(): DvCmsModuleApiResponse
    {
        $request = new DvCmsModuleApiRequest(
            [],
            DvCmsModuleApiRequest::HTTP_METHOD_GET,
            'vehicle-types'
        );

        return $this->sendRequest($request);
    }

    public function getClientProfile(): DvCmsModuleApiResponse
    {
        $request = new DvCmsModuleApiRequest(
            [],
            DvCmsModuleApiRequest::HTTP_METHOD_GET,
            'client-profile'
        );

        return $this->sendRequest($request);
    }

    public function addEvent(AddEventRequestModel $requestModel): DvCmsModuleApiResponse
    {
        $requestData = $requestModel->getRequestData();
        $request     = new DvCmsModuleApiRequest(
            $requestData,
            DvCmsModuleApiRequest::HTTP_METHOD_POST,
            'add-event'
        );

        return $this->sendRequest($request);
    }
}
