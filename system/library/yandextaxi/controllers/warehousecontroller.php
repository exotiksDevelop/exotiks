<?php

namespace YandexTaxi\Controllers;

use \Request;
use \Response;
use \Language;
use \Config;
use \Session;
use \Url;
use YandexTaxi\Utils\Responds;
use YandexTaxi\Delivery\YandexApi\Exceptions\NotAuthorizedException;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;
use YandexTaxi\Entities\Warehouse\Warehouse;
use YandexTaxi\Repositories\WarehouseRepository;
use YandexTaxi\Services\AvailableTariffChecker;
use YandexTaxi\Services\SettingService;
use YandexTaxi\Services\Constants;

/**
 * Class WarehouseController
 *
 * @package YandexTaxi\Controllers
 */
class WarehouseController
{
    /** @var Language */
    private $languages;

    /** @var Session */
    private $session;

    /** @var Url */
    private $url;

    /** @var Responds */
    private $responds;

    /** @var Config */
    private $config;

    /** @var WarehouseRepository */
    private $warehouseRepository;

    /** @var SettingService */
    private $settingService;

    /** @var Tariffs|null */
    private $tariffs;

    /**
     * WarehouseController constructor.
     *
     * @param Language            $languages
     * @param Session             $session
     * @param Url                 $url
     * @param Responds            $responds
     * @param Config              $config
     * @param WarehouseRepository $warehouseRepository
     * @param SettingService      $settingService
     * @param Tariffs|null        $tariffs
     */
    public function __construct(
        Language $languages,
        Session $session,
        Url $url,
        Responds $responds,
        Config $config,
        WarehouseRepository $warehouseRepository,
        SettingService $settingService,
        ?Tariffs $tariffs
    ) {
        $this->languages = $languages;
        $this->session = $session;
        $this->url = $url;
        $this->responds = $responds;
        $this->config = $config;
        $this->warehouseRepository = $warehouseRepository;
        $this->settingService = $settingService;
        $this->tariffs = $tariffs;
    }

    public function index() {
        $token = $this->session->data['user_token'];

        $this->responds->view('yandextaxi_warehouses_index', [
            'user_token' => $token,
            'default_id' => $this->settingService->getOne('shipping_yandextaxi_default_warehouse_id'),
            'warehouses' => $this->warehouseRepository->all(),
            'createUrl' => $this->url->link('extension/shipping/yandextaxi/editWarehouse', 'user_token=' . $token, true),
            'support_contact' => $this->responds->output('partial/_support_contact', ['plugin_version' => Constants::VERSION]),
            'breadcrumbs' => $this->getIndexBreadcrumbs(),
        ]);
    }

    public function edit(Request $request, Response $response) {
        $id = $request->get['id'] ?? null;

        if (is_null($id) && $this->responds->isPost()) {
            $id = $request->post['id'] ?? null;
        }

        $token = $this->session->data['user_token'];
        $message = '';

        if (empty($id)) {
            $warehouse = new Warehouse();
        } else {
            $warehouse = $this->warehouseRepository->get($id);
            if (empty($warehouse)) {
                echo $this->languages->get('warehouse_not_found') . PHP_EOL;
                return;
            }
        }

        $breadcrumbs = $this->getIndexBreadcrumbs();

        $breadcrumbs[] = [
            'text' => $this->languages->get('heading_warehouses_edit'),
            'href' => $this->url->link('extension/shipping/yandextaxi/editWarehouse&id=' . $id, 'user_token=' . $token, true),
        ];

        if (!$this->responds->isPost()) {
            //creation
            $this->responds->view('yandextaxi_warehouses_edit', [
                'isDefault' => $this->settingService->getOne('shipping_yandextaxi_default_warehouse_id') == $warehouse->getId(),
                'warehouse' => $warehouse,
                'geo_coder_token' => $this->settingService->getOne('shipping_yandextaxi_geo_coder_api_token'),
                'hours' => $this->getHoursRange(),
                'base_url' => $this->getBaseUrl($request),
                'message' => null,
                'support_contact' => $this->responds->output('partial/_support_contact', ['plugin_version' => Constants::VERSION]),
                'cabinet_modal' => $this->responds->output('partial/_create_cabinet_modal'),
                'translations_map' => $this->responds->output('translations/_map'),
                'translations_validation' => $this->responds->output('translations/_validation'),
                'breadcrumbs' => $breadcrumbs,
                'settings_url' => $this->url->link('extension/shipping/yandextaxi', 'user_token=' . $token, true),
            ]);
            return;
        }

        $lat = (float)$request->post['lat'];
        $lon = (float)$request->post['lon'];
        // edit
        $warehouse->setAddress(filter_var($request->post['address'], FILTER_SANITIZE_STRING));
        $warehouse->setLat($lat);
        $warehouse->setLon($lon);

        $warehouse->setContactName(filter_var($request->post['name'], FILTER_SANITIZE_STRING));
        $warehouse->setContactPhone(filter_var($request->post['phone'], FILTER_SANITIZE_STRING));
        $warehouse->setContactEmail(filter_var($request->post['email'], FILTER_SANITIZE_EMAIL));

        $warehouse->setStartTime(filter_var($request->post['start_time'], FILTER_SANITIZE_STRING));
        $warehouse->setEndTime(filter_var($request->post['end_time'], FILTER_SANITIZE_STRING));

        $warehouse->setComment(filter_var($request->post['comment'], FILTER_SANITIZE_STRING));
        $warehouse->setFlat(filter_var($request->post['flat'], FILTER_SANITIZE_STRING));
        $warehouse->setPorch(filter_var($request->post['porch'], FILTER_SANITIZE_STRING));
        $warehouse->setFloor(filter_var($request->post['floor'], FILTER_SANITIZE_STRING));

        if ($warehouse->isValid()) {
            $this->warehouseRepository->store($warehouse);

            if (isset($request->post['is_default']) && $request->post['is_default'] == 'on') {
                $this->markWarehouseAsDefault($warehouse);
            }

            $count = count($this->warehouseRepository->all());
            if ($count === 1) {
                $this->markWarehouseAsDefault($warehouse);
            }

            if (!empty($this->tariffs)) {
                try {
                    if (!(new AvailableTariffChecker($this->tariffs))->isAvailable($lat, $lon)) {
                        $message = $this->responds->output('partial/_no_tariffs', []);
                    }
                } catch (NotAuthorizedException $exception) {
                    $message = $this->responds->output('partial/_bad_token');
                } catch (YandexApiException $exception) {
                    $message = $this->responds->output('partial/_error', ['message' => $exception->getMessage()]);
                }
            }
        } else {
            $message = $this->responds->output('partial/_error', ['message' => $this->languages->get('warehouse_is_not_valid')]);
        }

        if (empty($message)) {
            $response->redirect(
                $this->url->link('extension/shipping/yandextaxi/indexWarehouses', "user_token=$token", true)
            );
        }

        $this->responds->view('yandextaxi_warehouses_edit', [
            'isDefault' => $this->settingService->getOne('shipping_yandextaxi_default_warehouse_id') == $warehouse->getId(),
            'title' => $this->languages->get('heading_warehouses_edit'),
            'warehouse' => $warehouse,
            'geo_coder_token' => $this->settingService->getOne('shipping_yandextaxi_geo_coder_api_token'),
            'hours' => $this->getHoursRange(),
            'base_url' => $this->getBaseUrl($request),
            'message' => $message,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function delete(Request $request): void {
        $id = $request->post['id'];

        if (empty($id)) {
            return;
        }

        $this->warehouseRepository->deleteByPk($id);

        // mark default - last warehouse
        $all = $this->warehouseRepository->all();

        if (count($all) === 1) {
            $this->markWarehouseAsDefault($all[0]);
        }

        if ($this->settingService->getOne('shipping_yandextaxi_default_warehouse_id') === $id && (count($all) > 0)) {
            $this->markWarehouseAsDefault($all[0]);
        }
    }

    private function markWarehouseAsDefault(Warehouse $warehouse): void {
        $this->settingService->storeOne('shipping_yandextaxi_default_warehouse_id', $warehouse->getId());
    }

    /**
     * @return string[]
     */
    private function getHoursRange(): array {
        return array_map(function ($hour) {
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        }, range(0, 23));
    }

    private function getBaseUrl(Request $request): ?string {
        if (isset($request->server['HTTPS'])
            && (($request->server['HTTPS'] == 'on') || ($request->server['HTTPS'] == '1'))) {

            return $this->config->get('config_ssl');
        }

        return $this->config->get('config_url');
    }

    private function getIndexBreadcrumbs(): array {
        $breadcrumbs[] = [
            'text' => $this->languages->get('text_home'),
            'href' => $this->url->link('common/dashboard', "user_token={$this->getToken()}", true),
        ];

        $breadcrumbs[] = [
            'text' => $this->languages->get('heading_title'),
            'href' => $this->url->link('extension/shipping/yandextaxi', "user_token={$this->getToken()}", true),
        ];

        $breadcrumbs[] = [
            'text' => $this->languages->get('heading_warehouses_index'),
            'href' => $this->url->link('extension/shipping/yandextaxi/indexWarehouses', "user_token={$this->getToken()}", true),
        ];

        return $breadcrumbs;
    }

    private function getToken(): string {
        return $this->session->data['user_token'];
    }
}
