<?php

namespace YandexTaxi\Services;

use YandexTaxi\Entities\Shipment\Shipment;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;

/**
 * Class OrderStatusService
 *
 * @package YandexTaxi\Services
 */
class OrderStatusService
{
    private const SHIPPED_STATUS_NAME = 'shipped';

    /** @var \Db */
    private $db;

    /** @var \ModelLocalisationOrderStatus */
    private $orderStatus;

    /** @var bool */
    private $autoChangeOn;

    /** @var string */
    private $commentText;

    public function __construct($db, $orderStatus, bool $autoChangeOn, string $text) {
        $this->db = $db;
        $this->orderStatus = $orderStatus;
        $this->autoChangeOn = $autoChangeOn;
        $this->commentText = $text;
    }

    public function changeIfNeeded(int $orderId, ?Shipment $old, Shipment $new): void {
        if (!$this->needStatusUpdate($orderId, $old, $new)) {
            return;
        }

        $statusId = $this->getShippedStatusId();

        if (is_null($statusId)) {
            return;
        }

        $this->db->query(
            "UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $statusId .
            "', date_modified = NOW() WHERE order_id = '" . $orderId . "'"
        );

        $this->db->query(
            "INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $orderId .
            "', order_status_id = '" . $statusId . "', notify = '0', comment = '" . $this->db->escape($this->commentText) .
            "', date_added = NOW()"
        );

    }

    private function needStatusUpdate(int $orderId, ?Shipment $old, Shipment $new): bool {
        if (!$this->autoChangeOn) {
            return false;
        }

        $oldStatus = $old->getPointForOrder($orderId)->getVisitStatus();

        if (!is_null($oldStatus)) {
            if ($oldStatus->equals(RoutePointVisitStatus::visited())) {
                return false;
            }
        }

        $newStatus = $new->getPointForOrder($orderId)->getVisitStatus();

        if (is_null($new)) {
            return false;
        }

        return $newStatus->equals(RoutePointVisitStatus::visited());
    }

    private function getShippedStatusId(): ?int {
        $statuses = $this->orderStatus->getOrderStatuses();

        foreach ($statuses as $status) {
            if (strtolower($status['name']) === self::SHIPPED_STATUS_NAME) {
                return (int)$status['order_status_id'];
            }
        }

        return null;
    }
}
