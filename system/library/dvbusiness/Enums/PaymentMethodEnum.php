<?php

namespace DvBusiness\Enums;


class PaymentMethodEnum
{
    const PAYMENT_METHOD_CASH     = 'cash';       // Нал
    const PAYMENT_METHOD_NON_CASH = 'non_cash';   // Оплата с баланса Достависты, для юр.лиц
    const PAYMENT_METHOD_QIWI     = 'qiwi_split'; // Банковские карты только для России
    const PAYMENT_METHOD_BANK     = 'bank_card';  // Банковские карты для других стран кроме России
}