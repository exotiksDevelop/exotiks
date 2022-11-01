<?php
namespace progroman\CityManager\DatabaseFileAction;

use progroman\CityManager\CityManager;

/**
 * Class ActionDownloadCountry
 * Загрузка и установка базы городов одной страны
 * @package progroman\CityManager\ActionDownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ActionDownloadCountry extends DatabaseFileAction {

    protected $icon = 'download';

    protected $css_class = 'btn-primary';

    /** @var array Текущая страна */
    private $country = [];

    public function __construct(array $lang = []) {
        parent::__construct($lang);
        $this->name = $this->lang('text_load');
        $this->loading_text = $this->lang('text_loading');
    }

    public function step($step, $params = []) {
        $this->country['iso'] = $params['country_iso'];
        $this->country['version'] = isset($params['version']) ? $params['version'] : '';

        switch ($step) {
            case 'upload':
                return $this->stepUpload();

            case 'unzip':
                return $this->stepUnzip();

            case 'query':
                return $this->stepQuery(isset($params['iteration']) ? $params['iteration'] : 0);

            case 'clear':
                return $this->stepClear();

            default:
                return ['error' => sprintf($this->lang('error_bug'), 'Unknown step')];
        }
    }

    private function stepUpload() {
        $upload_dir = CityManager::getUploadDir() . '/' . $this->country['iso'];
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                return ['error' => sprintf($this->lang('error_create_dir'), $upload_dir)];
            }
        }

        try {
            $this->downloadFile('fias', $upload_dir . '/' . $this->country['iso'] . '.zip');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $json['success'] = 1;
        $json['next_step'] = 'unzip';
        $json['btn_text'] = $this->lang('text_unzip');

        return $json;
    }

    private function stepUnzip() {
        $upload_dir = CityManager::getUploadDir() . '/' . $this->country['iso'];
        $zip_file = $upload_dir . '/' . $this->country['iso'] . '.zip';

        try {
            $this->unzipFile($zip_file, $upload_dir);
            unlink($zip_file);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $json['success'] = 1;
        $json['next_step'] = 'query';
        $json['btn_text'] = $this->lang('text_query');

        return $json;
    }

    private function stepQuery($iteration) {
        $files = glob(CityManager::getUploadDir() . '/' . $this->country['iso'] . '/*.sql');

        try {
            $this->queryFromFile($files[$iteration]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        if (isset($files[$iteration + 1])) {
            $json['next_step'] = 'query';
            $json['iteration'] = $iteration + 1;
        } else {
            $json['next_step'] = 'clear';
            $json['btn_text'] = $this->lang('text_clear');
        }

        $json['success'] = 1;

        return $json;
    }

    private function stepClear() {
        $this->rmdir(CityManager::getUploadDir() . '/' . $this->country['iso']);

        $json['success'] = 1;
        $json['text'] = $this->lang('text_database_uploaded');

        return $json;
    }

    protected function getParamsForProgromanServer() {
        return ['country' => $this->country['iso'], 'db_version' => $this->country['version']];
    }

}