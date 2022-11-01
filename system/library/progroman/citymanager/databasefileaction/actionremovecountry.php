<?php
namespace progroman\CityManager\DatabaseFileAction;

use progroman\Common\Registry;

/**
 * Class ActionRemoveCountry
 * Удаление из базы городов одной страны
 * @package progroman\CityManager\ActionDownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ActionRemoveCountry extends DatabaseFileAction {

    protected $icon = 'remove';

    protected $css_class = 'btn-danger';

    public function __construct(array $lang = []) {
        parent::__construct($lang);
        $this->name = $this->lang('button_delete');
        $this->loading_text = $this->lang('text_delete');
    }

    public function step($step, $params = []) {
        try {
            $model = new \ModelExtensionModuleProgromanCityManager(Registry::instance()->getRegistry());
            $end = $params['fias_id'] == 1 ? 299999 : ($params['fias_id'] + 99999);
            $model->removeSliceFias($params['fias_id'], $end);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $json['success'] = 1;
        $json['text'] = $this->lang('text_end');

        return $json;
    }
}