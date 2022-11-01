<?php

namespace DvBusiness\ModuleMetric;

use DB;
use DvBusiness\ApiClient\DvCmsModuleApiClient;
use DvBusiness\ApiClient\DvCmsModuleApiHttpException;
use DvBusiness\ApiClient\Request\AddEventRequestModel;

class ModuleMetricManager
{
    /** @var Db */
    private $db;

    /** @var string */
    private $cmsApiUrl;

    /** @var string */
    private $businessApiUrl;

    /** @var string|null */
    private $authToken;

    /**
     * @param Db $db (Proxy-объект)
     * @param string $cmsApiUrl
     * @param string $businessApiUrl
     * @param string|null $authToken
     */
    public function __construct($db, string $cmsApiUrl, string $businessApiUrl, string $authToken = null)
    {
        $this->db             = $db;
        $this->cmsApiUrl      = $cmsApiUrl;
        $this->businessApiUrl = $businessApiUrl;
        $this->authToken      = $authToken;

        $this->createTablesIfNotExists();
    }

    public function createTablesIfNotExists()
    {
        $this->db->query(
            "
                CREATE TABLE IF NOT EXISTS `dvbusiness_module_data` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `key` VARCHAR(255) DEFAULT '',
                    `value` VARCHAR(1024) DEFAULT '',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );
    }

    public function setData(string $key, string $value)
    {
        if ($this->issetKey($key)) {
            $this->db->query("UPDATE `dvbusiness_module_data` SET `value` = '" . $this->db->escape($value) . "' WHERE `key` = '" . $this->db->escape($key) . "'");
        } else {
            $this->db->query("INSERT INTO `dvbusiness_module_data` (`key`, `value`) VALUES ('" . $this->db->escape($key) . "', '" . $this->db->escape($value) . "')");
        }
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getData(string $key)
    {
        $queryResult = $this->db->query("SELECT `value` FROM `dvbusiness_module_data` WHERE `key` = '" . $this->db->escape($key) . "'");
        if (count($queryResult->rows)) {
            return $queryResult->rows[0]['value'];
        } else {
            return null;
        }
    }

    private function issetKey(string $key): bool
    {
        $queryResult = $this->db->query("SELECT `value` FROM `dvbusiness_module_data` WHERE `key` = '" . $this->db->escape($key) . "'");
        return (bool) count($queryResult->rows);
    }

    private function getApiClient(bool $withToken = true)
    {
        return new DvCmsModuleApiClient(
            $this->cmsApiUrl,
            $withToken ? $this->authToken : null
        );
    }

    public function install(string $datetime = null)
    {
        $this->setData('install_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function uninstall(string $datetime = null)
    {
        $this->setData('uninstall_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function tokenCreate(string $datetime = null)
    {
        $this->setData('token_create_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function tokenInstall(string $datetime = null)
    {
        $this->setData('token_install_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function deliveryInstall(string $datetime = null)
    {
        $this->setData('delivery_install_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function deliveryUninstall(string $datetime = null)
    {
        $this->setData('delivery_uninstall_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function callbackKeyInstall(string $datetime = null)
    {
        $this->setData('callback_key_install_datetime', date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    private function sendModuleMetrics()
    {
        $domain = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') {
            $url = 'https://' . $domain;
        } else {
            $url = 'http://' . $domain;
        }

        $notAuthMetricMap = [
            AddEventRequestModel::EVENT_TYPE_INSTALL      => ['install_datetime', 'install_notified_datetime'],
            AddEventRequestModel::EVENT_TYPE_UNINSTALL    => ['uninstall_datetime', 'uninstall_notified_datetime'],
        ];
        $notAuthApiClient = static::getApiClient();
        foreach ($notAuthMetricMap as $eventType => $fields) {
            $eventDatetimeField         = $fields[0];
            $eventNotifiedDatetimeField = $fields[1];

            if ($this->getData($eventDatetimeField) > $this->getData($eventNotifiedDatetimeField)) {
                try {
                    $notAuthApiClient->addEvent(
                        new AddEventRequestModel(
                            $eventType, $url, $this->getData($eventDatetimeField)
                        )
                    );
                    $this->setData($eventNotifiedDatetimeField, date('c'));
                } catch (DvCmsModuleApiHttpException $e) {
                    // Пока проблемы с API просто гасим. Метрики дойдут при следующих попытках
                }
            }
        }

        $isTestApiToken = strpos($this->businessApiUrl, 'robotapitest.') !== false;
        if (!$isTestApiToken && $this->businessApiUrl && $this->authToken) {
            $authApiClient = static::getApiClient();
            foreach (AddEventRequestModel::getEventTypesEnum() as $eventType) {
                $eventDatetimeField         = $eventType . '_datetime';
                $eventNotifiedDatetimeField = $eventType . '_notified_datetime';

                if ($this->getData($eventDatetimeField) > $this->getData($eventNotifiedDatetimeField)) {
                    try {
                        $authApiClient->addEvent(
                            new AddEventRequestModel(
                                $eventType, $url, $this->getData($eventDatetimeField)
                            )
                        );
                        $this->setData($eventNotifiedDatetimeField, date('c'));
                    } catch (DvCmsModuleApiHttpException $e) {
                        // Пока проблемы с API просто гасим. Метрики дойдут при следующих попытках
                    }
                }
            }
        }
    }

    public function wizardStepCompleted(int $stepNumber, string $datetime = null)
    {
        $fieldName = '';
        switch ($stepNumber) {
            case 1:
                $fieldName = 'wizard_step_1_completed_datetime';
                break;
            case 2:
                $fieldName = 'wizard_step_2_completed_datetime';
                break;
            case 3:
                $fieldName = 'wizard_step_3_completed_datetime';
                break;
            case 4:
                $fieldName = 'wizard_step_4_completed_datetime';
                break;
        }

        if (!$fieldName) {
            return;
        }

        $this->setData($fieldName, date('c', strtotime($datetime ?? 'now')));
        $this->sendModuleMetrics();
    }

    public function checkoutCalculation(string $datetime = null)
    {
        $currentDataTime = date('c', strtotime($datetime ?? 'now'));

        // Метрика про первый расчет из чекаута устанавливается только один раз
        if (empty($this->getData(AddEventRequestModel::EVENT_TYPE_CHECKOUT_FIRST_CALCULATION . '_datetime'))) {
            $this->setData(AddEventRequestModel::EVENT_TYPE_CHECKOUT_FIRST_CALCULATION . '_datetime', $currentDataTime);
        }

        $this->setData(AddEventRequestModel::EVENT_TYPE_CHECKOUT_LAST_CALCULATION . '_datetime', $currentDataTime);
    }

    public function checkoutOrder(string $datetime = null)
    {
        $currentDataTime = date('c', strtotime($datetime ?? 'now'));

        // Метрика про первый заказ из чекаута устанавливается только один раз
        if (empty($this->getData(AddEventRequestModel::EVENT_TYPE_CHECKOUT_FIRST_ORDER . '_datetime'))) {
            $this->setData(AddEventRequestModel::EVENT_TYPE_CHECKOUT_FIRST_ORDER . '_datetime', $currentDataTime);
        }

        $this->setData(AddEventRequestModel::EVENT_TYPE_CHECKOUT_LAST_ORDER . '_datetime', $currentDataTime);
    }

    public function moduleOrder(string $datetime = null)
    {

        $currentDataTime = date('c', strtotime($datetime ?? 'now'));

        // Метрика про первый заказ из формы устанавливается только один раз
        if (empty($this->getData(AddEventRequestModel::EVENT_TYPE_MODULE_FIRST_ORDER . '_datetime'))) {
            $this->setData(AddEventRequestModel::EVENT_TYPE_MODULE_FIRST_ORDER . '_datetime', $currentDataTime);
        }

        $this->setData(AddEventRequestModel::EVENT_TYPE_MODULE_LAST_ORDER . '_datetime', $currentDataTime);
    }
}
