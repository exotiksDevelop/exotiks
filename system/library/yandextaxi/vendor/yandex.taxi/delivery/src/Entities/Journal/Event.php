<?php

namespace YandexTaxi\Delivery\Entities\Journal;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use DateTime;
use YandexTaxi\Delivery\Entities\Claim\Status;

/**
 * Class Event
 *
 * @package YandexTaxi\Delivery\Dto\Journal
 */
class Event
{
    private const STATUS_CHANGED = 'status_changed';

    /** @var string */
    private $claimId;

    /** @var string */
    private $changeType;

    /** @var Status|null */
    private $newStatus;

    /** @var DateTime */
    private $at;

    public function __construct(string $claimId, string $changeType, DateTime $at, ?Status $newStatus)
    {
        $this->claimId = $claimId;
        $this->changeType = $changeType;
        $this->newStatus = $newStatus;
        $this->at = $at;
    }

    public function getClaimId(): string
    {
        return $this->claimId;
    }

    public function statusWasChanged(): string
    {
        return $this->changeType === self::STATUS_CHANGED;
    }

    public function getNewStatus(): ?Status
    {
        return $this->newStatus;
    }

    public function getAt(): DateTime
    {
        return $this->at;
    }
}
