<?php

/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpIncludeInspection */

require_once realpath(DIR_SYSTEM . 'library/qiwi/load.php');

/**
 * Class ModelExtensionPaymentQiwi.
 *
 * @property-read Loader $load
 * @property-read DB $db
 * @property-read ModelSettingEvent $model_setting_event
 */
class ModelExtensionPaymentQiwi extends Model implements \Qiwi\Admin\Model
{
    /**
     * The table name.
     *
     * @var string
     */
    const TABLE_NAME   = 'qiwi_bill';

    /**
     * The create table query.
     *
     * @var string
     */
    const CREATE_TABLE = 'CREATE TABLE IF NOT EXISTS `%1$s%2$s` ('
                       . '`bill_id` CHAR(36) NOT NULL PRIMARY KEY,'
                       . '`order_id` int(11) NOT NULL,'
                       . '`refund` int(36) NULL'
                       . ') ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;';

    /**
     * The drop table query.
     *
     * @var string
     */
    const DROP_TABLE   = 'DROP TABLE IF EXISTS `%1$s%2$s`;';

    /**
     * The bills query.
     *
     * @var string
     */
    const QUERY_REFUNDS = 'SELECT `bill_id` FROM `%1$s%2$s` WHERE `refund` = \'%3$s\';';

    /**
     * The bills query.
     *
     * @var string
     */
    const QUERY_BILLS = 'SELECT `bill_id` FROM `%1$s%2$s` WHERE `refund` IS NULL AND `order_id` = %3$d;';

    /**
     * The orders query.
     *
     * @var string
     */
    const QUERY_ORDER = 'SELECT `order_id` FROM `%1$s%2$s` WHERE `refund` IS NULL AND `bill_id` = \'%3$s\';';

    /**
     * The insert query.
     *
     * @var string
     */
    const QUERY_INSERT = 'INSERT INTO `%1$s%2$s` SET `refund` = \'%3$s\', `bill_id` = \'%4$s\', `order_id` = %5$d;';

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function install()
    {
        $this->db->query(sprintf(
            self::CREATE_TABLE,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME
        ));
        $this->load->model('setting/event');
        $this->model_setting_event->addEvent(
            'extension_qiwi_checkout_js',
            'catalog/controller/checkout/checkout/before',
            'extension/payment/qiwi/eventLoadCheckoutJs'
        );
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function uninstall()
    {
        $this->db->query(sprintf(
            self::DROP_TABLE,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME
        ));
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('extension_qiwi_checkout_js');
    }

    /**
     * @inheritDoc
     */
    public function getRefunds($bill)
    {
        $query = $this->db->query(sprintf(
            self::QUERY_REFUNDS,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME,
            $bill
        ));

        return $query instanceof stdClass
            ? array_map(function ($row) {
                return $row['bill_id'];
            }, $query->rows)
            : [];
    }

    /**
     * @inheritDoc
     */
    public function getBills($order)
    {
        $query = $this->db->query(sprintf(
            self::QUERY_BILLS,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME,
            (int) $order
        ));

        return $query instanceof stdClass
            ? array_map(function ($row) {
                return $row['bill_id'];
            }, $query->rows)
            : [];
    }

    /**
     * @inheritDoc
     */
    public function getOrder($bill)
    {
        $query = $this->db->query(sprintf(
            self::QUERY_ORDER,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME,
            $bill
        ));

        return $query instanceof stdClass ? (int) $query->row['order_id'] : null;
    }

    /**
     * @inheritDoc
     */
    public function addRefund($bill, $refund, $order)
    {
        $this->db->query(sprintf(
            self::QUERY_INSERT,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME,
            $bill,
            $refund,
            (int) $order
        ));
    }
}
