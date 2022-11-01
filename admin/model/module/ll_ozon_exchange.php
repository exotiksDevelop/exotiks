<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
class ModelExtensionModuleLLOzonExchange extends Model {
	protected $code = false;
	protected $statics = false;
	protected $ll = false;

	public function __construct($registry) {
		$this->registry = $registry;

		$this->code = basename(__FILE__, '.php');

		$this->statics = new \Config();
		$this->statics->load($this->code);

		$this->ll = new LL\Core($this->registry, $this->code, $this->statics->get('type'));
	}

	public function getOrder($order_id) {
		$export_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->code . "_order` WHERE order_id = " . (int)$order_id . "");

		if (!$export_query->num_rows) {
			return;
		}

		$package_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->code . "_package` WHERE order_id = " . (int)$order_id . "");

		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		$product_query = $this->db->query("SELECT op.*, p.shipping, p.length, p.width, p.height, p.length_class_id, p.tax_class_id, p.model, p.sku, p.upc, p.ean, p.jan, p.isbn, p.mpn FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = op.product_id) WHERE order_id = '" . (int)$order_id . "'");

		$product_count = 0;

		foreach ($product_query->rows as $product) {
			$product_count += $product['quantity'];
		}

		// если включена упаковка и задан ее вес, прибавляем его к общему весу заказа
		$order_weight = $export_query->row['weight'];

		if ($this->config->get($this->ll->getPrefix() . '_default_product_pack')) {
			$order_weight += (float)$this->config->get($this->ll->getPrefix() . '_default_product_pack_weight');
		}

		// форматируем полученный общий вес
		$order_weight = number_format($order_weight, 2, '.', '');

		// рассчитываем средний вес одного товара, исходя из общего веса заказа,
		// полученного при расчете доставки, т.к. реального веса товара может не быть
		$product_weight = $order_weight / $product_count;

		// форматируем полученный вес одного товара
		$product_weight = number_format($product_weight, 2, '.', '');

		$total = 0; // общая стоимость заказа
		$total_cost = 0; // общая стоимость заказа в валюте договора
		$total_diff = 0; // общая сумма расхождений итого
		$total_count = 0; // общее количество единиц всех товаров
		$cod = false;
		$length = 0;
		$width = 0;
		$height = 0;

		$payment_code = $order_query->row['payment_code'];

		// поддержка виртуальных платежек
		$payment_code = explode('.', $order_query->row['payment_code']);

		if (isset($payment_code[0])) {
			$payment_code = $payment_code[0];
		}

		if (is_array($this->config->get($this->ll->getPrefix() . '_cod')) && in_array($payment_code, $this->config->get($this->ll->getPrefix() . '_cod'))) {
			$cod = true;
		}

		foreach ($product_query->rows as $product) {
			$name_options = ' ';

			$options = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

			foreach ($options->rows as $option) {
				if ($option['type'] != 'file') {
					$name_options .= ' | ' . $option['name'] . ' - ' . $option['value'];
				}
			}

			// округляем согласно настройкам валюты
			$decimal_place = $this->currency->getDecimalPlace($order_query->row['currency_code']);

			// отнимаем от стоимости товаров сумму скидки, которая идет отдельной строкой в Итого
			// аналогично для наценки
			$product_сost = number_format($this->currency->convert($product['price'], $this->config->get('config_currency'), $order_query->row['currency_code']), (int)$decimal_place, '.', '');
			$product_payment = $product_сost;

			// определяем налоги товара
			$vat_rate = 0;
			$vat_sum = 0;

			if ($this->config->get('config_tax') && $product['tax_class_id'] > 0) {
				$tax_rules = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int)$product['tax_class_id'] . "'");

				if ($tax_rules->num_rows) {
					foreach ($tax_rules->rows as $rule) {
						if ($rule['based'] == 'shipping') {
							$tax_rate = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$rule['tax_rate_id'] . "'");

							if ($tax_rate->num_rows && $tax_rate->row['type'] == 'P') {
								$vat_rate = (int)$tax_rate->row['rate'];
								$vat_sum = (int)$product['tax'];
							}
						}
					}
				}
			}

			if ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 0) {
				$insurance = $product_сost;
			} elseif ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 1 && is_numeric($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num'))) {
				$insurance = $product_сost * ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num') / 100);
			} elseif ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 2 && is_numeric($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num'))) {
				$insurance = $this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num');
			} else {
				$insurance = 0;
			}

			$products[] = [
				'edit'           => $this->ll->getLink('catalog/product/edit', '&product_id=' . $product['product_id']),
				'name'           => $product['name'] . $name_options,
				'articleNumber'  => $product[$this->config->get($this->ll->getPrefix() . '_default_product_article')],
				'sellingPrice'   => $product_сost,
				'estimatedPrice' => $insurance,
				'quantity'       => $product['quantity'],
				'vat_rate'       => $vat_rate,
				'vat_sum'        => $vat_sum,
				'isDangerous'    => 0,
				'supplierTin'    => '',
			];

			$total += $product_payment * $product['quantity'];
			$total_cost += $product_сost * $product['quantity'];
			$total_count += $product['quantity'];

			// собираем габариты упаковки
			if ($product['shipping']) {
				$product_length = $this->length->convert($product['length'], $product['length_class_id'], $this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_length_class_id'));
				$product_width = $this->length->convert($product['width'], $product['length_class_id'], $this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_length_class_id'));
				$product_height = $this->length->convert($product['height'], $product['length_class_id'], $this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_length_class_id'));

				$product_length = $product_length == 0 && !$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_type') ? (float)$this->config->get($this->m . '_default_length') : $product_length;
				$product_width = $product_width == 0 && !$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_type') ? (float)$this->config->get($this->m . '_default_width') : $product_width;
				$product_height = $product_height == 0 && !$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_type') ? (float)$this->config->get($this->m . '_default_height') : $product_height;

				$length += $product_length * $product['quantity'];
				$width += $product_width * $product['quantity'];
				$height += $product_height * $product['quantity'];
			}
		}

		// стоимость доставки
		$shipping_cost = $this->db->query("SELECT value FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'shipping'");
		$shipping_cost = $this->currency->convert($shipping_cost->row['value'], $this->config->get('config_currency'), $order_query->row['currency_code']);
		$total += $shipping_cost;

		$total = number_format($total, (int)$decimal_place, '.', '');
		$shipping_cost = number_format($shipping_cost, (int)$decimal_place, '.', '');

		// определяем несоответствия итоговой стоимости из-за всяких скидок и наценок
		$order_totals = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		foreach ($order_totals->rows as $order_total) {
			if ($order_total['code'] == 'total') {
				$order_total = number_format($this->currency->convert($order_total['value'], $this->config->get('config_currency'), $order_query->row['currency_code']), (int)$decimal_place, '.', '');

				if ($order_total != $total) {
					$total_diff = $order_total - $total;
				}
			}
		}

		if ($total_diff != 0) {
			$diff = $total_diff / $total_count;

			foreach ($products as $key => $product) {
				$products[$key]['sellingPrice'] += $diff;

				if ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 0) {
					$products[$key]['estimatedPrice'] += $diff;
				}
			}

			$total += $total_diff;
		}

		$total = number_format($total, (int)$decimal_place, '.', '');

		// объединение start
		$merge_total = $total - $shipping_cost;
		$merge_total = number_format((string)$merge_total, (int)$decimal_place, '.', '');

		if ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 0) {
			$insurance = $merge_total;
		} elseif ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 1 && is_numeric($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num'))) {
			$insurance = $merge_total * ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num') / 100);
		} elseif ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance') == 2 && is_numeric($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num'))) {
			$insurance = $this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_insurance_num');
		} else {
			$insurance = 0;
		}

		$merge = [
			'edit'           => $this->ll->getLinkExtension(),
			'name'           => $this->config->get($this->ll->getPrefix() . '_merge_name'),
			'articleNumber'  => $this->config->get($this->ll->getPrefix() . '_merge_model'),
			'sellingPrice'   => $merge_total,
			'estimatedPrice' => $insurance,
			'quantity'       => 1,
			'vat_rate'       => $this->config->get($this->ll->getPrefix() . '_merge_vat'),
			'vat_sum'        => $this->currency->format($merge_total - ($merge_total / (1 + $this->config->get($this->ll->getPrefix() . '_merge_vat') / 100)), $order_query->row['currency_code'], '', false),
			'isDangerous'    => $this->config->get($this->ll->getPrefix() . '_merge_danger'),
			'supplierTin'    => $this->config->get($this->ll->getPrefix() . '_merge_inn'),
		];

		// автоматически объединяем товары
		if ($this->config->get($this->ll->getPrefix() . '_merge')) {
			$products = [$merge];
		}

		// объединяем товары с одинаковой моделью
		if ($this->config->get($this->ll->getPrefix() . '_merge_from_model')) {
			$models = [];

			foreach ($products as $key => $product) {
				if (isset($models[$product['articleNumber']])) {
					$products[$models[$product['articleNumber']]]['quantity'] += $product['quantity'];
					$products[$models[$product['articleNumber']]]['sellingPrice'] += $product['sellingPrice'];
					$products[$models[$product['articleNumber']]]['estimatedPrice'] += $product['estimatedPrice'];
					$products[$models[$product['articleNumber']]]['vat_sum'] += $product['vat_sum'];

					$unset_keys[] = $key;
				} else {
					$models[$product['articleNumber']] = $key;
				}
			}

			foreach ($unset_keys as $key) {
				unset($products[$key]);
			}
		}
		// объединение end

		// если отправление сохранено, то сразу берем исходники
		if ($export_query->row['orderNumber']) {
			return [
				'order_id'                                             => $export_query->row['order_id'],
				'places'                                               => $this->getPlaces(),
				'link_edit'                                            => $this->ll->getLink('sale/order/info', '&order_id=' . $export_query->row['order_id']),
				'orderId'                                              => $export_query->row['orderId'],
				'orderNumber'                                          => $export_query->row['orderNumber'],
				'firstMileTransfer_type'                               => $export_query->row['firstMileTransfer_type'],
				'firstMileTransfer_fromPlaceId'                        => $export_query->row['firstMileTransfer_fromPlaceId'],
				'allowPartialDelivery'                                 => $export_query->row['allowPartialDelivery'],
				'allowUncovering'                                      => $export_query->row['allowUncovering'],
				'comment'                                              => $export_query->row['comment'],
				'buyer_type'                                           => $export_query->row['buyer_type'],
				'buyer_legalName'                                      => $export_query->row['buyer_legalName'],
				'buyer_name'                                           => $export_query->row['buyer_name'],
				'buyer_phone'                                          => $export_query->row['buyer_phone'],
				'buyer_email'                                          => $export_query->row['buyer_email'],
				'recipient_type'                                       => $export_query->row['recipient_type'],
				'recipient_legalName'                                  => $export_query->row['recipient_legalName'],
				'recipient_name'                                       => $export_query->row['recipient_name'],
				'recipient_phone'                                      => $export_query->row['recipient_phone'],
				'recipient_email'                                      => $export_query->row['recipient_email'],
				'payment_type'                                         => $export_query->row['payment_type'],
				'payment_prepaymentAmount'                             => $export_query->row['payment_prepaymentAmount'],
				'payment_recipientPaymentAmount'                       => $export_query->row['payment_recipientPaymentAmount'],
				'payment_deliveryPrice'                                => $export_query->row['payment_deliveryPrice'],
				'payment_deliveryVat_rate'                             => $export_query->row['payment_deliveryVat_rate'],
				'payment_deliveryVat_sum'                              => $export_query->row['payment_deliveryVat_sum'],
				'deliveryInformation_deliveryVariantId'                => $export_query->row['pvz'],
				'deliveryInformation_deliveryType'                     => $export_query->row['tariff'],
				'deliveryInformation_address'                          => $export_query->row['deliveryInformation_address'],
				'deliveryInformation_additionalAddress'                => $export_query->row['deliveryInformation_additionalAddress'],
				'deliveryInformation_desiredDeliveryTimeInterval_from' => $export_query->row['deliveryInformation_desiredDeliveryTimeInterval_from'],
				'deliveryInformation_desiredDeliveryTimeInterval_to'   => $export_query->row['deliveryInformation_desiredDeliveryTimeInterval_to'],
				'packages_packageNumber'                               => $package_query->row['packageNumber'],
				'packages_dimensions_length'                           => $package_query->row['length'],
				'packages_dimensions_height'                           => $package_query->row['height'],
				'packages_dimensions_width'                            => $package_query->row['width'],
				'packages_dimensions_weight'                           => $package_query->row['weight'],
				'packages_barCode'                                     => $package_query->row['barCode'],
				'products'                                             => $products,
				'merge'                                                => $merge,
			];
		}

		if ($export_query->row['tariff'] == 'courier') {
			$courier_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_pvz` WHERE city_id = '" . (int)$export_query->row['to_city'] . "' AND objectTypeName LIKE 'Курьерская'  ORDER BY pvz_id ASC");

			$pvz = isset($courier_query->rows[0]['external_id']) ? $courier_query->rows[0]['external_id'] : '';
		} else {
			$pvz = $export_query->row['pvz'];
		}

		if ($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_type')) {
			if ($product_length == 0) {
				$length = (float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_length');
			}

			if ($product_width == 0) {
				$width = (float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_width');
			}

			if ($product_height == 0) {
				$height = (float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_default_height');
			}
		}

		if ((float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_box_length') > 0) {
			$length += (float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_box_length');
		}

		if ((float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_box_width') > 0) {
			$width += (float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_box_width');
		}

		if ((float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_box_height') > 0) {
			$height += (float)$this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_box_height');
		}

		if (!empty($this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_pickup_cities'))) {
			$place_id = $this->config->get($this->ll->getPrefix('ll_ozon', 'shipping') . '_pickup_cities')[0];
		} else {
			$place_id = 0;
		}

		$to_data = $this->getCity($export_query->row['to_city']);

		if ($order_query->num_rows) {
			return [
				'order_id'                                             => $order_query->row['order_id'],
				'places'                                               => $this->getPlaces(),
				'link_edit'                                            => $this->ll->getLink('sale/order/info', '&order_id=' . $order_query->row['order_id']),
				'orderNumber'                                          => str_replace(['{{order_id}}'], ['order_id' => $order_query->row['order_id']], $this->config->get($this->ll->getPrefix() . '_default_order_id')),
				'firstMileTransfer_type'                               => $this->config->get($this->ll->getPrefix() . '_default_firstMileTransfer_type'),
				'firstMileTransfer_fromPlaceId'                        => $place_id,
				'allowPartialDelivery'                                 => $this->config->get($this->ll->getPrefix() . '_default_allowPartialDelivery'),
				'allowUncovering'                                      => $this->config->get($this->ll->getPrefix() . '_default_allowUncovering'),
				'comment'                                              => str_replace(['{{comment}}'], ['comment' => $order_query->row['comment']], $this->config->get($this->ll->getPrefix() . '_default_order_comment')),
				'buyer_type'                                           => $this->config->get($this->ll->getPrefix() . '_default_buyer_type'),
				'buyer_legalName'                                      => '',
				'buyer_name'                                           => ($this->config->get($this->ll->getPrefix() . '_default_buyer_firstname') ? $order_query->row[$this->config->get($this->ll->getPrefix() . '_default_buyer_firstname')] : $order_query->row['firstname']) . ' ' . ($this->config->get($this->ll->getPrefix() . '_default_buyer_lastname') ? $order_query->row[$this->config->get($this->ll->getPrefix() . '_default_buyer_lastname')] : $order_query->row['lastname']),
				'buyer_phone'                                          => $order_query->row['telephone'],
				'buyer_email'                                          => $this->config->get($this->ll->getPrefix() . '_default_buyer_email') ? $order_query->row['email'] : '',
				'recipient_type'                                       => $this->config->get($this->ll->getPrefix() . '_default_recipient_type'),
				'recipient_legalName'                                  => '',
				'recipient_name'                                       => ($this->config->get($this->ll->getPrefix() . '_default_recipient_firstname') ? $order_query->row[$this->config->get($this->ll->getPrefix() . '_default_recipient_firstname')] : $order_query->row['firstname']) . ' ' . ($this->config->get($this->ll->getPrefix() . '_default_recipient_lastname') ? $order_query->row[$this->config->get($this->ll->getPrefix() . '_default_recipient_lastname')] : $order_query->row['lastname']),
				'recipient_phone'                                      => $order_query->row['telephone'],
				'recipient_email'                                      => $this->config->get($this->ll->getPrefix() . '_default_recipient_email') ? $order_query->row['email'] : '',
				'payment_type'                                         => $cod ? 'Postpay' : 'FullPrepayment',
				'payment_prepaymentAmount'                             => $total,
				'payment_recipientPaymentAmount'                       => $cod ? number_format($total_cost, (int)$decimal_place, '.', '') : 0,
				'payment_deliveryPrice'                                => $shipping_cost,
				'payment_deliveryVat_rate'                             => 0,
				'payment_deliveryVat_sum'                              => 0,
				'deliveryInformation_deliveryVariantId'                => $pvz,
				'deliveryInformation_deliveryType'                     => $export_query->row['tariff'],
				'deliveryInformation_address'                          => $to_data['name'] . ', ' . $order_query->row[$this->config->get($this->ll->getPrefix() . '_default_address')],
				'deliveryInformation_additionalAddress'                => '',
				'deliveryInformation_desiredDeliveryTimeInterval_from' => $this->config->get($this->ll->getPrefix() . '_default_deliveryInformation_desiredDeliveryTimeInterval_from'),
				'deliveryInformation_desiredDeliveryTimeInterval_to'   => $this->config->get($this->ll->getPrefix() . '_default_deliveryInformation_desiredDeliveryTimeInterval_to'),
				'packages_packageNumber'                               => 1,
				'packages_dimensions_length'                           => $length,
				'packages_dimensions_height'                           => $height,
				'packages_dimensions_width'                            => $width,
				'packages_dimensions_weight'                           => $order_weight,
				'packages_barCode'                                     => '',
				'products'                                             => $products,
				'merge'                                                => $merge,
			];
		} else {
			return;
		}
	}

	public function getOrders($data) {
		$sql = "SELECT e.*, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.customer_id, o.telephone, o.total, o.currency_code, o.currency_value, o.date_added, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . $this->code . "_order` as e LEFT JOIN `" . DB_PREFIX . "order` o ON e.order_id = o.order_id";

		if (!empty($data['filter_order_status'])) {
			$implode = [];

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif ($this->config->has($this->ll->getPrefix() . '_list_order_status') && !empty($this->config->get($this->ll->getPrefix() . '_list_order_status'))) {
			$implode = [];

			foreach ($this->config->get($this->ll->getPrefix() . '_list_order_status') as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_to'])) {
			$sql .= " AND e.to_city = '" . (int)$data['filter_to'] . "'";
		}

		if (!empty($data['filter_pvz'])) {
			$sql .= " AND e.pvz LIKE '%" . $this->db->escape($data['filter_pvz']) . "%' AND e.tariff IN ('" . implode('\', \'', $this->statics->get('variants_map')) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		if (!empty($data['filter_tariff'])) {
			$sql .= " AND e.tariff LIKE '" . $this->db->escape($data['filter_tariff']) . "'";
		}

		if (!empty($data['filter_logisticOrderNumber'])) {
			$sql .= " AND e.logisticOrderNumber LIKE '" . $this->db->escape($data['filter_logisticOrderNumber']) . "'";
		}

		if (!empty($data['filter_shipment_id'])) {
			$sql .= " AND e.shipment_id = '" . (int)$data['filter_shipment_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (is_numeric($data['filter_delivery_status'])) {
			if ((int)$data['filter_delivery_status'] == 0) {
				$sql .= " AND e.orderId is null";
			}

			$sql .= " AND e.status = '" . (int)$data['filter_delivery_status'] . "'";
		}

		$sql .= " ORDER BY e.order_id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalOrders($data) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . $this->code . "_order` as e LEFT JOIN `" . DB_PREFIX . "order` o ON e.order_id = o.order_id";

		if (!empty($data['filter_order_status'])) {
			$implode = [];

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif ($this->config->has($this->ll->getPrefix() . '_list_order_status') && !empty($this->config->get($this->ll->getPrefix() . '_list_order_status'))) {
			$implode = [];

			foreach ($this->config->get($this->ll->getPrefix() . '_list_order_status') as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_to'])) {
			$sql .= " AND e.to_city = '" . (int)$data['filter_to'] . "'";
		}

		if (!empty($data['filter_pvz'])) {
			$sql .= " AND e.pvz LIKE '" . $this->db->escape($data['filter_pvz']) . "'";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		if (!empty($data['filter_tariff'])) {
			$sql .= " AND e.tariff LIKE '" . $this->db->escape($data['filter_tariff']) . "'";
		}

		if (!empty($data['filter_logisticOrderNumber'])) {
			$sql .= " AND e.logisticOrderNumber LIKE '" . $this->db->escape($data['filter_logisticOrderNumber']) . "'";
		}

		if (!empty($data['filter_shipment_id'])) {
			$sql .= " AND e.shipment_id = '" . (int)$data['filter_shipment_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (is_numeric($data['filter_delivery_status'])) {
			if ((int)$data['filter_delivery_status'] == 0) {
				$sql .= " AND e.orderId is null";
			}

			$sql .= " AND e.status = '" . (int)$data['filter_delivery_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrderInfo($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $this->code . "_order` as e LEFT JOIN `" . DB_PREFIX . "order` o ON e.order_id = o.order_id LEFT JOIN `" . DB_PREFIX . $this->code . "_package` p ON e.order_id = p.order_id WHERE e.order_id = '" . (int)$order_id . "'");

		if (!$query->num_rows) {
			return;
		}

		return $query->row;
	}

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC");

		return $query->rows;
	}

	public function getOrderStatuses($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $this->code . "_status WHERE order_id = '" . (int)$order_id . "' ORDER BY date DESC");

		return $query->rows;
	}

	public function getCity($city_id) {
		$query = $this->db->query("SELECT c.name, r.name AS region_name, co.name AS country_name FROM `" . DB_PREFIX . "ll_ozon_city` as c LEFT JOIN `" . DB_PREFIX . "ll_ozon_region` r ON c.region_id = r.region_id LEFT JOIN `" . DB_PREFIX . "ll_ozon_country` co ON c.country_id = co.country_id WHERE c.city_id = '" . (int)$city_id . "'");

		if (!$query->num_rows) {
			return;
		}

		return $query->row;
	}

	public function getCities($city) {
		$query = $this->db->query("SELECT c.city_id AS id, c.name AS city, c.name AS full, r.zone_id AS zone_id, c.country_id AS country_id FROM `" . DB_PREFIX . "ll_ozon_city` c LEFT JOIN `" . DB_PREFIX . "ll_ozon_region_to_zone` r ON (c.region_id = r.region_id) WHERE c.name LIKE '" . $this->db->escape($city) . "%' ORDER BY c.name ASC LIMIT 0,7");

		return $query->rows;
	}

	public function getPvz($external_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_pvz` WHERE external_id LIKE '" . $this->db->escape($external_id) . "'");

		return $query->row;
	}

	public function getPlaces() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_place`");

		return $query->rows;
	}

	public function getPickups() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_pickup`");

		return $query->rows;
	}

	public function getPickup($pickup_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ll_ozon_pickup` WHERE id LIKE '" . $this->db->escape($pickup_id) . "'");

		return $query->row;
	}

	public function updateOrder($order) {
		$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_order` SET
			orderNumber = '" . $this->db->escape($order['orderNumber']) . "',
			buyer_name = '" . $this->db->escape($order['buyer']['name']) . "',
			buyer_type = '" . $this->db->escape($order['buyer']['type']) . "',
			buyer_legalName = '" . $this->db->escape($order['buyer']['legalName']) . "',
			buyer_email = '" . $this->db->escape($order['buyer']['email']) . "',
			buyer_phone = '" . $this->db->escape($order['buyer']['phone']) . "',
			recipient_name = '" . $this->db->escape($order['recipient']['name']) . "',
			recipient_type = '" . $this->db->escape($order['recipient']['type']) . "',
			recipient_legalName = '" . $this->db->escape($order['recipient']['legalName']) . "',
			recipient_email = '" . $this->db->escape($order['recipient']['email']) . "',
			recipient_phone = '" . $this->db->escape($order['recipient']['phone']) . "',
			firstMileTransfer_type = '" . $this->db->escape($order['firstMileTransfer']['type']) . "',
			firstMileTransfer_fromPlaceId = '" . $this->db->escape($order['firstMileTransfer']['fromPlaceId']) . "',
			payment_type = '" . $this->db->escape($order['payment']['type']) . "',
			payment_prepaymentAmount = '" .  (float)$order['payment']['prepaymentAmount'] . "',
			payment_recipientPaymentAmount = '" .  (float)$order['payment']['recipientPaymentAmount'] . "',
			payment_deliveryPrice = '" .  (float)$order['payment']['deliveryPrice'] . "',
			payment_deliveryVat_rate = '" .  (float)$order['payment']['deliveryVat']['rate'] . "',
			payment_deliveryVat_sum = '" .  (float)$order['payment']['deliveryVat']['sum'] . "',
			pvz = '" . $this->db->escape($order['deliveryInformation']['deliveryVariantId']) . "',
			deliveryInformation_address = '" . $this->db->escape($order['deliveryInformation']['address']) . "',
			deliveryInformation_desiredDeliveryTimeInterval_from = '" . $this->db->escape($order['deliveryInformation']['desiredDeliveryTimeInterval']['from']) . "',
			deliveryInformation_desiredDeliveryTimeInterval_to = '" . $this->db->escape($order['deliveryInformation']['desiredDeliveryTimeInterval']['to']) . "',
			deliveryInformation_additionalAddress = '" . $this->db->escape($order['deliveryInformation']['additionalAddress']) . "',
			comment = '" . $this->db->escape($order['comment']) . "',
			allowPartialDelivery = '" .  (float)$order['allowPartialDelivery'] . "',
			allowUncovering = '" .  (float)$order['allowUncovering'] . "',
			date = NOW()
		WHERE order_id = '" . (int)$order['order_id'] . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . $this->code . "_package` WHERE order_id = '" . (int)$order['order_id'] . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . $this->code . "_orderline` WHERE order_id = '" . (int)$order['order_id'] . "'");

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . $this->code . "_package` SET 
			packageNumber = '" . $this->db->escape($order['packages']['packageNumber']) . "',
			length = '" . (int)$order['packages']['dimensions']['length'] . "',
			height = '" . (int)$order['packages']['dimensions']['height'] . "',
			width = '" . (int)$order['packages']['dimensions']['width'] . "',
			weight = '" . (int)$order['packages']['dimensions']['weight'] . "',
			barCode = '" . $this->db->escape($order['packages']['barCode']) . "',
			order_id = '" . (int)$order['order_id'] . "'
		");

		foreach ($order['orderLines'] as $product) {
			$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . $this->code . "_orderline` SET 
				packageNumber = '" . $this->db->escape($product['resideInPackages']) . "',
				articleNumber = '" . $this->db->escape($product['articleNumber']) . "',
				name = '" . $this->db->escape($product['name']) . "',
				sellingPrice = '" . (float)$product['sellingPrice'] . "',
				estimatedPrice = '" . (float)$product['estimatedPrice'] . "',
				quantity = '" . (int)$product['quantity'] . "',
				vat_rate = '" . (float)$product['vat']['rate'] . "',
				vat_sum = '" . (float)$product['vat']['sum'] . "',
				isDangerous = '" . (int)$product['attributes']['isDangerous'] . "',
				supplierTin = '" . $this->db->escape($product['supplierTin']) . "',
				order_id = '" . (int)$order['order_id'] . "'
			");
		}
	}

	public function updateOrderInner($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_order` SET
			orderId = '" . (int)$data['id'] . "',
			logisticOrderNumber = '" . $this->db->escape($data['logisticOrderNumber']) . "',
			status = 998,
			date = NOW()
		WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_package` SET
			postingNumber = '" . $this->db->escape($data['packages'][0]['postingNumber']) . "',
			postingId = '" . (int)$data['packages'][0]['postingId'] . "',
			barCode = '" . $this->db->escape($data['packages'][0]['barCode']) . "'
		WHERE order_id = '" . (int)$order_id . "' AND packageNumber LIKE '" . $this->db->escape($data['packages'][0]['packageNumber']) . "'");
	}

	public function updateOrderShipment($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . $this->code . "_shipment` SET 
			shipment_id = '" . (int)$data['id'] . "',
			principalId = '" . (int)$data['principalId'] . "',
			actId = '" . (int)$data['actId'] . "',
			date = '" . $this->db->escape($data['date']) . "',
			status = '" . $this->db->escape($data['status']) . "'
		");

		foreach ($data['orderIds'] as $orderId) {
			$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_order` SET
				shipment_id = '" . (int)$data['id'] . "',
				status = 997,
				date = NOW()
			WHERE orderId = '" . (int)$orderId . "'");
		}
	}

	public function getOrderId($order_id) {
		$query = $this->db->query("SELECT orderId FROM " . DB_PREFIX . $this->code . "_order WHERE order_id = '" . (int)$order_id . "'");

		if (isset($query->row['orderId'])) {
			return $query->row['orderId'];
		}
	}

	public function getOrderShipmentId($order_id) {
		$query = $this->db->query("SELECT shipment_id FROM " . DB_PREFIX . $this->code . "_order WHERE order_id = '" . (int)$order_id . "'");

		if (isset($query->row['shipment_id'])) {
			return $query->row['shipment_id'];
		}
	}

	public function getOrderStatus($order_id, $code, $date = false) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$sql = "SELECT code FROM " . DB_PREFIX . $this->code . "_status WHERE order_id = '" . (int)$order_id . "' AND code = '" . (int)$code . "'";

		if ($date) {
			$sql .= " AND date = '" . date('Y-m-d H:i:s', strtotime($date)) . "'";
		}

		$query = $this->db->query($sql);

		if (isset($query->row['code'])) {
			return $query->row['code'];
		}
	}

	public function addOrderStatus($order_id, $data) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . $this->code . "_status` SET order_id = '" . (int)$order_id . "', date = '" . $this->db->escape(date('Y-m-d H:i:s', strtotime($data['date']))) . "', code = '" . (int)$data['code'] . "'");
		$this->updateOrderStatus($order_id, $data);
	}

	public function updateOrderStatus($order_id, $data) {
		date_default_timezone_set($this->config->get($this->ll->getPrefix() . '_timezone'));

		$this->db->query("UPDATE `" . DB_PREFIX . $this->code . "_order` SET status = '" . (int)$data['code'] . "', date = '" . $this->db->escape(date('Y-m-d H:i:s', strtotime($data['date']))) . "' WHERE order_id = '" . (int)$order_id . "'");
	}

	public function removeOrderFromModule($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . $this->code . "_order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . $this->code . "_status` WHERE order_id = '" . (int)$order_id . "'");
	}

	public function install() {
		//order_id - номер заказа в магазине
		//orderId - идентификатор заказа Ozon (id из ответа)
		//logisticOrderNumber - внутренний номер заказа Ozon
		//orderNumber - номер заказа при отправке
		//date - дата последнего действия
		//status - текущий статус, если 0, то не создан
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->code . "_order` (
			`order_id` int(11) NOT NULL,
			`orderId` int(11) DEFAULT NULL,
			`logisticOrderNumber` varchar(255) DEFAULT NULL,
			`shipment_id` int(11) DEFAULT NULL,
			`from_city` int(11) NOT NULL,
			`to_city` int(11) NOT NULL,
			`tariff` varchar(255) NOT NULL,
			`pvz` varchar(255) NOT NULL,
			`weight` int(11) NOT NULL,
			`orderNumber` varchar(255),
			`buyer_name` varchar(255),
			`buyer_type` varchar(255),
			`buyer_legalName` varchar(255),
			`buyer_email` varchar(255),
			`buyer_phone` varchar(255),
			`recipient_name` varchar(255),
			`recipient_type` varchar(255),
			`recipient_legalName` varchar(255),
			`recipient_email` varchar(255),
			`recipient_phone` varchar(255),
			`firstMileTransfer_type` varchar(255),
			`firstMileTransfer_fromPlaceId` varchar(255),
			`payment_type` varchar(255),
			`payment_prepaymentAmount` decimal(15,4),
			`payment_recipientPaymentAmount` decimal(15,4),
			`payment_deliveryPrice` decimal(15,4),
			`payment_deliveryVat_rate` decimal(15,4),
			`payment_deliveryVat_sum` decimal(15,4),
			`deliveryInformation_address` varchar(255),
			`deliveryInformation_timeSlotId` varchar(255),
			`deliveryInformation_desiredDeliveryTimeInterval_from` varchar(255),
			`deliveryInformation_desiredDeliveryTimeInterval_to` varchar(255),
			`deliveryInformation_additionalAddress` varchar(255),
			`comment` varchar(255),
			`allowPartialDelivery` tinyint(1),
			`allowUncovering` tinyint(1),
			`orderAttributes_contractorShortName` varchar(255),
			`orderAttributes_returnOfShippingDocuments` tinyint(1),
			`date` datetime,
			`status` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->code . "_package` (
			`order_id` int(11) NOT NULL,
			`packageNumber` varchar(255) NOT NULL,
			`postingNumber` varchar(255),
			`postingId` int(11),
			`weight` int(11),
			`length` int(11),
			`height` int(11),
			`width` int(11),
			`barCode` varchar(255),
			PRIMARY KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->code . "_orderline` (
			`order_id` int(11) NOT NULL,
			`packageNumber` varchar(255) NOT NULL,
			`articleNumber` varchar(255),
			`name` varchar(255),
			`sellingPrice` decimal(15,4),
			`estimatedPrice` decimal(15,4),
			`quantity` int(11),
			`vat_rate` decimal(15,4),
			`vat_sum` decimal(15,4),
			`isDangerous` tinyint(1),
			`supplierTin` varchar(255),
			PRIMARY KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->code . "_shipment` (
			`shipment_id` int(11) NOT NULL,
			`principalId` int(11) NOT NULL,
			`actId` int(11) NOT NULL,
			`date` datetime NOT NULL,
			`status` varchar(255) NOT NULL,
			PRIMARY KEY (`shipment_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		// order_id - номер заказа по postingNumber
		// date - moment
		// code - eventId
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $this->code . "_status` (
			`state_id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,
			`date` datetime NOT NULL,
			`code` int(11) NOT NULL,
			PRIMARY KEY (`state_id`),
			KEY `order_id` (`order_id`),
			UNIQUE KEY `order_id_date_code` (`order_id`, `date`, `code`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->code . "_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->code . "_package`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->code . "_orderline`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->code . "_shipment`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $this->code . "_status`;");
	}
}

class ModelModuleLLOzonExchange extends ModelExtensionModuleLLOzonExchange {}
