<?php

namespace Qiwi\Admin;

/**
 * Interface AdminModel.
 *
 * @package Qiwi
 */
interface Model
{
    /**
     * Install DB.
     *
     * @return void
     */
    public function install();

    /**
     * Uninstall DB.
     *
     * @return void
     */
    public function uninstall();

    /**
     * Get bill refunds.
     *
     * @param string $bill The bill ID.
     *
     * @return string[]
     */
    public function getRefunds($bill);

    /**
     * Get bills IDs.
     *
     * @param int $order The order ID.
     *
     * @return string[]
     */
    public function getBills($order);

    /**
     * Get order ID.
     *
     * @param string $bill The bill ID.
     *
     * @return int|null
     */
    public function getOrder($bill);

    /**
     * Add refund to order.
     *
     * @param string $bill The bill ID.
     * @param string $refund The refund ID.
     * @param int $order The order ID.
     *
     * @return void
     */
    public function addRefund($bill, $refund, $order);
}
