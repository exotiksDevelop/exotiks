<?php

namespace YandexTaxi\Utils;

use YandexTaxi\Library;

/**
 * @property \Url $url
 * @property \Language $language
 * @property \Response $response
 * @property \Loader $load
 * @property \Session $session
 * @property \Request $request
 *
 * Class Responds
 */
class Responds extends Library {
    /**
     * @param \Registry $registry
     */
	public function __construct($registry) {
		parent::__construct($registry);

		$this->load->language('extension/shipping/yandextaxi');
	}

    /**
     * @param array $data
     */
    public function json(array $data) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function empty() {
        return $this->json([]);
    }

    /**
     * @param string $template
     * @param array  $data
     *
     * @return mixed
     */
	public function view(string $template, array $data = []) {
		$token = $this->session->data['user_token'];

		if (!empty($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		if (!empty($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$output = $this->output($template, array_merge(
			[
				'header' => $this->load->controller('common/header'),
				'footer' => $this->load->controller('common/footer'),
				'column_left' => $this->load->controller('common/column_left'),
				'breadcrumbs' => [
					[
						'text' => $this->language->get('text_home'),
						'href' => $this->url->link('common/dashboard', "user_token=$token", true)
					],
					[
						'text' => $this->language->get('text_extension'),
						'href' => $this->url->link('marketplace/extension', "user_token=$token", true)
					],
					[
						'text' => $this->language->get('heading_title'),
						'href' => $this->url->link('extension/shipping/yandextaxi', "user_token=$token", true)
					]
				],
				'cancel' => $this->url->link('marketplace/extension', "user_token=$token", true),
			], $data));

		$this->response->setOutput($output);
	}

	public function isPost(): bool {
		return $this->request->server['REQUEST_METHOD'] === 'POST';
	}

	public function output(string $template, array $data = []): string {
		return $this->load->view("extension/shipping/$template", $data);
	}
}
