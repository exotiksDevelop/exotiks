<?php

namespace YandexTaxi\Delivery\Entities\Claim;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\ExtensibleEnum;

/**
 * Class Status
 *
 * @method static Status new()
 * @method static Status estimating()
 * @method static Status estimatingFailed()
 * @method static Status readyForApproval()
 * @method static Status accepted()
 * @method static Status performerLookup()
 * @method static Status performerDraft()
 * @method static Status performerFound()
 * @method static Status performerNotFound()
 * @method static Status pickupArrived()
 * @method static Status readyForPickupConfirmation()
 * @method static Status pickuped()
 * @method static Status payWaiting()
 * @method static Status deliveryArrived()
 * @method static Status readyForDeliveryConfirmation()
 * @method static Status delivered()
 * @method static Status deliveredFinish()
 * @method static Status returning()
 * @method static Status returnArrived()
 * @method static Status readyForReturnConfirmation()
 * @method static Status returned()
 * @method static Status returnedFinish()
 * @method static Status cancelled()
 * @method static Status cancelledWithPayment()
 * @method static Status cancelledByTaxi()
 * @method static Status cancelledWithItemsOnHands()
 * @method static Status failed()
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class Status extends ExtensibleEnum
{
    private const NEW = 'new';
    private const ESTIMATING = 'estimating';
    private const ESTIMATING_FAILED = 'estimating_failed';
    private const READY_FOR_APPROVAL = 'ready_for_approval';
    private const ACCEPTED = 'accepted';
    private const PERFORMER_LOOKUP = 'performer_lookup';
    private const PERFORMER_DRAFT = 'performer_draft';
    private const PERFORMER_FOUND = 'performer_found';
    private const PERFORMER_NOT_FOUND = 'performer_not_found';
    private const PICKUP_ARRIVED = 'pickup_arrived';
    private const READY_FOR_PICKUP_CONFIRMATION = 'ready_for_pickup_confirmation';
    private const PICKUPED = 'pickuped';
    private const PAY_WAITING = 'pay_waiting';
    private const DELIVERY_ARRIVED = 'delivery_arrived';
    private const READY_FOR_DELIVERY_CONFIRMATION = 'ready_for_delivery_confirmation';
    private const DELIVERED = 'delivered';
    private const DELIVERED_FINISH = 'delivered_finish';
    private const RETURNING = 'returning';
    private const RETURN_ARRIVED = 'return_arrived';
    private const READY_FOR_RETURN_CONFIRMATION = 'ready_for_return_confirmation';
    private const RETURNED = 'returned';
    private const RETURNED_FINISH = 'returned_finish';
    private const CANCELLED = 'cancelled';
    private const CANCELLED_WITH_PAYMENT = 'cancelled_with_payment';
    private const CANCELLED_BY_TAXI = 'cancelled_by_taxi';
    private const CANCELLED_WITH_ITEMS_ON_HANDS = 'cancelled_with_items_on_hands';
    private const FAILED = 'failed';

    /**
     * @return string[]
     */
    public static function namesList(): array
    {
        return [
            self::NEW => 'Новая',
            self::ESTIMATING => 'Оценивается',
            self::ESTIMATING_FAILED => 'Невозможно оценить',
            self::READY_FOR_APPROVAL => 'Ждёт подтверждения',
            self::ACCEPTED => 'Подтверждена',
            self::PERFORMER_LOOKUP => 'Взята в обработку',
            self::PERFORMER_DRAFT => 'Поиск водителя',
            self::PERFORMER_FOUND => 'Водитель найден',
            self::PERFORMER_NOT_FOUND => 'Водитель не найден',
            self::PICKUP_ARRIVED => 'Водитель приехал в точку А',
            self::READY_FOR_PICKUP_CONFIRMATION => 'Ждёт подтверждения получения водителем из СМС',
            self::PICKUPED => 'Получено водителем',
            self::PAY_WAITING => 'Заказ ожидает оплаты',
            self::DELIVERY_ARRIVED => 'Водитель приехал в точку Б',
            self::READY_FOR_DELIVERY_CONFIRMATION => 'Ждёт подтверждения доставки из СМС',
            self::DELIVERED => 'Доставлена',
            self::DELIVERED_FINISH => 'Доставлена',
            self::RETURNING => 'Посылка возвращается на склад',
            self::RETURN_ARRIVED => 'Водитель приехал на точку возврата',
            self::READY_FOR_RETURN_CONFIRMATION => 'Ждёт подтверждения возврата из СМС',
            self::RETURNED => 'Возвращена',
            self::RETURNED_FINISH => 'Возвращена',
            self::CANCELLED => 'Отменена',
            self::CANCELLED_WITH_PAYMENT => 'Платно отменена',
            self::CANCELLED_BY_TAXI => 'Отмена со стороны сервиса',
            self::CANCELLED_WITH_ITEMS_ON_HANDS => 'Отмена (груз остался у водителя)',
            self::FAILED => 'Ошибка',
        ];
    }
}
