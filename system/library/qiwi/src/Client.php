<?php

namespace Qiwi;

use Qiwi\Api\BillPayments;

/**
 * @inheritDoc
 */
class Client extends BillPayments
{
    /**
     * @inheritDoc
     */
    public function __construct($key = '', $options = [])
    {
        // Setup CURL options.
        $ca_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cacert.pem';
        if (is_file($ca_path)) {
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
            $options[CURLOPT_CAINFO] = $ca_path;
        }

        parent::__construct($key, $options);
    }
}
