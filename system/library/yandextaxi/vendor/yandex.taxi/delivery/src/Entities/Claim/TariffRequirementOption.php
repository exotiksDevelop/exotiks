<?php

namespace YandexTaxi\Delivery\Entities\Claim;

/**
 * Class TariffRequirementOption
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class TariffRequirementOption
{
    /** @var string */
    private $title;

    /** @var string */
    private $text;

    /** @var string */
    private $value;

    public function __construct(string $title, string $text, string $value)
    {
        $this->title = $title;
        $this->text = $text;
        $this->value = $value;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
