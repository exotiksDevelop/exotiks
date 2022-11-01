<?php

namespace YooMoneyModule\Model;

class VersionCompatibilityHelper
{
    public static function getModulePrefix()
    {
        return version_compare(VERSION, '2.3.0') >= 0 ? 'extension/' : '';
    }
}