<?php

declare(strict_types=1);

namespace App\Collection;

use App\Exceptions\InvalidViewTypeException;
use Illuminate\Support\Collection;

abstract class DedicatedCollection extends Collection
{
    private const OBJECT_TYPE = 'object';

    private array $allowedVarTypes;
    private array $allowedClassTypes;

    public function __construct($items = [])
    {
        $this->allowedVarTypes = [
            self::OBJECT_TYPE,
        ];
        $this->allowedClassTypes = $this->defineAllowedTypes();

        if (null !== ($k = key($items))) {
            $this->validateItem($items[$k]);
        }

        parent::__construct($items);
    }

    /**
     * Define a list of allowed object types for collection.
     * Define it as an empty array if no restrictions are needed.
     *
     * @return array
     */
    abstract public function defineAllowedTypes(): array;

    public function add($item)
    {
        $this->offsetSet(null, $item);
    }

    public function put($key, $item)
    {
        $this->offsetSet($key, $item);
    }

    public function offsetSet($key, $value)
    {
        $this->validateItem($value);
        parent::offsetSet($key, $value);
    }

    protected function validateItem($item): void
    {
        $actualType = gettype($item);
        if (!in_array($actualType, $this->allowedVarTypes)) {
            throw InvalidViewTypeException::create($actualType, $this->allowedVarTypes);
        }

        if (0 !== count($this->allowedClassTypes)) {
            $actualClass = get_class($item);
            if (!in_array($actualClass, $this->allowedClassTypes)) {
                throw InvalidViewTypeException::create($actualClass, $this->allowedClassTypes);
            }
        }
    }
}
