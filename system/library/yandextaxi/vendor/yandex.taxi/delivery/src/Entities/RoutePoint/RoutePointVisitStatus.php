<?php

namespace YandexTaxi\Delivery\Entities\RoutePoint;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\ExtensibleEnum;

/**
 * Class RoutePointVisitStatus
 *
 * @method static RoutePointVisitStatus pending()
 * @method static RoutePointVisitStatus arrived()
 * @method static RoutePointVisitStatus visited()
 * @method static RoutePointVisitStatus skipped()
 *
 * @package YandexTaxi\Delivery\Entities\RoutePoint
 */
class RoutePointVisitStatus extends ExtensibleEnum
{
    private const PENDING = 'pending';
    private const ARRIVED = 'arrived';
    private const VISITED = 'visited';
    private const SKIPPED = 'skipped';

    public static function namesList(): array
    {
        return [
            self::PENDING => 'Ждёт исполнения',
            self::ARRIVED => 'Исполнитель на месте',
            self::VISITED => 'Выполнено',
            self::SKIPPED => 'Пропущено',
        ];
    }
}
