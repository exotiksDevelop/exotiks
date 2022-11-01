<?php

namespace YandexTaxi\Services;

use YandexTaxi\Entities\Warehouse\Warehouse;
use YandexTaxi\Repositories\WarehouseRepository;

/**
 * Class DefaultWarehouseFinder
 *
 * @package YandexTaxi\Services
 */
class DefaultWarehouseFinder
{
    /** @var SettingService */
    private $settingService;

    /** @var WarehouseRepository */
    private $warehouseRepository;

    public function __construct(SettingService $settingService, WarehouseRepository $warehouseRepository)
    {
        $this->settingService = $settingService;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function find(): ?Warehouse
    {
        $defaultWarehouseId = $this->settingService->getOne('shipping_yandextaxi_default_warehouse_id');

        return !empty($defaultWarehouseId) ? $this->warehouseRepository->get($defaultWarehouseId) : null;
    }
}
