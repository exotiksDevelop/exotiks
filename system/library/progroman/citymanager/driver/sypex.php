<?php
namespace progroman\CityManager\Driver;

class Sypex extends Driver {
    private $sxgeo_path = '';

    protected function initGeoFilter() {
        $this->geo_filter = [];

        if ($this->sxgeo_path && file_exists($this->sxgeo_path)) {
            $sx_geo = new SxGeo($this->sxgeo_path);
            $data = $sx_geo->getCityFull($this->ip);

            if ($data) {
                $this->geo_filter = [
                    'city_name' => $data['city']['name_ru'],
                    'zone_name' => $data['region']['name_ru'],
                    'iso_code_2' => $data['country']['iso'],
                    'country_name' => $data['country']['name_ru'],
                ];

                $this->formilize();
            }
        }
    }

    private function formilize() {
        if (strpos($this->geo_filter['zone_name'], 'Крым') !== false) {
            $this->geo_filter['zone_name'] = 'Крым';
            $this->geo_filter['iso_code_2'] = 'RU';
            $this->geo_filter['country_name'] = 'Россия';
        }

        if (strpos($this->geo_filter['zone_name'], 'Севастополь') !== false) {
            $this->geo_filter['iso_code_2'] = 'RU';
            $this->geo_filter['country_name'] = 'Россия';
        }
    }

    public function setSxgeoPath($path) {
        $this->sxgeo_path = $path;
        return $this;
    }

    public function getSxgeoVersion() {
        return (new SxGeo($this->sxgeo_path))->about()['Timestamp'];
    }
}