<?php

namespace Qiwi\Admin;

/**
 * Interface Controller.
 *
 * @package Qiwi\Admin
 */
interface Controller
{
    /**
     * Index action.
     *
     * @return void
     */
    public function index();

    /**
     * Order action.
     *
     * @return void
     */
    public function order();

    /**
     * Reject action.
     *
     * @return void
     */
    public function reject();

    /**
     * Refund action.
     *
     * @return void
     */
    public function refund();

    /**
     * Install action.
     *
     * @return void
     */
    public function install();

    /**
     * Uninstall action.
     *
     * @return void
     */
    public function uninstall();
}
