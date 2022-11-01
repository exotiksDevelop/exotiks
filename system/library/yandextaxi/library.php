<?php

namespace YandexTaxi;

/**
 * Class Library
 *
 * @package YandexTaxi
 */
class Library {
    /** @var \Registry */
    protected $registry;

    /**
     * @param \Registry $registry
     */
    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }
}
