<?php

class Ulogin {

    public $config = array(
        // Возможные значения: small, panel, window
        'type' => 'panel',
        // на какой адрес придёт POST-запрос от uLogin
        'redirect_uri' => NULL,
        // Сервисы, выводимые сразу
        'providers' => array(
            'vkontakte',
            'facebook',
            'twitter',
            'google',
        ),
        // Выводимые при наведении
        'hidden' => array(
            'odnoklassniki',
            'mailru',
            'livejournal',
            'openid'
        ),
        // Эти поля используются для значения поля username в таблице users
        'username' => array(
            'firstname',
            'lastname',
        ),
        // Обязательные поля
        'fields' => array(
            'first_name',
            'last_name',
            'email',
        ),
        // Необязательные поля
        'optional' => array(
            'last_name',
            'nickname',
            'bdate',
            'sex',
            'phone',
            'city',
            'country',
        ),
    );
    protected static $_used_id = array();

    public static function factory(array $config = array()) {
        return new Ulogin($config);
    }

    public function __construct(array $config = array()) {
        $this->config = array_merge($this->config, $config);
    }

    public function render() {
        $params = 'display=' . $this->config['type'] .
                '&fields=' . implode(',', array_merge($this->config['username'], $this->config['fields'])) .
                '&providers=' . implode(',', $this->config['providers']) .
                '&hidden=' . implode(',', $this->config['hidden']) .
                '&redirect_uri=' . $this->config['redirect_uri'] .
                '&optional=' . implode(',', $this->config['optional']);

        do {
            $uniq_id = "uLogin_" . rand();
        }
        while (in_array($uniq_id, self::$_used_id));

        self::$_used_id[] = $uniq_id;


        $html = '<script src="http://ulogin.ru/js/ulogin.js"></script>';
        if ($this->config['type'] == 'window') {

            $html.='<a id="' . $uniq_id . '" href="#" x-ulogin-params="' . $params . '">';
            $html.='<img src="http://ulogin.ru/img/button.png" width=187 height=30 alt="МультиВход"/>';
            $html.='</a>';
        }
        else {

            $html.='<div id="' . $uniq_id . '" x-ulogin-params="' . $params . '"></div>';
        }
        return $html;
    }

    public function __toString() {
        return $this->render();
    }
}
