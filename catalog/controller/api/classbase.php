<?php

class ControllerApiClassbase extends Controller {

    protected $JSON = [];

	public function __destruct() {
		$this->setResponce();
    }

    public function get_version() {
        $this->JSON = [
            'version_module' => '2.1.0',
			'version_opencart' => VERSION,
        ];
	}

	protected function sql_update($table, $fields, $where) {
		foreach ($fields AS $k => &$v) $v = "{$k}='{$v}'";
		return 'UPDATE '.$table.' SET '.implode(',',$fields).' WHERE '.$where;
	}

    protected function setResponce() {
		if (empty($this->JSON)) return;
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->JSON));
	}

	protected function loadAdminModel($model, $data = array()) {
		// $this->event->trigger('pre.model.' . str_replace('/', '.', (string)$model), $data);

		$model = str_replace('../', '', (string)$model);

		$dir = $result = preg_replace("/catalog\\/$/", '', DIR_APPLICATION).'admin/';

		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if ( class_exists($class) ) {
			$class .= '_class';
			$file = $dir . 'model/' . $model . '_class.php';
		} else {
			$file = $dir . 'model/' . $model . '.php';
		}

		if (file_exists($file)) {
			include_once($file);

			$this->registry->set('model_admin_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}

		// $this->event->trigger('post.model.' . str_replace('/', '.', (string)$model), $output);
    }

    protected function langProp(&$prop, $prop_value) {
        $prop = [];
        foreach ($this->getArrLang() as $lang_id)
            $prop[$lang_id] = $prop_value;
	}

	protected function langProps(&$obj, $key, $value=[]) {
		$obj[$key] = [];
		$this->langProp($obj[$key], $value);
		foreach($value as $key_value) {
			unset($obj[$key_value]);
		}
	}

	protected function filterKeys(&$obj, array $keys=[], $clean=false) {
		$return = [];
		foreach($keys as $key) {
			if (isset($obj[$key])) {
				$return[$key] = $obj[$key];
				if ($clean) unset($obj[$key]);
			}
		}
		return $return;
	}

	protected function disableError() {
		ini_set('display_errors', 'Off');
		ini_set('log_errors', 1);
		ini_set('error_reporting', 0);
		ini_set('display_startup_errors', 'Off');
	}

	protected function checkOldVersion() {
		return ( preg_match('/^2\.0\.\d+/', VERSION) ) ? true : false;
	}

    protected function getArrLang() {

		$return = [];
		foreach ((array)$this->db->query('SELECT l.language_id FROM '.DB_PREFIX.'language l')->rows as $value) {
			$return[] = $value["language_id"];
		}
		return $return;
	}

	private function checkAuthVersion3()
	{
		$remote = $this->request->server['REMOTE_ADDR'];

		if (isset($this->request->get['token'])) {
			$token = $this->request->get['token'];
		} else {
			return false;
		}

		$qToken = (array)$this->db->query("SELECT * FROM " . DB_PREFIX . "api_session WHERE session_id= '" . $token . "' AND ip='" . $remote . "'");

		if (!empty($qToken['row']))
			return true;
		else
			return false;
	}

	protected function checkToken() {
		if ( preg_match('/^3\.\d\.\d+/', VERSION))
			return $this->checkAuthVersion3();

		if ( preg_match('/^2\.0\.\d+/', VERSION))
			return (isset($this->session->data['api_id'])) ? true : false;

		if (isset($this->request->get['token']))
			$token = $this->request->get['token'];
		else return false;

		$remote = $this->request->server['REMOTE_ADDR'];
		$qToken = (array)$this->db->query("SELECT * FROM " . DB_PREFIX . "api_session WHERE token= '" . $token . "' AND ip='" . $remote . "'");

		if (!empty($qToken['row']))
			return true;
		else
			return false;

	}
}
