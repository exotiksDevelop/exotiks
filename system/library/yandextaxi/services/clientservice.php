<?php

namespace YandexTaxi\Services;

use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\RoutePoint\Address;
use YandexTaxi\Delivery\Exceptions\ValidationError;
use YandexTaxi\Delivery\GeoCoding\GeoCoderInterface;
use YandexTaxi\Delivery\YandexApi\Resources\PriceChecker;
use YandexTaxi\Repositories\ProductRepository;

/**
 * Class ClientService
 *
 * @package YandexTaxi\Services
 */
class ClientService
{
    /** @var PriceChecker */
    private $priceChecker;

    /** @var GeoCoderInterface */
    private $geoCoder;

    /** @var DefaultWarehouseFinder */
    private $defaultWarehouseFinder;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(
        PriceChecker $priceChecker,
        GeoCoderInterface $geoCoder,
        DefaultWarehouseFinder $defaultWarehouseFinder,
        ProductRepository $productRepository
    ) {
        $this->priceChecker = $priceChecker;
        $this->geoCoder = $geoCoder;
        $this->defaultWarehouseFinder = $defaultWarehouseFinder;
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $products
     * @param array $address
     *
     * @return float|null
     */
    public function calculateSum(array $products, array $address): ?float
    {
        $items = $this->prepareItems($products);
        $source = $this->getSource();

        $destination = $this->prepareDestination($address);

        $checkPriceResult = $this->priceChecker->calculate($items, [$source, $destination]);

        return $checkPriceResult->getPrice()->getValue();
    }

    /**
     * @param array  $products
     *
     * @return ClaimItem[]
     */
    private function prepareItems(array $products): array
    {
        $ids = array_column($products, 'product_id');
        $fullProducts = $this->productRepository->findByIds($ids);

        return array_map(function (array $fullProduct) use ($products) {
            foreach ($products as $product) {
                if ($product['product_id'] === $fullProduct['product_id']) {
                    $fullProduct['quantity'] = $product['quantity'];
                    $fullProduct['name'] = $product['name'];
                }
            }

            return (new ProductToClaimItem())->convert($fullProduct);
        }, $fullProducts);
    }

    private function getSource(): Address
    {
        $warehouse = $this->defaultWarehouseFinder->find();

        if (is_null($warehouse)) {
            throw new ValidationError('Default warehouse was not found');
        }

        return new Address($warehouse->getAddress(), $warehouse->getLat(), $warehouse->getLon());
    }

    private function prepareDestination(array $address): Address
    {
        $addressLine = implode(', ', array_filter( array_map('trim', [
            $address['country'],
            $address['zone'],
            $address['address_1'],
            $address['address_2'],
        ])));

        if (empty($addressLine)) {
            throw new ValidationError('Address is not filled');
        }

        $point = $this->geoCoder->decode($addressLine);

        // TODO store in session?

        return new Address($addressLine, $point->getLat(), $point->getLon());
    }
}
