<?php

namespace DvBusiness;

final class Autoloader
{
    public function __construct()
    {
        // PSR-4 autoloader DvBusiness Library
        spl_autoload_register(function($className) {
            $filteredClassName = str_replace('DvBusiness\\', '', $className);
            $baseDestination = str_replace('\\', DIRECTORY_SEPARATOR, $filteredClassName) . '.php';
            $fileDestination = __DIR__ . '/' . $baseDestination;
            if (is_file($fileDestination)) {
                require $fileDestination;
            }
        });
    }
}