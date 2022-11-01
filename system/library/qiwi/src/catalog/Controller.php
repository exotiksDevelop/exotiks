<?php

namespace Qiwi\Catalog;

/**
 * Interface Controller.
 *
 * @package Qiwi\Catalog
 */
interface Controller
{
    /**
     * Index action.
     *
     * @return string
     */
    public function index();

    /**
     * Confirm ajax-action.
     *
     * @return void
     */
    public function confirm();

    /**
     * Notification callbacks ajax-action.
     *
     * @return void
     */
    public function notification();
}
