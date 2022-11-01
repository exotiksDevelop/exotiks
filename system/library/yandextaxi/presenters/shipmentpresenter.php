<?php

namespace YandexTaxi\Presenters;

use YandexTaxi\Entities\Shipment\Shipment;
use YandexTaxi\Entities\Shipment\ShipmentPoint;
use \Language;
use \Url;

/**
 * Class ShipmentPresenter
 *
 * @package YandexTaxi\Presenters
 */
class ShipmentPresenter
{
    /** @var Language */
    private $languages;

    /** @var Url */
    private $url;

    /** @var string */
    private $token;

    /**
     * ShipmentPresenter constructor.
     *
     * @param Language $languages
     * @param Url      $url
     * @param string   $token
     */
    public function __construct(Language $languages, Url $url, string $token) {
        $this->languages = $languages;
        $this->url = $url;
        $this->token = $token;
    }

    public function present(?Shipment $shipment, int $orderId): array {
        if (is_null($shipment)) {
            return [];
        }

        $result = [
            'claim_id' => $shipment->getClaimId(),
            'tariff' => $shipment->getTariff(),
            'status' => $this->languages->get('status_label_' . $shipment->getStatus()->getCode()),
            'price' => $this->getPriceLine($shipment->getPrice(), array_map(function (ShipmentPoint $point) {
                return $point->getOrderId();
            }, $shipment->getPoints())),
        ];

        $shipmentPoint = $shipment->getPointForOrder($orderId);

        if (!is_null($shipmentPoint->getVisitStatus())) {
            $result['point_visit_status'] = $this->languages->get(
                'route_point_status_label_' . $shipmentPoint->getVisitStatus()->getCode()
            );
        }
        if (!is_null($shipment->getDriver())) {
            $result['driver_name'] = $shipment->getDriver()->getName();
            $result['car'] = "{$shipment->getDriver()->getCarModel()} {$shipment->getDriver()->getCarNumber()}";
        }

        if (!is_null($shipment->getDriverPhone())) {
            $result['driver_phone'] = $shipment->getDriverPhone()->getPhone() . " ({$shipment->getDriverPhone()->getExt()})";
        }

        return $result;
    }

    private function getPriceLine(?int $rawPrice, array $orderIds): string {
        if (is_null($rawPrice)) {
            return '';
        }

        $price = number_format($rawPrice / 100, 2) . ' ' . $this->languages->get('rubles');

        $orderIds = array_filter($orderIds);

        if (count($orderIds) === 1) {
            return $price;
        }

        $ordersPart = implode(', ', array_map(function (int $id) {
            return $this->getOrderLink($id);
        }, $orderIds));

        return sprintf(
            $this->languages->get('price_multi_order'),
            $price,
            $ordersPart
        );
    }

    private function getOrderLink(int $id): string {
        $url = $this->url->link('sale/order/info&order_id=' . $id,"user_token={$this->token}", true);

        return "<a href='{$url}' target='_blank'>№{$id}</a>";
    }
}
