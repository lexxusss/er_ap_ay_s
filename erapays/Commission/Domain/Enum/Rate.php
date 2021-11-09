<?php

declare(strict_types=1);

namespace EraPays\Commission\Domain\Enum;

class Rate
{
    private ?float $value;

    public function __construct(?float $value)
    {
        $this->value = $value;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }
}
