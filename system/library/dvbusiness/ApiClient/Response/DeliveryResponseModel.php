<?php

namespace DvBusiness\ApiClient\Response;

class DeliveryResponseModel
{
    const STATUS_DRAFT            = 'draft';             // ��������
    const STATUS_PLANNED          = 'planned';           // ���� �������
    const STATUS_COURIER_ASSIGNED = 'courier_assigned';  // ������ ��������
    const STATUS_ACTIVE           = 'active';            // ������ �������� �����������
    const STATUS_PARCEL_PICKED_UP = 'parcel_picked_up';  // ������ ������ �����������
    const STATUS_COURIER_DEPARTED = 'courier_departed';  // ������ � ����
    const STATUS_COURIER_ARRIVED  = 'courier_arrived';   // ������ ���� ����������
    const STATUS_FINISHED         = 'finished';          // ����������
    const STATUS_FAILED           = 'failed';            // �� ����������
    const STATUS_CANCELED         = 'canceled';          // ��������
    const STATUS_DELAYED          = 'delayed';           // ��������

    /** @var array */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getClientOrderId()
    {
        return !empty($this->data['client_order_id']) ? (string) $this->data['client_order_id'] : null;
    }

    public function getStatus(): string
    {
        return $this->data['status'];
    }
}
