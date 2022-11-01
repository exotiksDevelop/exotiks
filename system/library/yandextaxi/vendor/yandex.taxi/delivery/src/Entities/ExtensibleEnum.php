<?php

namespace YandexTaxi\Delivery\Entities;

defined('YANDEX_GO_DELIVERY_CALLED_FROM_PLUGIN') || exit;

use BadMethodCallException;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;

/**
 * Class ExtensibleEnum
 *
 * @package YandexTaxi\Delivery\Entities
 */
abstract class ExtensibleEnum
{
    /** @var array */
    private static $constantsCache = [];

    /** @var ReflectionClass[] */
    private static $reflectionClassCache = [];

    /** @var string */
    private $value;

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return self
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        $array = self::getConstants();
        $key = self::fromCamelCaseToSnakeUppercase($name);

        if (isset($array[$key])) {
            return self::fromCode($array[$key]);
        }

        throw new BadMethodCallException("No static method [$name] in class " . static::class);
    }

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

    private static function checkNotAbstract(): void
    {
        if (self::getReflection()->isAbstract()) {
            throw new RuntimeException('Unable to call method of an abstract class.');
        }
    }

    public static function fromCode(string $code): self
    {
        return new static($code);
    }

    public function getCode(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $status): bool
    {
        return $this->getCode() === $status->getCode();
    }

    public function in(self ...$statuses): bool
    {
        foreach ($statuses as $status) {
            if ($this->equals($status)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public static function namesList(): array
    {
        return [];
    }

    public function getName(): string
    {
        if (isset(static::namesList()[$this->getCode()])) {
            return static::namesList()[$this->getCode()];
        }

        return $this->transformCodeToName($this->getCode());
    }

    protected function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Value cannot be empty');
        }

        $this->value = $value;
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

    private function transformCodeToName(string $code): string
    {
        return ucfirst(str_replace('_', ' ', $code));
    }
}

