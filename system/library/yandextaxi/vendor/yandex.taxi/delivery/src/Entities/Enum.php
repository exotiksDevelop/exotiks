<?php

namespace YandexTaxi\Delivery\Entities;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use BadMethodCallException;
use ReflectionClass;
use RuntimeException;
use UnexpectedValueException;

/**
 * Class Enum
 *
 * @package YandexTaxi\Delivery\Entities
 */
abstract class Enum
{
    /** @var array */
    private static $constantsCache = [];

    /** @var ReflectionClass[] */
    private static $reflectionClassCache = [];

    /** @var mixed */
    private $value;

    /**
     * @return Enum[]
     */
    public static function values(): array
    {
        self::checkNotAbstract();

        return array_values(
            array_map(function (string $value) {
                return self::fromCode($value);
            }, self::getConstants())
        );
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Enum
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        self::checkNotAbstract();

        $array = self::getConstants();
        $key = self::fromCamelCaseToSnakeUppercase($name);

        if (isset($array[$key])) {
            return self::fromCode($array[$key]);
        }

        throw new BadMethodCallException("No static method [$name] in class " . static::class);
    }

    public static function fromCode(string $code): self
    {
        self::checkNotAbstract();

        return new static($code);
    }

    public static function isValid($value): bool
    {
        self::checkNotAbstract();

        return in_array($value, self::getConstants(), true);
    }

    private static function checkNotAbstract(): void
    {
        if (self::getReflection()->isAbstract()) {
            throw new RuntimeException('Unable to call method of an abstract class.');
        }
    }

    private static function getConstants(): array
    {
        $class = static::class;
        if (!isset(self::$constantsCache[$class])) {
            self::$constantsCache[$class] = self::getReflection()->getConstants();
        }

        return self::$constantsCache[$class];
    }

    /**
     * @return ReflectionClass
     */
    private static function getReflection(): ReflectionClass
    {
        if (!isset(self::$reflectionClassCache[static::class])) {
            self::$reflectionClassCache[static::class] = new ReflectionClass(static::class);
        }

        return self::$reflectionClassCache[static::class];
    }

    private static function fromCamelCaseToSnakeUppercase(string $value): string
    {
        return strtoupper(preg_replace('/(?<!\p{Lu})\p{Lu}/u', '_\0', $value));
    }

    public function getValue()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equals(Enum $enum): bool
    {
        return $this->getValue() === $enum->getValue() && get_class($enum) === static::class;
    }

    /**
     * Enum constructor.
     *
     * @param mixed $value
     *
     * @throws UnexpectedValueException
     */
    protected function __construct($value)
    {
        if (!self::isValid($value)) {
            throw new UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
        }

        $this->value = $value;
    }
}
