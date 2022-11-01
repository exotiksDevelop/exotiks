<?php

namespace Qiwi\Catalog;

/**
 * Interface CatalogModel.
 *
 * @package Qiwi
 */
interface Model
{
    /**
     * Get payment method.
     *
     * @param array $address The address.
     * @param double $total The total.
     *
     * @return array
     */
    public function getMethod($address, $total);

    /**
     * Add bill link.
     *
     * @param string $bill The bill ID.
     * @param int $order The order ID.
     *
     * @return void
     */
    public function addBill($bill, $order);

    /**
     * Get order ID by bill ID.
     *
     * @param string $bill The bill ID.
     *
     * @return int
     */
    public function getOrder($bill);

    /**
     * Get bills IDs by order ID.
     *
     * @param int $order The order ID.
     *
     * @return string[]
     */
    public function getBills($order);
}
