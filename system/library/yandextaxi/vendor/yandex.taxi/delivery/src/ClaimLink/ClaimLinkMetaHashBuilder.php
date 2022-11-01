<?php

namespace YandexTaxi\Delivery\ClaimLink;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;

/**
 * Class ClaimLinkMetaHashBuilder
 *
 * @package YandexTaxi\Delivery\ClaimLink
 */
class ClaimLinkMetaHashBuilder
{
    /**
     * @param ClaimItem[]  $items
     * @param RoutePoint   $source
     * @param RoutePoint[] $destinations
     * @param string|null $tariff
     * @param array $clientRequirements
     *
     * @return string
     */
    public function generate(
        array $items,
        RoutePoint $source,
        array $destinations,
        ?string $tariff,
        array $clientRequirements
    ): string {
        $string = $this->getRoutePointString($source);

        foreach ($destinations as $destination) {
            $string .= $this->getRoutePointString($destination);
        }

        foreach ($items as $item) {
            $string .= $item->getId() . $item->getQuantity();
        }

        $string .= $tariff;
        $string .= json_encode($clientRequirements);

        return hash('sha256', $string);
    }

    private function getRoutePointString(RoutePoint $point): string
    {
        return implode($point->getAddress()->toArray()) . $point->getContact()->getName() . $point->getContact()->getPhone()
            . $point->getContact()->getEmail() . $point->sendConfirmationSms() . $point->getOrderId();
    }
}
