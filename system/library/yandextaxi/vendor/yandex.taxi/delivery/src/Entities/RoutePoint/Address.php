<?php

namespace YandexTaxi\Delivery\Entities\RoutePoint;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Address
 *
 * @package YandexTaxi\Delivery\Entities\RoutePoint
 */
class Address
{
    /** @var string */
    private $fullName;

    /** @var float|null */
    private $lat;

    /** @var float|null */
    private $lon;

    /** @var string|null */
    private $flat = null;

    /** @var string|null */
    private $porch = null;

    /** @var string|null */
    private $floor = null;

    /** @var string */
    private $comment = '';

    public function __construct(string $fullName, ?float $lat = null, ?float $lon = null, string $comment = '')
    {
        $this->fullName = $fullName;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->comment = $comment;
    }

    public function setFlat(?string $flat): void
    {
        $this->flat = $this->nullifyEmptyString($flat);
    }

    public function setPorch(?string $porch): void
    {
        $this->porch = $this->nullifyEmptyString($porch);
    }

    public function setFloor(?string $floor): void
    {
        $this->floor = $this->nullifyEmptyString($floor);
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function hasNoPoint(): bool
    {
        return is_null($this->getLat()) || is_null($this->getLon());
    }

    public function setPoint(float $lat, float $lon): void
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getFlat(): ?string
    {
        return $this->flat;
    }

    public function getPorch(): ?string
    {
        return $this->porch;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function toArray(): array
    {
        return [
            'fullName' => $this->fullName,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'flat' => $this->flat,
            'porch' => $this->porch,
            'floor' => $this->floor,
            'comment' => $this->comment,
        ];
    }

    private function nullifyEmptyString(?string $string): ?string
    {
        if (is_null($string)) {
            return null;
        }

        return trim($string) !== '' ? $string : null;
    }
}
