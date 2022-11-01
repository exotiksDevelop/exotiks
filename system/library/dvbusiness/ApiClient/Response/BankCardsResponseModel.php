<?php

namespace DvBusiness\ApiClient\Response;

use DvBusiness\BankCard\BankCard;

class BankCardsResponseModel
{
    /** @var BankCard[] */
    private $cards;

    public function __construct(array $responseBankCardsData)
    {
        foreach ($responseBankCardsData['bank_cards'] as $cardData) {
            $this->cards[] = new BankCard(
                $cardData['bank_card_id'],
                $cardData['bank_card_number_mask'],
                $cardData['expiration_date'],
                $cardData['card_type'],
                $cardData['is_last_used']
            );
        }
    }

    /**
     * @return BankCard[]
     */
    public function getCards(): array
    {
        return $this->cards ?? [];
    }
}
