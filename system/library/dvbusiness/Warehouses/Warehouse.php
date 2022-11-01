<?php

namespace DvBusiness\Warehouses;

class Warehouse
{
    /** @var int|null */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $city;

    /** @var string */
    public $address;

    /** @var string */
    public $workStartTime = '08:00';

    /** @var string */
    public $workFinishTime = '20:00';

    /** @var string */
    public $contactName;

    /** @var string */
    public $contactPhone;

    /** @var string */
    public $note;

    /** @var string */
    public $workdays = '';

    public function getWorkdays(): array
    {
        return $this->workdays
            ? Workdays::unserializeWorkdays($this->workdays)
            : [1,2,3,4,5,6,7];
    }

    public function setWorkdays(array $workdays): Warehouse
    {
        $this->workdays = Workdays::serializeWorkdays($workdays);
        return $this;
    }

    public function getNearestWorkDate(string $date = null): string
    {
        if ($date === null) {
            $date = date('c');
        }

        $time        = strtotime($date);
        $todayOfWeek = date('N', $time);
        $workdays    = $this->getWorkdays();

        $diffDays = null;
        foreach ($workdays as $workday) {
            if ($workday >= $todayOfWeek) {
                $diffDays = $workday - $todayOfWeek;
                break;
            }
        }

        if ($diffDays === null) {
            $nearestDayOfWeek = reset($workdays);
            $diffDays = 7 + $nearestDayOfWeek - $todayOfWeek;
        }

        return date('c', strtotime("+{$diffDays} days", $time));
    }

    public function getFullAddress(): string
    {
        if ($this->address && $this->city && strpos($this->address, $this->city) === false) {
            return $this->city . ', ' . $this->address;
        } else {
            return $this->address;
        }
    }
}
