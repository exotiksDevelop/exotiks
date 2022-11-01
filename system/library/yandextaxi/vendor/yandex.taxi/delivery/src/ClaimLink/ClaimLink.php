<?php

namespace YandexTaxi\Delivery\ClaimLink;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class ClaimLink
 *
 * @package YandexTaxi\Delivery\ClaimLink
 */
class ClaimLink
{
    /** @var string */
    private $id;

    /** @var string */
    private $metaHash;

    /** @var string */
    private $address;

    /** @var float|null */
    private $lat;

    /** @var float|null */
    private $lon;

    /** @var string */
    private $claimId;

    /** @var int */
    private $version;

    public function __construct(
        string $id,
        string $metaHash,
        string $address,
        ?float $lat,
        ?float $lon,
        string $claimId,
        int $version = 1
    ) {
        $this->id = $id;
        $this->metaHash = $metaHash;
        $this->setGeo($address, $lat, $lon);
        $this->claimId = $claimId;
        $this->version = $version;
    }

    public function metaWasUpdated(string $newMetaHash): bool
    {
        return $this->metaHash !== $newMetaHash;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMetaHash(): string
    {
        return $this->metaHash;
    }

    public function setMetaHash(string $metaHash): void
    {
        $this->metaHash = $metaHash;
    }

    public function getClaimId(): string
    {
        return $this->claimId;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function incrementVersion(): void
    {
        $this->version++;
    }

    public function addressWasUpdated(string $newAddress): bool
    {
        return $this->address !== $newAddress;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setGeo(string $address, ?float $lat, ?float $lon): void
    {
        $this->address = $address;
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }
}
