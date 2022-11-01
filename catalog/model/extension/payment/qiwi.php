<?php

/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpIncludeInspection */

require_once realpath(DIR_SYSTEM . 'library/qiwi/load.php');

/**
 * Class ModelExtensionPaymentQiwi.
 *
 * @property-read Loader $load
 * @property-read Config $config
 * @property-read Language $language
 * @property-read DB $db
 * @property-read Log $log
 */
class ModelExtensionPaymentQiwi extends Model implements \Qiwi\Catalog\Model
{
    /**
     * The geo zone query.
     *
     * @var string
     */
    const QUERY_GEO_ZONE = 'SELECT * FROM `%1$s%2$s` WHERE `geo_zone_id` = %3$d AND `country_id` = %4$d AND (`zone_id` = %5$d OR `zone_id` = 0);';

    /**
     * The table name.
     *
     * @var string
     */
    const TABLE_NAME     = 'qiwi_bill';

    /**
     * The insert query.
     *
     * @var string
     */
    const QUERY_INSERT   = 'INSERT INTO `%1$s%2$s` SET `bill_id` = \'%3$s\', `order_id` = %4$d;';

    /**
     * The order query.
     *
     * @var string
     */
    const QUERY_ORDER    = 'SELECT `order_id` FROM `%1$s%2$s` WHERE `bill_id` = \'%3$s\';';

    /**
     * The bills query.
     *
     * @var string
     */
    const QUERY_BILLS    = 'SELECT `bill_id` FROM `%1$s%2$s` WHERE `order_id` = %3$d;';

    /**
     * @inheritDoc
     */
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/qiwi');
        $query = $this->db->query(sprintf(
            self::QUERY_GEO_ZONE,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            'zone_to_geo_zone',
            (int) $this->config->get('payment_cod_geo_zone_id'),
            (int) $address['country_id'],
            (int) $address['zone_id']
        ));
        if ($this->config->get('payment_qiwi_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('payment_qiwi_geo_zone_id')) {
            $status = true;
        } elseif ($query instanceof stdClass && $query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        return $status ? [
            'code'       => 'qiwi',
            'title'      => $this->language->get('text_title'),
            'terms'      => $this->config->get('payment_qiwi_description'),
            'sort_order' => $this->config->get('payment_qiwi_sort_order')
        ] : [];
    }

    /**
     * @inheritDoc
     */
    public function addBill($bill, $order) {
        $this->db->query(sprintf(
            self::QUERY_INSERT,
            defined('DB_PREFIX') ? DB_PREFIX : '',
            self::TABLE_NAME,
            $bill,
            (int) $order
        ));
    }

    /**
     * @inheritDoc
     */
    public function getOrder($bill) {
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
    public function getBills($order) {
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
}
