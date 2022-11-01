<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
namespace LL;

/**
 * Ядро библиотеки
 */
class Core extends \Controller {
	protected $config;
	public $code;
	public $type;

	public function __construct($registry, $code, $type = false) {
		parent::__construct($registry);

		$this->config = $registry->get('config');
		$this->code = $code;
		$this->type = $type;
	}

	/**
	 * Получение краткой версии движка
	 * 
	 * @return string
	 */
	public function getVersion() {
		return mb_substr(VERSION, 0, 3);
	}

	/**
	 * Получение префикса расширения
	 *
	 * @param  string $code - Код расширения
	 * @param  string $type - Тип расширения
	 * @return string
	 */
	public function getPrefix($code = false, $type = false) {
		if (!$code) {
			$code = $this->code;
		}

		if (!$type) {
			$type = $this->type;
		}

		if (version_compare(VERSION, '3.0', '<') && $type != 'dashboard') {
			return $code;
		} else {
			return $type . '_' . $code;
		}
	}

	/**
	 * Получение измененного в версии 2.3 пути расширения
	 * 
	 * @return string
	 */
	public function getExt() {
		if (version_compare(VERSION, '2.3', '>=')) {
			return 'extension/';
		} else {
			return '';
		}
	}

	/**
	 * Получение значения настройки расширения
	 * 
	 * @param  string $variable - Название настройки
	 * @param  string|array $default - Значение настройки по умолчанию
	 * @return string|array
	 */
	public function getValue($variable, $default) {
		if (isset($this->request->post[$this->getPrefix() . '_' . $variable])) {
			return $this->request->post[$this->getPrefix() . '_' . $variable];
		} elseif ($this->config->has($this->getPrefix() . '_' . $variable)) {
			return $this->config->get($this->getPrefix() . '_' . $variable);
		} else {
			return $default;
		}
	}

	/**
	 * Получение токена
	 * 
	 * @return string
	 */
	public function getToken() {
		if (version_compare(VERSION, '3.0', '>=')) {
			return $this->session->data['user_token'];
		} else {
			return $this->session->data['token'];
		}
	}

	/**
	 * Получение хлебных крошек любой страницы расширения
	 * 
	 * @param  array|boolean $add - Массив дополнительных хлебных крошек для вложенных страниц расширения
	 * @return array
	 */
	public function getBreadcrumbs($add = false) {
		$breadcrumbs[] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->getLink('common/dashboard'),
		];

		$breadcrumbs[] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->getLinkExtensions(),
		];

		$breadcrumbs[] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->getLinkExtension(),
		];

		if (is_array($add)) {
			foreach ($add as $breadcrumb) {
				$breadcrumbs[] = [
					'text' => $breadcrumb['text'],
					'href' => $breadcrumb['href'],
				];
			}
		}

		return $breadcrumbs;
	}

	/**
	 * Получение успешного сообщения
	 * 
	 * @return string
	 */
	public function getSuccess() {
		$success = '';

		if (isset($this->session->data['success'])) {
			$success = $this->session->data['success'];

			unset($this->session->data['success']);
		}

		return $success;
	}

	/**
	 * Получение сообщения об ошибке
	 * 
	 * @return string
	 */
	public function getWarning() {
		$warning = '';

		if (isset($this->session->data['warning'])) {
			$warning = $this->session->data['warning'];

			unset($this->session->data['warning']);
		}

		return $warning;
	}

	/**
	 * Получение актуального роута
	 * 
	 * @param  string $path - Роут из актуальной версии движка. Если не задано, то возвращается роут текущего расширения
	 * @return string
	 */
	public function getRoute($path = false) {
		if ($path) {
			// добавить проверку роута к версии движка, по умолчанию используется из последней версии
			// возвращается согласно массива соответствий:
			// [актуальный роут из 3]:[старый роут 1 для opencart 2.3, старый роут 2 для opencart 2.0]
			return $path;
		} elseif (version_compare(VERSION, '2.3', '<')) {
			return $this->type . '/' . $this->code;
		} else {
			return 'extension/' . $this->type . '/' . $this->code;
		}
	}

	/**
	 * Получение актуального роута модели
	 * 
	 * @param  string $path - Роут модели из актуальной версии движка. Если не задано, то возвращается роут модели текущего расширения
	 * @return string
	 */
	public function getModel($path = false) {
		return 'model_' . str_replace('/', '_', $this->getRoute($path));
	}

	/**
	 * Очистка ссылок от &amp; для версий ниже 2.3
	 * 
	 * @param  string $route - Роут
	 * @param  string $params - Параметры
	 * @param  string $ssl
	 * @return string
	 */
	private function cleanUrl($route, $params, $ssl) {
		return str_replace('&amp;', '&', $this->url->link($route, $params, $ssl));
	}

	/**
	 * Получение ссылки на любую страницу админки
	 * 
	 * @param  string $route - Роут необходимой страницы
	 * @param  string $params - get параметры
	 * @return string
	 */
	public function getLink($route, $params = '') {
		if (version_compare(VERSION, '2.2', '<')) {
			return $this->cleanUrl($route, 'token=' . $this->getToken() . $params, 'SSL');
		} elseif (version_compare(VERSION, '3.0', '<')) {
			return $this->cleanUrl($route, 'token=' . $this->getToken() . $params, true);
		} else {
			return $this->cleanUrl($route, 'user_token=' . $this->getToken() . $params, true);
		}
	}

	/**
	 * Получение ссылки расширения
	 * 
	 * @param  string $add - Дополнение роута расширения вложенными страницами
	 * @param  string $params - get параметры
	 * @return string
	 */
	public function getLinkExtension($add = false, $params = '') {
		$route = $this->getRoute() . ($add ? '/' . $add : '');

		if (version_compare(VERSION, '2.2', '<')) {
			return $this->cleanUrl($route, 'token=' . $this->getToken() . $params, 'SSL');
		} elseif (version_compare(VERSION, '3.0', '<')) {
			return $this->cleanUrl($route, 'token=' . $this->getToken() . $params, true);
		} else {
			return $this->cleanUrl($route, 'user_token=' . $this->getToken() . $params, true);
		}
	}

	/**
	 * Получение ссылки на страницу всех расширений
	 * 
	 * @return string
	 */
	public function getLinkExtensions() {
		if (version_compare(VERSION, '2.2', '<')) {
			return $this->cleanUrl('extension/module', 'token=' . $this->getToken(), 'SSL');
		} elseif (version_compare(VERSION, '2.3', '<')) {
			return $this->cleanUrl('extension/module', 'token=' . $this->getToken(), true);
		} elseif (version_compare(VERSION, '3.0', '<')) {
			return $this->cleanUrl('extension/extension', 'token=' . $this->getToken() . '&type=' . $this->type, true);
		} else {
			return $this->cleanUrl('marketplace/extension', 'user_token=' . $this->getToken() . '&type=' . $this->type, true);
		}
	}

	/**
	 * Получение пути темплейта расширения
	 * 
	 * @param  string|boolen $add - Дополнение роута расширения вложенными страницами
	 * @return string
	 */
	public function getView($add = false) {
		$route = $this->getRoute() . ($add ? '_' . $add : '');

		if (version_compare(VERSION, '2.2', '<')) {
			return $route . '.tpl';
		} else {
			return $route;
		}
	}

	/**
	 * Получение пагинатора
	 *
	 * @param  string $total - Общее количество объектов
	 * @param  string $page - Текущая страница
	 * @param  string $url - Адрес страницы
	 * @return string
	 */
	public function getPagination($total, $page, $url) {
		$pagination = new \Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $url;

		return $pagination->render();
	}

	/**
	 * Получение текста пагинатора
	 *
	 * @param  string $total - Общее количество объектов
	 * @param  string $page - Текущая страница
	 * @param  string $text - Переводы
	 * @return string
	 */
	public function getPaginationText($total, $page, $text) {
		return sprintf($text, ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));
	}

	/**
	 * Получение актуального роута модели событий и расширения
	 * 
	 * @return string
	 */
	public function getExtensionModel() {
		if (version_compare(VERSION, '3.0', '<')) {
			return 'extension';
		} else {
			return 'setting';
		}
	}

	/**
	 * Функция валидации расширения
	 *
	 * По умолчанию проверяет основной роут расширения
	 * 
	 * @param  string $route - Если необходимо проверить конкретный роут
	 * @return boolen
	 */
	public function validate($route = '') {
		$error = [];

		if (!in_array($this->code, ['ll_lpost', 'll_lpost_exchange'])
			&& ((isset($this->request->post[$this->getPrefix() . '_license']) && isset($this->request->server['HTTP_HOST']) && base64_encode(hash_hmac('sha256',$this->request->server['HTTP_HOST'].$this->code,M_PI,true)) != $this->request->post[$this->getPrefix() . '_license'])
				|| (isset($this->request->post[$this->getPrefix() . '_license']) && isset($this->request->server['HTTP_HOST']) && base64_encode(hash_hmac('sha256',ltrim($this->request->server['HTTP_HOST'], 'www.').$this->code,'3.1415926535898',true)) != $this->request->post[$this->getPrefix() . '_license']))
		) {
			$error['warning'] = $this->language->get('error_license');
		}

		if (!$this->user->hasPermission('modify', ($route == '' ? $this->getRoute() : $route))) {
			$error['warning'] = $this->language->get('error_permission');
		}

		return !$error;
	}

	/**
	 * Получение доступных sms шлюзов
	 *
	 * @return array
	 */
	public function getSMSGates() {
		$statics = new \Config();
		$statics->load('ll');

		return $statics->get('sms');
	}

	/**
	 * Отправка SMS
	 * 
	 * @param  array $options
	 * @return array $result
	 */
	public function sms($options) {
		$statics = new \Config();
		$statics->load('ll');

		$gates = $statics->get('sms');

		if (isset($gates[$this->config->get($this->getPrefix() . '_sms_gate')])) {
			$gate = $gates[$this->config->get($this->getPrefix() . '_sms_gate')];

			foreach ($gate['options'] as $name => $option) {
				$params[$name] = isset($options[$option]) ? $options[$option] : $option;
			}

			$this->curl('sms', $params);
		}
	}

	/**
	 * Отправка Curl
	 * 
	 * @param  string $method - Название метода api
	 * @param  array  $params - Входные параметры
	 * @param  string $header - Content-Type запроса
	 * @return array  $result - Результат выполнения запроса
	 */
	public function curl($method = '', $params = [], $header = 'json', $post = true, $put = false) {
		switch ($header) {
			case 'json':
				$header = ['Content-Type: application/json'];
				break;
			case 'xml':
				$header = ['Content-Type: text/xml'];
				break;
			case 'form':
				$header = ['Content-Type: application/x-www-form-urlencoded'];
				break;
		}

		$ch = curl_init();

		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		$statics = new \Config();
		$statics->load($this->code);

		if ($method == 'sms') {
			$statics = new \Config();
			$statics->load('ll');

			$gates = $statics->get('sms');
			$gate = $gates[$this->config->get($this->getPrefix() . '_sms_gate')];
			$url = $gate['url'] . '?' . http_build_query($params);
			$params = [];
		} elseif ($this->config->get($this->getPrefix() . '_test')) {
			if ($this->getPrefix() == 'll_ozon_exchange' || $this->getPrefix() == 'module_ll_ozon_exchange') {
				if ($method == '' && $statics->get('api_auth_url_test') != null) {
					$url = $statics->get('api_auth_url_test');
					$params = http_build_query($params);
				} else {
					$url = $statics->get('api_test_url') . '/' . $method;
				}

				//if (!$post && !$put && !empty($params) && $statics->get('api_auth_url') === null) {
				if (!$post && !$put && !empty($params)) {
					$url .= '?' . http_build_query($params);
				}
			} else {
				$url = $statics->get('api_test_url') . '/' . $method;

				if (!$post && !empty($params)) {
					$url .= '?' . http_build_query($params);
				}
			}
		} else {
			if ($this->getPrefix() == 'll_ozon_exchange' || $this->getPrefix() == 'module_ll_ozon_exchange') {
				if ($method == '' && $statics->get('api_auth_url') != null) {
					$url = $statics->get('api_auth_url');
					$params = http_build_query($params);
				} else {
					$url = $statics->get('api_url') . '/' . $method;
				}

				if (!$post && !$put && !empty($params)) {
					$url .= '?' . http_build_query($params);
				}
			} else {
				$url = $statics->get('api_url') . '/' . $method;

				if (!$post && !empty($params)) {
					$url .= '?' . http_build_query($params);
				}
			}
		}

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);

		if ($post) {
			curl_setopt($ch, CURLOPT_POST, $post);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

		if ($put) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			
			if ($this->getPrefix() == 'll_ozon_exchange' || $this->getPrefix() == 'module_ll_ozon_exchange') {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			}
		}

		$result = curl_exec($ch);
		$info = curl_getinfo($ch);

		curl_close($ch);

		if ((!is_array($result) && ($this->getPrefix() == 'll_lpost_exchange' || $this->getPrefix() == 'module_ll_lpost_exchange' || $this->getPrefix() == 'll_ozon_exchange' || $this->getPrefix() == 'module_ll_ozon_exchange')) && $info['content_type'] != 'application/pdf') {
			$result = json_decode($result, true);
		}

		if ($method == 'sms') {
			$params = $url;
		}

		// cdek 
		if ($info['content_type'] == 'text/xml;charset=utf-8' ||
			$info['content_type'] == 'text/xml; charset=UTF-8' ||
			$info['content_type'] == 'application/xml;charset=UTF-8' ||
			$info['content_type'] == 'application/xml;charset=utf-8' ||
			$info['content_type'] == 'application/xml'
		) {
			$result = $this->parseXML($result);

			if (isset($result['Order'])) {
				foreach ($result['Order'] as $order) {
					if (isset($order['@attributes']['ErrorCode'])) {
						$errors[$order['@attributes']['ErrorCode']] = $order['@attributes']['Msg'];
					} elseif (isset($order['ErrorCode'])) {
						$errors[$order['ErrorCode']] = $order['Msg'];
					}
				}
			}

			if (isset($result['Call']['@attributes']['ErrorCode'])) {
				$errors[$result['Call']['@attributes']['ErrorCode']] = $result['Call']['@attributes']['Msg'];
			}

			if (isset($result['Call'][0]['@attributes']['ErrorCode'])) {
				$errors[$result['Call'][0]['@attributes']['ErrorCode']] = $result['Call'][0]['@attributes']['Msg'];
			}

			if (isset($result['alerts'])) {
				$errors[$result['alerts']['errorCode']] = $result['alerts']['msg'];
			}
		}

		if ($this->code == 'll_axilog_exchange') {
			$result = $this->parseXML($result);
		}

		// lpost
		if (isset($result['errorMessage']) && $result['errorMessage'] != '') {
			$errors[] = $result['errorMessage'];
		}

		// lpost
		if (isset($result['Message']) && $result['Message'] != '') {
			$errors[] = $result['Message'];
		}

		// lpost
		if (isset($result['JSON_TXT'])) {
			$result = json_decode($result['JSON_TXT'], true);
		}

		// ozon
		if (isset($result['message']) && $result['message'] != '') {
			$errors[] = $result['message'];

			if (isset($result['arguments']) && !empty($result['arguments'])) {
				foreach ($result['arguments'] as $arguments) {
					if (isset($arguments) && !empty($arguments)) {
						foreach ($arguments as $argument) {
							$errors[] = $argument;
						}
					}
				}
			}

			$errors = implode(' | ', $errors);
		}

		if ($info['http_code'] < 200 || $info['http_code'] >= 300) {
			$this->addLog(0, $method, $params, isset($errors) ? $errors : $info['http_code']);

			return $errors;
		} elseif (isset($errors)) {
			$this->addLog(0, $method, $params, $errors);

			return $errors;
		} else {
			$this->addLog(1, $method, $params, ($info['content_type'] == 'application/pdf' ? true : $result));

			return $result;
		}
	}

	public function parseXML($xml) {
		$json = json_encode(simplexml_load_string($xml));

		return json_decode($json, true);
	}

	public function getCache($method, $postfix = '', $ignore = false) {
		if ($this->config->get($this->getPrefix() . '_cache') || $ignore) {
			return $this->cache->get($this->code . '.' . $method . '.' . base64_encode($postfix));
		}
	}

	public function setCache($method, $postfix = '', $data, $ignore = false) {
		if ($this->config->get($this->getPrefix() . '_cache') || $ignore) {
			$this->cache->set($this->code . '.' . $method . '.' . base64_encode($postfix), $data);
		}
	}

	public function addLog($type, $method, $request, $response = [], $file = false) {
		if ($file) {
			$response = 'Result file loaded';
		}

		if ($this->config->get($this->getPrefix() . '_email') != '' && !empty($this->config->get($this->getPrefix() . '_notify'))) {
			foreach (explode(',', $this->config->get($this->getPrefix() . '_email')) as $email) {
				if ($email != '' && in_array($type, $this->config->get($this->getPrefix() . '_notify'))) {
					$this->notifyLog($email, $type, $method, $request, $response);
				}
			}
		}

		if ($this->config->get($this->getPrefix() . '_logging')) {
			$module = new \Config();
			$module->load($this->code);

			$statics = new \Config();
			$statics->load('ll');

			$types = $statics->get('log');

			$log = new \Log($this->code . '.log');

			$log->write('[' . $types[$type]['code'] . '] Версия модуля: ' . $module->get('version'));
			$log->write('[' . $types[$type]['code'] . '] Версия движка: ' . VERSION);
			$log->write('[' . $types[$type]['code'] . '] Статус запроса: ' . $types[$type]['title']);
			$log->write('[' . $types[$type]['code'] . '] Метод api: ' . $method);
			$log->write('[' . $types[$type]['code'] . '] Параметры запроса:');
			$log->write($request);
			$log->write('[' . $types[$type]['code'] . '] Ответ сервера api:');
			$log->write($response);
			$log->write('------------------------------');
		}
	}

	public function notifyLog($email, $type, $method, $request, $response) {
		$this->load->language('extension/shipping/' . $this->code);

		$subject = $this->language->get('text_subject_' . $type);

		$message = $this->language->get('text_method');
		$message .= $method;
		$message .= $this->language->get('text_request');

		foreach ($request as $key => $val) {
			if (is_array($val)) {
				foreach ($val[0] as $k => $v) {
					$message .= $k . ': ' . $v . '<br>';
				}
			} else {
				$message .= $key . ': ' . $val . '<br>';
			}
		}

		$message .= $this->language->get('text_response');

		if (isset($response)) {
			if (empty($response)) {
				$message .= $this->language->get('text_not_found');
			} elseif (is_array($response)) {
				foreach ($response as $key => $val) {
					if (is_array($val)) {
						foreach ($val as $k => $v) {
							if (is_array($v)) {
								$message .= '<b>' . $k . '</b><br>';

								foreach ($v as $kk => $vv) {
									if (is_array($vv)) {
										if (is_array($kk)) {
											$message .= '<b>' . array_key_first($kk) . '</b><br>';
										} else {
											$message .= '<b>' . $kk . '</b><br>';
										}

										foreach ($vv as $kkk => $vvv) {
											$message .= $kkk . ': ' . $vvv . '<br>';
										}
									} else {
										$message .= $kk . ': ' . $vv . '<br>';
									}
								}
							} else {
								$message .= $k . ': ' . $v . '<br>';
							}
						}
					} else {
						$message .= $key . ': ' . $val . '<br>';
					}
				}
			} else {
				$message .= $response;
			}
		} else {
			$message .= $this->language->get('text_response_null');
		}

		$this->sendMail($email, $subject, $message);
	}

	/**
	 * Отправка email
	 * 
	 * @param  string $email - Email
	 * @param  string $subject - Заголовок письма
	 * @return string $message - Текст письма
	 */
	public function sendMail($email, $subject, $message) {
		$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">' . "\n";
		$html .= '<html>' . "\n";
		$html .= '  <head>' . "\n";
		$html .= '    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
		$html .= '    <title>' . $subject . '</title>' . "\n";
		$html .= '  </head>' . "\n";
		$html .= '  <body>' . $message . '</body>' . "\n";
		$html .= '</html>' . "\n";

		$mail = new \Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($html);
		$mail->send();
	}
}
