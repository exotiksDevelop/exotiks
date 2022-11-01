<?php
    require_once 'SxGeo.php';

    class GeoIP_Driver_SypexGeo extends GeoIP_Driver {

        protected function initGeoFilter() {

            $sxGeo = new SxGeo(DIR_SYSTEM . 'library/geoip/driver/SxGeoCity.dat');
            $data = $sxGeo->getCityFull($this->ip);

            if ($data) {

                $this->geo_filter = array(
                        'city_name' => $data['city']['name_ru'],
                        'zone_name' => $data['region']['name_ru'],
                        'iso_code_2' => $data['country']['iso'],
                        'country_name' => $this->getCountryNameByIso($data['country'])
                );
            }
        }

        private function getCountryNameByIso($country) {

            if ($country['iso'] == 'BY') {
                return 'Белоруссия';
            }

            return $country['name_ru'];
        }
    }