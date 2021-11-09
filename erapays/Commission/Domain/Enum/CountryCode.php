<?php

declare(strict_types=1);

namespace EraPays\Commission\Domain\Enum;

class CountryCode implements EuroAble
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEuro(): bool
    {
        $code = EuroCountryCode::memberByValueWithDefault($this->value, null);

        return $code instanceof EuroCountryCode;
    }
}
