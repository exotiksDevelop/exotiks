<?php

namespace DvBusiness\BankCard;


class BankCardCollectionSerializer
{
    /**
     * @param BankCard[] $bankCards
     */
    public static function serialize(array $bankCards): string
    {
        $data = [];

        foreach ($bankCards as $bankCard) {
            $data[] = [
                'bankCardId'         => $bankCard->getBankCardId(),
                'bankCardNumberMask' => $bankCard->getBankCardNumberMask(),
                'expirationDate'     => $bankCard->getExpirationDate(),
                'cardType'           => $bankCard->getCardType(),
                'isLastUsed'         => $bankCard->getIsLastUsed(),
            ];
        }

        return serialize($data);
    }

    /**
     * @param string $serializedData
     * @return BankCard[]|array
     */
    public static function unserialize(string $serializedData): array
    {
        $unserializedData = unserialize($serializedData);

        if (!is_array($unserializedData)) {
            return [];
        }
        $cards = [];
        foreach ($unserializedData as $unserializedItem) {
            $cards[] = new BankCard(
                (int) $unserializedItem['bankCardId'],
                $unserializedItem['bankCardNumberMask'],
                $unserializedItem['expirationDate'],
                $unserializedItem['cardType'],
                (bool) $unserializedItem['isLastUsed']
            );
        }

        return $cards;
    }
}