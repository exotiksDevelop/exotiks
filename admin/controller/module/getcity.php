<?php
class ControllerModuleGetcity extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/getcity');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('getcity', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_token'] = $this->language->get('entry_token');
		$data['entry_limit'] = $this->language->get('entry_limit');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/getcity', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('module/getcity', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['getcity_status'])) {
			$data['getcity_status'] = $this->request->post['getcity_status'];
		} else {
			$data['getcity_status'] = $this->config->get('getcity_status');
		}

		if (isset($this->request->post['getcity_token'])) {
			$data['getcity_token'] = $this->request->post['getcity_token'];
		} else {
			$data['getcity_token'] = $this->config->get('getcity_token');
		}

		if (isset($this->request->post['getcity_limit'])) {
			$data['getcity_limit'] = (int)$this->request->post['getcity_limit'];
		} else {
			$data['getcity_limit'] = (int)$this->config->get('getcity_limit');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/getcity.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/getcity')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {

		

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "zone_to_vkzone` (
			  `vkzone_id` int(11) NOT NULL,
			  `zone_id` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  PRIMARY KEY (`vkzone_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1000001, 2760, 'Республика Адыгея');");			
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1000236, 2724, 'Архангельская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1004118, 2725, 'Астраханская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1004565, 2794, 'Республика Башкортостан');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1009404, 2727, 'Белгородская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1011109, 2730, 'Брянская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1014032, 2801, 'Волгоградская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1015702, 2802, 'Вологодская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1023816, 2803, 'Воронежская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1025654, 2759, 'Республика Дагестан');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1027297, 2741, 'Ивановская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1030371, 2765, 'Республика Ингушетия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1030428, 2763, 'Кабардино-Балкарская Республика');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1030632, 2743, 'Калининградская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1031793, 2736, 'Республика Калмыкия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1032084, 2744, 'Калужская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1035359, 2733, 'Карачаево-Черкесская Республика');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1035522, 2776, 'Республика Карелия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1036606, 2787, 'Республика Коми');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1037344, 2750, 'Костромская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1040652, 2751, 'Краснодарский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1042388, 2755, 'Курская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1045244, 2735, 'Ленинградская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1048584, 2757, 'Липецкая область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1050307, 2808, 'Республика Марий Эл');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1052052, 2782, 'Республика Мордовия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1053480, 2722, 'Московская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1060316, 2762, 'Мурманская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1060458, 2767, 'Новгородская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1064424, 2770, 'Орловская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1067455, 2773, 'Пензенская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1069004, 2777, 'Псковская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1077676, 2778, 'Ростовская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1080077, 2779, 'Рязанская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1082931, 2781, 'Самарская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1084332, 2783, 'Саратовская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1086244, 2798, 'Республика Северная Осетия — Алания');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1086468, 2784, 'Смоленская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1091406, 2786, 'Ставропольский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1092174, 2788, 'Тамбовская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1094197, 2746, 'Республика Татарстан');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1097508, 2792, 'Тверская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1105465, 2790, 'Тульская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1109098, 2742, 'Удмуртская Республика');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1111137, 2795, 'Ульяновская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1112201, 2732, 'Челябинская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1113642, 2739, 'Чеченская Республика');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1113937, 2731, 'Чувашская Республика — Чувашия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1115658, 2806, 'Ярославская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1121540, 2738, 'Республика Алтай');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1121829, 2726, 'Алтайский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1123488, 2729, 'Амурская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1124157, 2796, 'Республика Бурятия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1124833, 2799, 'Владимирская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1127400, 2728, 'Еврейская автономная область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1127513, 2740, 'Иркутская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1128991, 2775, 'Камчатский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1129059, 2747, 'Кемеровская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1130218, 2804, 'Кировская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1134771, 2752, 'Красноярский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1137144, 2754, 'Курганская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1138434, 2758, 'Магаданская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1138534, 2766, 'Нижегородская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1143518, 2768, 'Новосибирская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1145150, 2769, 'Омская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1146712, 2771, 'Оренбургская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1148549, 2774, 'Пермский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1152714, 2800, 'Приморский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1153366, 2805, 'Республика Саха (Якутия)');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1153840, 2737, 'Сахалинская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1154131, 2807, 'Свердловская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1156388, 2789, 'Томская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1157049, 2756, 'Республика Тыва');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1157218, 2793, 'Тюменская область');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1158917, 2748, 'Хабаровский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1159424, 2721, 'Республика Хакасия');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1159710, 2749, 'Ханты-Мансийский автономный округ — Югра');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1159987, 2734, 'Забайкальский край');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1160844, 2723, 'Чукотский автономный округ');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1160930, 2780, 'Ямало-Ненецкий автономный округ');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (5331184, 2764, 'Ненецкий автономный округ');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (1500001, 3483, 'Крым');");
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (9999999, 2761, 'Москва');");	
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (9999998, 2785, 'Санкт-Петербург');");	
		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "zone_to_vkzone` (`vkzone_id`, `zone_id`, `name`) VALUES (9999997, 3498, 'Севастополь');");	


	}
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "zone_to_vkzone`");
	}
}