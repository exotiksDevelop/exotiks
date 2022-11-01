<?php

require_once( DIR_SYSTEM . "/engine/neoseo_model.php");

class ModelModuleNeoSeoBitrix24 extends NeoSeoModel
{

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = 'neoseo_bitrix24';
		$this->_modulePostfix = ""; // Постфикс для разных типов модуля, поэтому переходим на испольлзование $this->_moduleSysName()()
		$this->_logFile = $this->_moduleSysName() . '.log';
		$this->debug = $this->config->get($this->_moduleSysName() . '_debug') == 1;

		$this->params = array(
			'status' => 1,
			'debug' => 0,
			'portal_name' => '',
			'id_user' => '',
			'secret_code' => '',
			'add_contact' => 0,
			'contact_user_id' => '',
			'source_contact' => '',
			'type_contact_default' => '',
			'lead_user_id' => '',
			'add_lead_register' => 1,
			'source_lead_register' => '',
			'add_lead_neoseo_catch_contacts' => 0,
			'source_lead_neoseo_catch_contacts' => '',
			'add_lead_neoseo_notify_when_available' => 0,
			'source_lead_neoseo_notify_when_available' => '',
			'add_deal_order' => 1,
			'deal_user_id' => '',
			'deal_stage_default' => '',
			'deal_type_default' => '',
			'deal_extra_property' => '',
			'unload_options' => array(),
			'unload_order_status' => array(),
			'domain' => array('bitrix24.ua'),
		);
	}

	public function install()
	{
		// Значения параметров по умолчанию
		$this->initParams($this->params);

		// Создаем новые и недостающие таблицы в актуальной структуре
		$this->installTables();

		return TRUE;
	}

	public function installTables()
	{
		$result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "group_to_contact_bitrix24'");
		if (!$result->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "group_to_contact_bitrix24` ("
			    . "`id`  INT NOT NULL AUTO_INCREMENT, "
			    . "`customer_group_id` int(11) NOT NULL, "
			    . "`contact_type` varchar(255) NOT NULL,"
			    . "PRIMARY KEY (`id`) "
			    . ") ENGINE=InnoDB;");
		}

		$result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order_status_to_deal_stage_bitrix24'");
		if (!$result->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_status_to_deal_stage_bitrix24` ("
			    . "`id`  INT NOT NULL AUTO_INCREMENT, "
			    . "`order_status_id` int(11) NOT NULL, "
			    . "`deal_stage` varchar(255) NOT NULL,"
			    . "PRIMARY KEY (`id`) "
			    . ") ENGINE=InnoDB;");
		}

		$result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "category_to_deal_type_bitrix24'");
		if (!$result->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_to_deal_type_bitrix24` ("
			    . "`id`  INT NOT NULL AUTO_INCREMENT, "
			    . "`category_id` int(11) NOT NULL, "
			    . "`deal_type` varchar(255) NOT NULL,"
			    . "PRIMARY KEY (`id`) "
			    . ") ENGINE=InnoDB;");
		}

		$result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "customer_to_lead_bitrix24'");
		if (!$result->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_to_lead_bitrix24` ("
			    . "`id`  INT NOT NULL AUTO_INCREMENT, "
			    . "`customer_id` int(11) NOT NULL, "
			    . "`email` varchar(255) NOT NULL,"
			    . "`lead_id` int(11) NOT NULL,"
			    . "PRIMARY KEY (`id`) "
			    . ") ENGINE=InnoDB;");
		}

		$result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "customer_to_contact_bitrix24'");
		if (!$result->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_to_contact_bitrix24` ("
			    . "`id`  INT NOT NULL AUTO_INCREMENT, "
			    . "`customer_id` int(11) NOT NULL, "
			    . "`email` varchar(255) NOT NULL,"
			    . "`contact_id` int(11) NOT NULL,"
			    . "PRIMARY KEY (`id`) "
			    . ") ENGINE=InnoDB;");
		}

		$result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order_to_deal_bitrix24'");
		if (!$result->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_to_deal_bitrix24` ("
			    . "`id`  INT NOT NULL AUTO_INCREMENT, "
			    . "`order_id` int(11) NOT NULL, "
			    . "`order_product_id` int(11) NOT NULL,"
			    . "`deal_id` int(11) NOT NULL,"
			    . "PRIMARY KEY (`id`) "
			    . ") ENGINE=InnoDB;");
		}

		// Добавляем недостающие столбцы
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_to_contact_bitrix24` LIKE 'email'");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_to_contact_bitrix24`  ADD `email` VARCHAR(255) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer_to_lead_bitrix24` LIKE 'email'");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_to_lead_bitrix24`  ADD `email` VARCHAR(255) NOT NULL");
		}

		return TRUE;
	}

	public function upgrade()
	{

		// Добавляем недостающие новые параметры
		$this->initParams($this->params);

		// Создаем недостающие таблицы в актуальной структуре
		$this->installTables();

		return TRUE;
	}

	public function uninstall()
	{
		// Удаляем таблицы модуля
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "group_to_contact_bitrix24");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_status_to_deal_stage_bitrix24");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "category_to_deal_type_bitrix24");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "customer_to_lead_bitrix24");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "customer_to_contact_bitrix24");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_to_deal_bitrix24");

		return TRUE;
	}

}
