<?php

namespace DvBusiness\Warehouses;

class Workdays
{
    public static function serializeWorkdays(array $workdays): string
    {
        return join(',', $workdays);
    }

    public static function unserializeWorkdays(string $workdays): array
    {
        return array_map('intval', explode(',', $workdays));
    }
}
