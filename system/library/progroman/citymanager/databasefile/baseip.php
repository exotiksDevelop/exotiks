<?php
namespace progroman\CityManager\DatabaseFile;

use progroman\CityManager\DatabaseFileAction\ActionDownloadSxgeo;
use progroman\CityManager\CityManager;

/**
 * Class BaseIP
 * @package progroman\CityManager\DownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class BaseIP extends DatabaseFile {

    /** @var bool Есть новая версия базы IP */
    private $sxgeo_need_update;

    /** @var bool База загружена */
    private $sxgeo_downloaded;

    public function __construct($lang) {
        parent::__construct($lang);
        $this->sxgeo_downloaded = file_exists(CityManager::getSxgeoPath());
        $this->sxgeo_need_update = $this->sxgeo_downloaded && (CityManager::getSxgeoVersion() < $this->getLastVersion());
    }

    public function getName() {
        return $this->lang('text_base_ip_name');
    }

    public function getStatus() {
        if ($this->sxgeo_downloaded) {
            return $this->lang($this->sxgeo_need_update ? 'text_there_is_a_new_version' : 'text_latest_version_installed');
        }

        return $this->lang('text_not_loaded');
    }

    public function getActions() {
        if ($this->sxgeo_downloaded) {
            if ($this->sxgeo_need_update) {
                $action = new ActionDownloadSxgeo($this->lang);
                $action->setName($this->lang('button_refresh'));
                return [$action];
            }
        } else {
            return [new ActionDownloadSxgeo($this->lang)];
        }

        return [];
    }

    private function getLastVersion() {
        return '1540153994';
    }
}