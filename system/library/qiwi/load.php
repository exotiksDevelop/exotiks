<?php

if (!defined('DIR_SYSTEM')) {
    exit;
}

if (!defined('CLIENT_NAME')) {
    /**
     * The client name fingerprint.
     *
     * @const string
     */
    define('CLIENT_NAME', 'opencart');
}

if (!defined('CLIENT_VERSION')) {
    /**
     * The client version fingerprint.
     *
     * @const string
     */
    define('CLIENT_VERSION', '0.0.2');
}

if (!defined('DIR_QIWI_LIB')) {
    define('DIR_QIWI_LIB', DIR_SYSTEM . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'qiwi' . DIRECTORY_SEPARATOR);
}

// Autoload for standalone composer build.
if (!class_exists('Curl\Curl')) {
    require_once DIR_QIWI_LIB . 'vendor' . DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR . 'curl' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Curl' . DIRECTORY_SEPARATOR . 'Curl.php';
}

if (!class_exists('Qiwi\Api\BillPaymentsException')) {
    require_once DIR_QIWI_LIB . 'vendor' . DIRECTORY_SEPARATOR . 'qiwi' . DIRECTORY_SEPARATOR . 'bill-payments-php-sdk' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'BillPaymentsException.php';
}

if (!class_exists('Qiwi\Api\BillPayments')) {
    require_once DIR_QIWI_LIB . 'vendor' . DIRECTORY_SEPARATOR . 'qiwi' . DIRECTORY_SEPARATOR . 'bill-payments-php-sdk' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'BillPayments.php';
}

if (!interface_exists('Qiwi\Admin\Controller')) {
    require_once DIR_QIWI_LIB . 'src' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'Controller.php';
}

if (!interface_exists('Qiwi\Admin\Model')) {
    require_once DIR_QIWI_LIB . 'src' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'Model.php';
}

if (!interface_exists('Qiwi\Catalog\Controller')) {
    require_once DIR_QIWI_LIB . 'src' . DIRECTORY_SEPARATOR . 'catalog' . DIRECTORY_SEPARATOR . 'Controller.php';
}

if (!interface_exists('Qiwi\Catalog\Model')) {
    require_once DIR_QIWI_LIB . 'src' . DIRECTORY_SEPARATOR . 'catalog' . DIRECTORY_SEPARATOR . 'Model.php';
}

if (!interface_exists('Qiwi\Client')) {
    require_once DIR_QIWI_LIB . 'src' . DIRECTORY_SEPARATOR . 'Client.php';
}
