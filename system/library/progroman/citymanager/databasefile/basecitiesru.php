<?php
namespace progroman\CityManager\DatabaseFile;

use progroman\CityManager\DatabaseFileAction\ActionDownloadCountry;
use progroman\CityManager\DatabaseFileAction\ActionRemoveCountry;

/**
 * Class BaseCitiesRu
 * @package progroman\CityManager\DownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class BaseCitiesRu extends BaseCities {

    /** @var bool Установлена полная версия базы (с деревнями) */
    private $full_installed;

    public function __construct($lang) {
        parent::__construct($lang);
        $this->full_installed = $this->model->countCitiesRu() > 10000;
    }

    public function getStatus() {
        if ($this->isInstalled()) {
            return $this->lang($this->full_installed ? 'text_installed_full' : 'text_installed_cities');
        }

        return $this->lang('text_no');
    }

    public function getActions() {
        $params = ['country_iso' => $this->country['iso'], 'fias_id' => $this->country['country_fias_id']];

        if ($this->isInstalled()) {
            $action = new ActionRemoveCountry($this->lang);
            $action->setParams($params);
            return [$action];
        } else {
            $action = new ActionDownloadCountry($this->lang);
            $action->setName($this->lang('text_installed_full'))
                ->setParams($params);

            // Добавим lite-версию (только города)
            $action2 = new ActionDownloadCountry($this->lang);
            $action2->setCssClass('btn-success')
                ->setName($this->lang('text_installed_cities'))
                ->setParams($params + ['version' => 'lite']);

            return [$action, $action2];
        }
    }

}