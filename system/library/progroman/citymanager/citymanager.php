<?php
namespace progroman\CityManager;

use progroman\CityManager\Driver\Sypex;

if (defined('PROGROMAN_DEV_MODE')) {
    require_once 'core.php';
} elseif (version_compare(phpversion(), '7.1', '<')) {
    require_once 'core-encoded-php56.php';
} elseif (version_compare(phpversion(), '7.2', '<')) {
    require_once 'core-encoded-php71.php';
} else {
    require_once 'core-encoded-php72.php';
}

define('PROGROMAN_CITYMANAGER_DIR', __DIR__);

/**
 * Class CityManager
 * @package progroman\CityManager
 * @author Roman Shipilov (ProgRoman) <mr.progroman@yandex.ru>
 */
class CityManager extends Core {

    const VERSION = '8.3';
    const MODULE_NAME = 'CityManager';

    protected static $instance;

    protected $dev_mode;

    public function __construct() {
        parent::__construct();

        if ($this->dev_mode) {
            $this->log('HTTP_HOST ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            $this->log('SESSION ' . print_r(isset($this->session->data[$this->getSessionKey()]) ? $this->session->data[$this->getSessionKey()] : null, true));
            $this->log('COOKIE fias "' . (!empty($cookie[$this->getCookieKey('fias')]) ? $cookie[$this->getCookieKey('fias')] : '') . '"');
            $this->log('is_first_visit ' . ($this->is_first_visit ? 'true' : 'false'));
        }
    }

    public function getCountryId() {
        return $this->country_id;
    }

    public function getCountryName($case = self::NOMINATIVE) {
        return $this->country_name;
    }

    public function getZoneId() {
        return $this->zone_id;
    }

    public function getZoneName($case = self::NOMINATIVE, $with_prefix = false) {
        return $with_prefix ? $this->concatPrefix($this->zone_name, $this->prefix_zone_name) : $this->zone_name;
    }

    public function getDistrictName($case = self::NOMINATIVE, $with_prefix = false) {
        return $with_prefix ? $this->concatPrefix($this->district_name, $this->prefix_district_name) : $this->district_name;
    }

    public function getFullCityName($case = self::NOMINATIVE, $with_prefix = false) {
        $district = $this->getDistrictName($case, true);
        return ($district ? $district .  ', ' : '') . $this->getCityName($case, $with_prefix);
    }

    public function getCityName($case = self::NOMINATIVE, $with_prefix = false) {
        return $with_prefix ? $this->concatPrefix($this->city_name, $this->prefix_city_name) : $this->city_name;
    }

    public function getPostcode() {
        return $this->postcode;
    }

    /** private */
    public function concatPrefix($name, $prefix) {
        if (empty($prefix)) {
            return $name;
        }

        $after = ['обл.', 'аобл.', 'ао', 'край', 'р-н'];
        return in_array(mb_strtolower($prefix), $after) ? $name . ' ' . $prefix : $prefix . ' ' . $name;
    }

    public function getPrefixZoneName() {
        return $this->prefix_zone_name;
    }

    public function getPrefixCityName() {
        return $this->prefix_city_name;
    }

    public function getFiasCountryId() {
        return $this->fias_country_id;
    }

    public function getFiasZoneId() {
        return $this->fias_zone_id;
    }

    public function getFiasId() {
        return $this->fias_id;
    }

    /**
     * Если для текущего города определено название в попапе городов,
     * возвращает его название (как оно записано в таблице)
     * @return string
     */
    public function getPopupCityName() {
        if (is_null($this->popup_city_name)) {
            $cities = $this->loadModel('citymanager')->getCities();
            $fias_id = $this->fias_id ? $this->fias_id : ($this->fias_zone_id ? $this->fias_zone_id : $this->fias_country_id);

            foreach ($cities as $city) {
                if ($city['fias_id'] == $fias_id) {
                    return $city['name'];
                }
            }
        }

        return $this->popup_city_name;
    }

    public function getFullInfo() {
        return $this->session->data[$this->getSessionKey()];
    }

    public function setFias($fias_id) {
        $result = parent::setFias($fias_id);
        if ($result) {
            $this->forceSaveInSession();
        }

        return $result;
    }

    /**
     * Записывает адреса доставки и оплаты в сессию,
     * только если эти значения не были установлены ранее.
     * Не перезаписывает уже установленных значений.
     */
    public function saveInSession() {
        if ($this->setting('not_fill_fields')) {
            return;
        }

        foreach ($this->getData() as $key => $value) {
            // OC 1.5
            if (empty($this->session->data['shipping_' . $key])) {
                $this->session->data['shipping_' . $key] = $value;
            }

            if (empty($this->session->data['payment_' . $key])) {
                $this->session->data['payment_' . $key] = $value;
            }

            if (empty($this->session->data['guest']['shipping'][$key])) {
                $this->session->data['guest']['shipping'][$key] = $value;
            }

            if (empty($this->session->data['guest']['payment'][$key])) {
                $this->session->data['guest']['payment'][$key] = $value;
            }

            // OC 2
            if (empty($this->session->data['payment_address'][$key])) {
                $this->session->data['payment_address'][$key] = $value;
            }

            if (empty($this->session->data['shipping_address'][$key])) {
                $this->session->data['shipping_address'][$key] = $value;
            }

            // Simple
            if (empty($this->session->data['simple']['payment_address'][$key])) {
                $this->session->data['simple']['payment_address'][$key] = $value;
            }

            if (empty($this->session->data['simple']['shipping_address'][$key])) {
                $this->session->data['simple']['shipping_address'][$key] = $value;
            }
        }
    }

    /**
     * Записывает адреса доставки и оплаты в сессию.
     * Используется, когда пользователь меняет регион вручную.
     */
    public function forceSaveInSession() {
        if ($this->setting('not_fill_fields')) {
            return;
        }

        foreach ($this->getData() as $key => $value) {
            $this->session->data['payment_address'][$key]
                = $this->session->data['shipping_address'][$key]
                = $this->session->data['shipping_' . $key]
                = $this->session->data['payment_' . $key]
                = $this->session->data['guest']['shipping'][$key]
                = $this->session->data['guest']['payment'][$key]
                = $this->session->data['simple']['payment_address'][$key]
                = $this->session->data['simple']['shipping_address'][$key]
                = $value;
        }
    }

    private function getData() {
        return [
            'country_id' => $this->getCountryId(),
            'zone_id' => $this->getZoneId(),
            'postcode' => $this->getPostcode(),
            'city' => $this->setting('use_fullname_city') ? $this->getFullCityName() : $this->getCityName()
        ];
    }

    public function getRedirectUrlForManual($request_uri) {
        // Редирект уже был или авторедирект включен
        if ($this->getValueFromSession('redirected') || !$this->setting('disable_autoredirect')) {
            return false;
        }

        return $this->getRedirectUrl($request_uri);
    }

    protected function getBots() {
        return [
            'apis-google', 'mediapartners-google', 'adsbot', 'googlebot', 'yandex.com/bots', 'mail.ru_bot', 'stackrambler',
            'slurp', 'msnbot', 'bingbot', 'alexa.com'
        ];
    }

    /**
     * Возвращает папку для загрузки файлов
     * @return string
     */
    static public function getUploadDir() {
        return (defined('DIR_UPLOAD') ? DIR_UPLOAD : DIR_DOWNLOAD) . 'progroman';
    }

    static public function getSxgeoPath() {
        return self::getUploadDir() . '/SxGeoCity.dat';
    }

    static public function getSxgeoVersion() {
        return (new Sypex())->setSxgeoPath(self::getSxgeoPath())->getSxgeoVersion();
    }

    static public function getCitiesBaseList() {
        return [
            ['name' => 'Белоруссия', 'country_fias_id' => 300000, 'iso' => 'by'],
            ['name' => 'Казахстан', 'country_fias_id' => 500000, 'iso' => 'kz'],
            ['name' => 'РФ', 'class' => 'progroman\CityManager\DatabaseFile\BaseCitiesRu', 'country_fias_id' => 1, 'iso' => 'ru'],
            ['name' => 'Украина', 'class' => 'progroman\CityManager\DatabaseFile\BaseCitiesUa', 'country_fias_id' => 400000, 'iso' => 'ua'],
        ];
    }

    public function replaceBlanks($string) {
        $replaces = [
            '%COUNTRY%' => $this->getCountryName(),
            '%ZONE%' => $this->getZoneName(self::NOMINATIVE, true),
            '%PREFIX_ZONE%' => $this->getPrefixZoneName(),
            '%CITY%' => $this->getCityName(),
            '%PREFIX_CITY%' => $this->getPrefixCityName(),
        ];

        $string = str_replace(array_keys($replaces), $replaces, $string);
        $string = preg_replace_callback('#%MSG_(.*?)%#', function($matches) {
            return $this->getMessage($matches[1]);
        }, $string);

        return $string;
    }

    public function autocompleteForSimple($term, $limit) {
        return $this->loadModel('fias')->autocompleteForSimple($term, $limit);
    }

    /**
     * Проверка - нужно ли делать редирект
     * @return bool
     */
    protected function needRedirect() {
        $request = $this->registry->get('request');
        $urls = ['route=api/', 'payment/'];

        foreach ($urls as $url) {
            if (strpos($request->server['REQUEST_URI'], $url) !== false) {
                return false;
            }
        }

        return parent::needRedirect();
    }
}