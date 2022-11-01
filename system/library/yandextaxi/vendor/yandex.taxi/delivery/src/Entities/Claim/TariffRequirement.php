<?php

namespace YandexTaxi\Delivery\Entities\Claim;

/**
 * Class TariffRequirement
 *
 * @package YandexTaxi\Delivery\Entities\Claim
 */
class TariffRequirement
{
    private const TYPE_SELECT = 'select';
    private const TYPE_MULTI_SELECT = 'multi_select';

    /** @var string */
    private $name;

    /** @var string */
    private $title;

    /** @var string */
    private $text;

    /** @var string */
    private $type;

    /** @var TariffRequirementOption[] */
    private $options;

    /** @var boolean */
    private $required;

    /**
     * TariffRequirement constructor.
     *
     * @param string                    $name
     * @param string                    $title
     * @param string                    $text
     * @param string                    $type
     * @param bool                      $required
     * @param TariffRequirementOption[] $options
     */
    public function __construct(
        string $name,
        string $title,
        string $text,
        string $type,
        bool $required,
        array $options = []
    ) {
        $this->name = $name;
        $this->title = $title;
        $this->text = $text;
        $this->type = $type;
        $this->required = $required;
        $this->options = $options;
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

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return TariffRequirementOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function isSelect(): bool
    {
        return $this->type === self::TYPE_SELECT;
    }

    public function isMultiSelect(): bool
    {
        return $this->type === self::TYPE_MULTI_SELECT;
    }
}
