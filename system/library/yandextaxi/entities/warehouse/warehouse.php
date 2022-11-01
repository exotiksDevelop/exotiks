<?php

namespace YandexTaxi\Entities\Warehouse;

use YandexTaxi\Delivery\PhoneNumber\Formatter;
use libphonenumber\NumberParseException;

/**
 * Class Warehouse
 *
 * @package YandexTaxi\Entities\Warehouse
 */
class Warehouse
{
    /** @var int|null */
    private $id;

    /** @var string */
    private $address;

    /** @var float|null */
    private $lat;

    /** @var float|null */
    private $lon;

    /** @var string */
    private $contactName;

    /** @var string */
    private $contactEmail;

    /** @var string|null */
    private $contactPhone;

    /** @var string */
    private $startTime;

    /** @var string */
    private $endTime;

    /** @var string */
    private $comment;

    /** @var string */
    private $flat;

    /** @var string */
    private $porch;

    /** @var string */
    private $floor;

    /**
     * Warehouse constructor.
     *
     * @param int|null $id
     * @param string   $address
     * @param float    $lat
     * @param float    $lon
     * @param string   $contactEmail
     * @param string   $contactName
     * @param string   $contactPhone
     * @param string   $startTime
     * @param string   $endTime
     * @param string   $comment
     * @param string   $flat
     * @param string   $porch
     * @param string   $floor
     *
     * @throws NumberParseException
     */
    public function __construct(
        ?int $id = null,
        string $address = '',
        float $lat = null,
        float $lon = null,
        string $contactEmail = '',
        string $contactName = '',
        ?string $contactPhone = null,
        string $startTime = '',
        string $endTime = '',
        string $comment = '',
        string $flat = '',
        string $porch = '',
        string $floor = ''
    ) {
        $this->setId($id);
        $this->setAddress($address);
        $this->setLat($lat);
        $this->setLon($lon);
        $this->setContactEmail($contactEmail);
        $this->setContactName($contactName);
        $this->setContactPhone($contactPhone);
        $this->setStartTime($startTime);
        $this->setEndTime($endTime);
        $this->setComment($comment);
        $this->setFloor($floor);
        $this->setPorch($porch);
        $this->setFlat($flat);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getFlat(): string
    {
        return $this->flat;
    }

    public function getPorch(): string
    {
        return $this->porch;
    }

    public function getFloor(): string
    {
        return $this->floor;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    public function setLon(?float $lon): void
    {
        $this->lon = $lon;
    }

    public function setContactEmail(string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function setContactName(string $contactName): void
    {
        $this->contactName = $contactName;
    }

    /**
     * @param string|null $contactPhone
     *
     * @throws NumberParseException
     */
    public function setContactPhone(?string $contactPhone): void
    {
        $this->contactPhone = is_null($contactPhone) ? null : Formatter::format($contactPhone);
    }

    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function setFlat(string $flat): void
    {
        $this->flat = $flat;
    }

    public function setPorch(string $porch): void
    {
        $this->porch = $porch;
    }

    public function setFloor(string $floor): void
    {
        $this->floor = $floor;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function isValid(): bool
    {
        if (empty($this->contactName)) {
            return false;
        }

        if (empty($this->contactPhone)) {
            return false;
        }

        if (empty($this->contactEmail)) {
            return false;
        }

        return true;
    }

    private function toArray(): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'contactName' => $this->contactName,
            'contactEmail' => $this->contactEmail,
            'contactPhone' => $this->contactPhone,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'comment' => $this->comment,
            'flat' => $this->flat,
            'porch' => $this->porch,
            'floor' => $this->floor,
        ];
    }
}
