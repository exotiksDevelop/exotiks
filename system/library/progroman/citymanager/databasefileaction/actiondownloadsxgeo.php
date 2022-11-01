<?php
namespace progroman\CityManager\DatabaseFileAction;

use progroman\CityManager\CityManager;

/**
 * Class ActionDownloadSypex
 * @package progroman\CityManager\ActionDownloadFile
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class ActionDownloadSxgeo extends DatabaseFileAction {

    protected $icon = 'download';
    protected $css_class = 'btn-primary';

    public function __construct($lang) {
        parent::__construct($lang);
        $this->name = $this->lang('text_load');
        $this->loading_text = $this->lang('text_loading');

        $this->lang['error_create_dir'] = $this->lang('error_create_dir');
        $this->lang['error_create_file'] = $this->lang('error_create_file');
        $this->lang['error_unzip'] = $this->lang('error_unzip');
    }

    public function step($step, $params = []) {
        switch ($step) {
            case 'upload':
                return $this->stepUpload();

            case 'unzip':
                return $this->stepUnzip();

            default:
                return ['error' => sprintf($this->lang('error_bug'), 'Unknown step')];
        }
    }

    private function stepUpload() {
        $sxgeo_path = CityManager::getSxgeoPath();
        $sxgeo_dir = dirname($sxgeo_path);
        if (!is_dir($sxgeo_dir)) {
            if (!@mkdir($sxgeo_dir)) {
                return ['error' => sprintf($this->lang('error_create_dir'), $sxgeo_dir)];
            }
        }

        try {
            $this->downloadFile('sypex', $sxgeo_dir . '/sxgeocity.zip');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $json['success'] = 1;
        $json['next_step'] = 'unzip';
        $json['btn_text'] = $this->lang('text_unzip');

        return $json;
    }

    private function stepUnzip() {
        $sxgeo_path = CityManager::getSxgeoPath();
        $sxgeo_dir = dirname($sxgeo_path);
        $zip_file = $sxgeo_dir . '/sxgeocity.zip';

        try {
            $this->unzipFile($zip_file, $sxgeo_dir);
            unlink($zip_file);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        if (!file_exists($sxgeo_path)) {
            return ['error' => $this->lang('error_unzip')];
        }

        $json['success'] = 1;
        $json['text'] = $this->lang('text_database_uploaded');

        return $json;
    }

}