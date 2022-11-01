<?php

namespace YandexTaxi\Delivery\Entities\RoutePoint;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class RoutePoint
 *
 * @package YandexTaxi\Delivery\Entities\RoutePoint
 */
class RoutePoint
{
    /** @var int */
    private $id;

    /** @var string|null */
    private $orderId = null;

    /** @var Contact */
    private $contact;

    /** @var Address */
    private $address;

    /** @var RoutePointVisitStatus|null */
    private $status;

    /** @var bool */
    private $sendConfirmationSms = true;

    public function __construct(
        Contact $contact,
        Address $address,
        bool $sendConfirmationSms = true,
        ?string $orderId = null
    ) {
        $this->id = random_int(0, PHP_INT_MAX);
        $this->contact = $contact;
        $this->address = $address;
        $this->sendConfirmationSms = $sendConfirmationSms;
        $this->orderId = $orderId;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function sendConfirmationSms(): bool
    {
        return $this->sendConfirmationSms;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getStatus(): ?RoutePointVisitStatus
    {
        return $this->status;
    }

    public function setStatus(RoutePointVisitStatus $status): void
    {
        $this->status = $status;
    }
}
