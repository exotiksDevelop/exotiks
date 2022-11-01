<?php

namespace YandexTaxi\Delivery\Entities\Claim;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Tariff
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class Tariff
{
    /** @var string */
    private $name;

    /** @var string */
    private $title;

    /** @var string */
    private $text;

    /** @var TariffRequirement[] */
    private $requirements;

    /**
     * Tariff constructor.
     *
     * @param string              $name
     * @param string              $title
     * @param string              $text
     * @param TariffRequirement[] $requirements
     */
    public function __construct(string $name, string $title, string $text, array $requirements)
    {
        $this->name = $name;
        $this->title = $title;
        $this->text = $text;
        $this->requirements = $requirements;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return TariffRequirement[]
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }
}
