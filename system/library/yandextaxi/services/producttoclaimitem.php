<?php

namespace YandexTaxi\Services;

use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\ClaimItem\Money;
use YandexTaxi\Delivery\Entities\ClaimItem\Size;

/**
 * Class ProductToClaimItem
 *
 * @package YandexTaxi\Services
 */
class ProductToClaimItem
{
    public function convert(array $product): ClaimItem
    {
        $dimensionCoefficient = $this->getDimensionCoefficient($product['length_unit']);
        $weightCoefficient = $this->getWeightCoefficient($product['weight_unit']);

        return new ClaimItem(
            $product['product_id'],
            "Product-{$product['product_id']}",
            $product['order_id'] ?? null,
            "{$product['name']} {$product['model']}",
            new Size(
                $product['width'] * $dimensionCoefficient,
                $product['length'] * $dimensionCoefficient,
                $product['height'] * $dimensionCoefficient
            ),
            new Money(
                $product['price'],
                'RUB'
            ),
            $product['weight'] * $weightCoefficient,
            $product['quantity']
        );
    }

    /**
     * Get multiplier to convert default dimension to required meters
     *
     * @param string $unit
     *
     * @return float
     */
    private function getDimensionCoefficient(string $unit): float {
        $unit = $this->preprareUnit($unit);
        switch ($unit) {
            case 'in':
                return 0.0254;
            case 'cm':
            case 'см':
                return 0.01;
            case 'mm':
                return 0.001;
            case 'm':
            case 'м':
            default:
                return 1; // unit not found send as it is
        }
    }

    /**
     * Get multiplier to convert default weight to required kgs
     *
     * @param string $unit
     *
     * @return float
     */
    private function getWeightCoefficient(string $unit): float {
        $unit = $this->preprareUnit($unit);
        switch ($unit) {
            case 'g':
            case 'г':
                return 0.001;
            case 'lb':
                return 0.453592;
            case 'oz':
                return 0.0283495;
            case 'kg':
            case 'кг':
            default:
                return 1; // unit not found send as it is
        }
    }

    private function preprareUnit(string $unit): string {
        return trim('.', mb_strtolower($unit));
    }
}
