<?php

namespace YandexTaxi\Controllers;

use \Pagination;
use \ModelSaleOrder;
use \Cart\Currency;
use \Language;
use \Config;
use \Session;
use \Url;
use \Request;
use \Response;
use YandexTaxi\Entities\Shipment\Shipment;
use YandexTaxi\Presenters\ShipmentPresenter;
use YandexTaxi\Utils\Responds;
use YandexTaxi\Services\OrderService;
use YandexTaxi\Services\ShipmentService;
use YandexTaxi\Services\Constants;

/**
 * Class OrderController
 *
 * @package YandexTaxi\Controllers
 */
class OrderController
{
    /** @var ModelSaleOrder */
    private $orders;

    /** @var Currency */
    private $currencies;

    /** @var Language */
    private $languages;

    /** @var Config */
    private $config;

    /** @var Session */
    private $session;

    /** @var Url */
    private $url;

    /** @var Responds */
    private $responds;

    /** @var OrderService */
    private $orderService;

    /** @var ShipmentService|null */
    private $shipmentService;

    /**
     * OrderController constructor.
     *
     * @param                      $orders
     * @param Currency             $currencies
     * @param Language             $languages
     * @param Config               $config
     * @param Session              $session
     * @param Url                  $url
     * @param Responds             $responds
     * @param OrderService         $orderService
     * @param ShipmentService|null $shipmentService
     */
    public function __construct(
        $orders,
        Currency $currencies,
        Language $languages,
        Config $config,
        Session $session,
        Url $url,
        Responds $responds,
        OrderService $orderService,
        ?ShipmentService $shipmentService
    ) {
        $this->orders = $orders;
        $this->currencies = $currencies;
        $this->languages = $languages;
        $this->config = $config;
        $this->session = $session;
        $this->url = $url;
        $this->responds = $responds;
        $this->orderService = $orderService;
        $this->shipmentService = $shipmentService;
    }

    public function index(Request $request): void {
        $page = $request->get['page'] ?? 1;
        $limit = $this->config->get('config_limit_admin');

        $filter = [
            'order' => 'DESC',
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
        ];

        $results = $this->orders->getOrders($filter);
        $orderTotal = $this->orders->getTotalOrders($filter);

        $pagination = new Pagination();
        $pagination->total = $orderTotal;
        $pagination->page = $page;
        $pagination->limit = $limit;

        $pagination->url = $this->url->link('extension/shipping/yandextaxi/indexOrders', 'user_token=' . $this->getToken() . '' . '&page={page}', true);

        $orderIds = array_column($results, 'order_id');

        $availableForShippingOrders = $this->orderService->getAvailableForShipping($orderIds);
        $availableForCancelOrders = $this->orderService->getAvailableForCancel($orderIds);

        $orders = [];

        foreach ($results as $result) {
            $id = $result['order_id'];
            $shipment = $this->getShipmentForOrder($id);

            $orders[] = [
                'id' => $id,
                'customer' => $result['customer'],
                'order_status' => $result['order_status'] ? $result['order_status'] : $this->languages->get('text_missing'),
                'address' => $this->getOrderAddress($id),
                'shipment_status' => !is_null($shipment) ? $this->languages->get('status_label_' . $shipment->getStatus()->getCode()) : null,
                'total' => $this->getTotal($result),
                'date_added' => date($this->languages->get('date_format_short'), strtotime($result['date_added'])),
                'can_ship_by_yandex_taxi_shipping' => in_array($id, $availableForShippingOrders),
                'can_cancel_yandex_taxi_shipping_order' => in_array($id, $availableForCancelOrders),
                'viewUrl' => $this->url->link('extension/shipping/yandextaxi/viewOrder&id=' . $id, 'user_token=' . $this->getToken(), true),
            ];
        }

        $this->responds->view('yandextaxi_orders_index', [
            'user_token' => $this->getToken(),
            'orders' => $orders,
            'pagination' => $pagination->render(),
            'support_contact' => $this->responds->output('partial/_support_contact', ['plugin_version' => Constants::VERSION]),
            'breadcrumbs' => $this->getIndexBreadcrumbs(),
        ]);
    }

    public function view(Request $request, Response $response): void {
        $id = $request->get['id'] ?? null;

        if (empty($id)) {
            $this->redirectToNotFound($response);
        }

        $id = (int) $id;

        $order = $this->getOrder($id);

        if (empty($order)) {
            $this->redirectToNotFound($response);
        }

        $breadcrumbs = $this->getIndexBreadcrumbs();

        $this->responds->view('yandextaxi_order_view', [
            'order' => [
                'id' => $order['order_id'],
                'customer' => implode(' ', array_filter([$order['firstname'], $order['lastname']])),
                'phone' => $order['telephone'],
                'address' => $this->getOrderAddress($id),
                'status' => $order['order_status'],
                'date_added' => date($this->languages->get('date_format_short'), strtotime($order['date_added'])),
                'total' => $this->getTotal($order),
                'viewSale' => $this->getOrderLink($id),
            ],
            'user_token' => $this->getToken(),
            'shipment_info' => $this->responds->output('partial/_order_shipping_info', [
                'shipment' => (new ShipmentPresenter($this->languages, $this->url, $this->getToken()))->present($this->getShipmentForOrder($id), $id),
            ]),
            'support_contact' => $this->responds->output('partial/_support_contact', ['plugin_version' => Constants::VERSION]),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    private function getShipmentForOrder(int $id): ?Shipment {
        return !is_null($this->shipmentService) ? $this->shipmentService->getForOrder($id) : null;
    }

    private function getToken(): string {
        return $this->session->data['user_token'];
    }

    private function getOrderAddress(int $id): string {
        $order = $this->getOrder($id);

        return "{$order['shipping_city']}, {$order['shipping_address_1']}";
    }

    private function getTotal(array $order): string {
       return $this->currencies->format($order['total'], $order['currency_code'], $order['currency_value']);
    }

    private function getOrder(int $id): array {
        return $this->orders->getOrder($id);
    }

    private function redirectToNotFound(Response $response): void {
        $response->redirect(
            $this->url->link('error', "user_token={$this->getToken()}", true)
        );
    }
    
    private function getOrderLink(int $id): string {
        return $this->url->link('sale/order/info&order_id=' . $id,"user_token={$this->getToken()}", true);
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
            'text' => $this->languages->get('heading_order_index'),
            'href' => $this->url->link('extension/shipping/yandextaxi/indexOrders', "user_token={$this->getToken()}", true),
        ];

        return $breadcrumbs;
    }
}
