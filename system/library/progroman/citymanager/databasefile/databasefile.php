<?php
namespace progroman\CityManager\DatabaseFile;

/**
 * Class DownloadFile
 * @package progroman\CityManager\DownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
abstract class DatabaseFile {

    protected $name;

    protected $lang = [];

    public function __construct($lang = []) {
        if ($lang) {
            $this->lang = $lang;
        }
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return DatabaseFile|BaseCities
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getStatus() {
        return '';
    }

    public function getActions() {
        return [];
    }

    protected function lang($key) {
        return isset($this->lang[$key]) ? $this->lang[$key] : $key;
    }
}