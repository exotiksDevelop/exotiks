<?php

namespace YandexTaxi\Delivery\Services;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use Exception;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\Claim\Tariff;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;
use YandexTaxi\Delivery\Entities\Claim\TariffRequirement;
use YandexTaxi\Delivery\Entities\Claim\TariffRequirementOption;

/**
 * Class TariffTextFinder
 *
 * @package YandexTaxi\Delivery\Services
 */
class TariffTextFinder
{
    /** @var Tariffs */
    private $tariffs;

    public function __construct(Tariffs $tariffs)
    {
        $this->tariffs = $tariffs;
    }

    public function find(Claim $claim): string
    {
        try {
            $tariffs = $this->tariffs->getAllForPoint(
                $claim->getSource()->getAddress()->getLat(),
                $claim->getSource()->getAddress()->getLon()
            );

            return $this->generateText($tariffs, $claim);
        } catch (Exception $exception) {
            // do nothing
        }

        return $claim->getTariffName();
    }

    /**
     * @param Tariff[] $tariffs
     * @param Claim    $claim
     *
     * @return string
     */
    private function generateText(array $tariffs, Claim $claim): string
    {
        $tariffName = $claim->getTariffName();
        $requirements = $claim->getClientRequirements();

        foreach ($tariffs as $tariff) {
            if ($tariff->getName() === $tariffName) {
                return $this->getTextForTariff($tariff, $requirements);
            }
        }

        return $tariffName;
    }

    private function getTextForTariff(Tariff $tariff, array $requirements): string
    {
        $text = $tariff->getTitle();

        $requirementTexts = $this->getRequirementsTexts($tariff->getRequirements(), $requirements);

        if (empty($requirementTexts)) {
            return $text;
        }

        return $text . ' (' . implode(',', $requirementTexts) . ')';
    }

    /**
     * @param TariffRequirement[] $tariffRequirements
     * @param array               $requirements
     *
     * @return string[]
     */
    private function getRequirementsTexts(array $tariffRequirements, array $requirements): array
    {
        if (empty($requirements) || empty($tariffRequirements)) {
            return [];
        }

        $texts = [];


        foreach ($tariffRequirements as $requirement) {
            if (isset($requirements[$requirement->getName()])) {
                $texts = array_merge(
                    $texts,
                    $this->getOptionTexts(
                        $requirement->getOptions(),
                        $requirements[$requirement->getName()]
                    )
                );
            }
        }
        return $texts;
    }

    /**
     * @param TariffRequirementOption[] $options
     * @param array|string              $selected
     *
     * @return string[]
     */
    private function getOptionTexts(array $options, $selected): array
    {
        if (is_array($selected)) {
            $texts = [];

            foreach ($options as $option) {
                if (in_array($option->getValue(), $selected)) {
                    $texts[] = $option->getTitle();
                }
            }

            return $texts;
        }

        foreach ($options as $option) {
            if ($selected === $option->getValue()) {
                return [$option->getTitle()];
            }
        }

        return [];
    }
}
