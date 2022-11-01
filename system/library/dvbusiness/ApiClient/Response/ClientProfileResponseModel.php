<?php

namespace DvBusiness\ApiClient\Response;

class ClientProfileResponseModel
{
    /** @var int */
    private $clientId;

    /** @var string */
    private $name;

    /** @var string */
    private $phone;

    /** @var string individual_person (физ.лицо), individual_entrepreneur (ИП), company (юр.лицо) */
    private $legalType;

    /** @var string|null */
    private $email;

    /** @var string */
    private $userName;

    /** @var string */
    private $userSurname;

    /** @var string */
    private $userMiddlename;

    /** @var string|null */
    private $noteComments;

    /** @var string|null */
    private $defaultMatter;

    /** @var string */
    private $backpaymentDetails;

    /** @var string none (нет), virtual (Электронный перевод), bank (Банковская карта), buyout (Выкуп), courier (Курьером)*/
    private $backpaymentMethod;

    /** @var bool Уведомлять клиента по СМС о заказах */
    private $smsNotification;

    /** @var bool Предупреждать контактные лица на адресах о прибытии курьера */
    private $recipientsSmsNotification;

    /** @var bool Может ли клиент пользоваться РКО, в частности, создавать packages (С клиентом должен быть заключен договор на кассовое обслуживание). */
    private $isCashVoucherIssueAllowed;

    /** @var bool */
    private $isRequisitesFilled;

    /** @var bool $isAgreementApproved Одобрен ли договор присоединения для юр.лиц (без этого договора юр.лицо не может создавать и калькулировать заказы/доставки) */
    private $isAgreementApproved;

    /** @var bool */
    private $isAgreementSigned;

    /** @var bool */
    private $isNewAgreementNeedsSign;

    /**
     * Доступные клиенту методы оплаты, возможные варианты:
     * cash (Наличные), qiwi_split (Банковские карты в России), non_cash (Оплата с баланса Достависты, для юр.лиц), bank_card (Банковские карты в других странах)
     * @var string[]
     */
    private $paymentMethods;

    /** @var  */
    private $clientProfileData;

    public function __construct(array $responseData)
    {
        $this->clientProfileData = $responseData;

        $this->clientId                  = $responseData['client_id'];
        $this->name                      = $responseData['name'];
        $this->phone                     = $responseData['phone'];
        $this->legalType                 = $responseData['legal_type'];
        $this->email                     = $responseData['email'];
        $this->userName                  = $responseData['user_name'];
        $this->userSurname               = $responseData['user_surname'];
        $this->userMiddlename            = $responseData['user_middlename'];
        $this->noteComments              = $responseData['note_comments'];
        $this->defaultMatter             = $responseData['default_matter'];
        $this->backpaymentDetails        = $responseData['backpayment_details'];
        $this->backpaymentMethod         = $responseData['backpayment_method'];
        $this->smsNotification           = $responseData['sms_notification'];
        $this->recipientsSmsNotification = $responseData['recipients_sms_notification'];
        $this->isCashVoucherIssueAllowed = $responseData['is_cash_voucher_issue_allowed'];
        $this->isRequisitesFilled        = $responseData['is_requisites_filled'];
        $this->isAgreementApproved       = $responseData['is_agreement_approved'];
        $this->isAgreementSigned         = $responseData['is_agreement_signed'];
        $this->isNewAgreementNeedsSign   = $responseData['is_new_agreement_needs_sign'];
        $this->paymentMethods            = [];

        foreach ($responseData['payment_methods'] as $paymentMethod) {
            $this->paymentMethods[] = $paymentMethod;
        }
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getLegalType(): string
    {
        return $this->legalType;
    }

    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }
}
