<?php declare(strict_types=1);

namespace EraPays\Infrastructure\Enum;

use Eloquent\Enumeration\EnumerationInterface;
use Eloquent\Enumeration\AbstractEnumeration as AbstractEnumerationEloquent;

abstract class AbstractEnumeration extends AbstractEnumerationEloquent
{
    public function equals(EnumerationInterface $enumeration): bool
    {
        if (get_class($this) !== get_class($enumeration)) {
            return false;
        }

        return $this->value() === $enumeration->value();
    }
}
