<?php

namespace DvBusiness\BankCard;

class BankCard
{
    /** @var int */
    private $bankCardId;

    /** @var string */
    private $bankCardNumberMask;

    /** @var string */
    private $expirationDate;

    /** @var string|null */
    private $cardType;

    /** @var bool */
    private $isLastUsed;

    public function __construct(int $bankCardId, string $bankCardNumberMask, string $expirationDate, string $cardType = null, bool $isLastUsed)
    {
        $this->bankCardId         = $bankCardId;
        $this->bankCardNumberMask = $bankCardNumberMask;
        $this->expirationDate     = $expirationDate;
        $this->cardType           = $cardType;
        $this->isLastUsed         = $isLastUsed;
    }

    public function getBankCardId(): int
    {
        return $this->bankCardId;
    }

    public function getBankCardNumberMask(): string
    {
        return  $this->bankCardNumberMask;
    }

    public function getIsLastUsed(): bool
    {
        return $this->isLastUsed;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    /**
     * @return string|null
     */
    public function getCardType()
    {
        return $this->cardType;
    }

}
