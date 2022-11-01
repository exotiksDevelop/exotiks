<?php
namespace progroman\CityManager\DatabaseFile;

use progroman\CityManager\DatabaseFileAction\ActionDownloadCountry;
use progroman\CityManager\DatabaseFileAction\ActionRemoveCountry;
use progroman\Common\Registry;

/**
 * Class BaseCities
 * @package progroman\CityManager\DownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class BaseCities extends DatabaseFile {

    static protected $installed_countries = [];

    /** @var array */
    protected $country;

    /** @var \ModelExtensionModuleProgromanCityManager */
    protected $model;

    public function __construct(array $lang = []) {
        parent::__construct($lang);
        $this->model = new \ModelExtensionModuleProgromanCityManager(Registry::instance()->getRegistry());
    }

    public function getStatus() {
        return $this->lang($this->isInstalled() ? 'text_yes' : 'text_no');
    }

    public function getActions() {
        $action = $this->isInstalled() ? new ActionRemoveCountry($this->lang) : new ActionDownloadCountry($this->lang);
        $action->setParams(['country_iso' => $this->country['iso'], 'fias_id' => $this->country['country_fias_id']]);
        return [$action];
    }

    /**
     * @param int $country
     * @return BaseCities
     */
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    /**
     * Возвращает список установленных стран
     * @return array
     */
    public function getInstalledCountries() {
        if (!self::$installed_countries) {
            self::$installed_countries = $this->model->getInstalledCountries();
        }

        return self::$installed_countries;
    }

    protected function isInstalled() {
        return in_array($this->country['country_fias_id'], $this->getInstalledCountries());
    }
}