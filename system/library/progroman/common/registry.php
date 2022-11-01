<?php
namespace progroman\Common;

class Registry {

    private static $instance;

    /** @var \Registry */
    private $registry;

    /**
     * @return self
     */
    public static function instance() {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param \Registry $registry
     * @return $this
     */
    public function setRegistry($registry) {
        $this->registry = $registry;
        return $this;
    }

    public function getRegistry() {
        return $this->registry;
    }

    public function get($key) {
        return $this->registry->get($key);
    }

    public function set($key, $value) {
        $this->registry->set($key, $value);
    }

    public function has($key) {
        return $this->registry->has($key);
    }

}