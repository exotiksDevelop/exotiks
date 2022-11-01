<?php

namespace DvBusiness\OpenCart;

use Loader;
use ModelSettingExtension;

class PaymentMethods
{
    /** @var ModelSettingExtension */
    private $modelSettingExtension;

    /**
     * @param ModelSettingExtension $loader
     */
    public function __construct($modelSettingExtension)
    {
        $this->modelSettingExtension = $modelSettingExtension;

    }

    public function getEnum(): array
    {
        $enum = [];

        $results = $this->modelSettingExtension->getInstalled('payment');
        foreach ($results as $code) {
            $enum[$code] = $code;
        }

        return $enum;
    }
}
