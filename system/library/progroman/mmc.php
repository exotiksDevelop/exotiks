<?php

namespace progroman;

class MMC {
    private $errors = [];
    private $key_path;
    private $lang;

    /** @var array Обязательные параметры для передачи на сервер */
    private $params = [];

    public function __construct($key_path, $lang) {
        $this->key_path = $key_path;
        $this->lang = $lang;
    }

    public function setParams($module, $version, $params = []) {
        $this->params = $params;
        $this->params['module'] = strtolower(str_replace(' ', '', $module ));
        $this->params['version'] = $version;
        $this->params['lang'] = $this->lang;

        $public_key  = openssl_get_publickey('file://' . $this->key_path);
        openssl_public_encrypt(parse_url(HTTPS_SERVER ? HTTPS_SERVER : HTTP_SERVER, PHP_URL_HOST), $crypt, $public_key);
        $this->params['http_server'] = base64_encode($crypt);

        return $this;
    }

    public function getSecretKey() {
        $response = $this->send(self::getProgromanServer() . '/api/licenses/get-secret-key');

        if (!($json = $this->parseResponse($response, $error))) {
            $this->setError($error);
            return false;
        }

        if (!$json->success) {
            $this->setError($json->message);
            return false;
        }

        return $json->data->secret_key;
    }

    /**
     * Загружает файл с сервера
     * @param $type
     * @param $dest
     * @return bool
     */
    public function downloadFile($type, $dest) {
        set_time_limit(0);
        $file = fopen($dest, 'w+');
        if (!$file) {
            $this->setError('Could not create file ' . $dest);
            return false;
        }

        $response = $this->send(self::getProgromanServer() . '/api/download/' . $type);

        if (!$response) {
            fclose($file);
            unlink($dest);
            return false;
        }

        fwrite($file, $response);
        fclose($file);

        return true;
    }

    private function send($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if (curl_errno($curl)) {
            $this->setError('CURL: ' . curl_error($curl));
            return false;
        }

        curl_close($curl);

        if ($response_code != 200) {
            $this->setError('Server response code ' . $response_code);

            if ($response && ($json = $this->parseResponse($response, $error))) {
                $this->setError($json->message);
            }

            return false;
        }

        if (empty($response)) {
            $this->setError('Server returned an empty result ');
            return false;
        }

        return $response;
    }

    private function setError($error) {
        $this->errors[] = $error;
    }

    public function getErrors() {
        return $this->errors;
    }

    static public function getProgromanServer() {
        return defined('PROGROMAN_DEV_MODE') ? 'http://mmc.loc' : 'http://mmc.progroman.ru';
    }

    static public function parseResponse($response, & $error) {
        if (empty($response)) {
            $error = 'Response is empty';
            return false;
        }

        $json = json_decode($response);
        if (json_last_error()) {
            $error = 'JSON: "' . json_last_error_msg() . '". Response: "' . $response . '"';
            return false;
        }

        return $json;
    }
}