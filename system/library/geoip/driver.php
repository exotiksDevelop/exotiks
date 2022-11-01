<?php

    abstract class GeoIP_Driver {

        protected $ip;

        /**
         * @var Registry
         */
        protected $registry;

        /**
         * @var array Геофильтр, с данными, получаемыми по IP
         */
        protected $geo_filter = array();

        public function __construct($registry, $ip = false) {

            $this->registry = $registry;

            if ($this->isValidIp($ip)) {
                $this->ip = $ip;
            }
            else {
                $http_ip = $this->getHttpIp();

                if ($http_ip) {
                    $this->ip = $http_ip;
                }
            }

            $this->initGeoFilter();
        }

        public function getGeoFilter() {

            return $this->geo_filter;
        }

        public function getIp() {

            return $this->ip;
        }

        /**
         * Инициализация геофильтра
         * @return mixed
         */
        abstract protected function initGeoFilter();

        /**
         * Определяет ip адрес по HTTP-заголовкам из массива $_SERVER
         * ip адреса проверяются начиная с приоритетного, для определения возможного использования прокси
         * @return string IP-адрес | bool false
         */
        private function getHttpIp() {

            $httpKeys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR', 'HTTP_X_REAL_IP');

            // проверяем ip-адреса на валидность начиная с приоритетного.
            foreach ($httpKeys as $httpKey) {

                if (isset($_SERVER[$httpKey])) {

                    $ip = trim(strtok($_SERVER[$httpKey], ','));

                    if ($this->isValidIp($ip)) {
                        return $ip;
                    }
                }
            }

            return false;
        }

        /**
         * Валидация ip адреса
         * @param ip адрес в формате 1.2.3.4
         * @return boolean
         */
        private function isValidIp($ip = null) {

            return $ip && preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#", $ip);
        }

    }