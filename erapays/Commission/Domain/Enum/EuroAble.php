<?php

declare(strict_types=1);

namespace EraPays\Commission\Domain\Enum;

interface EuroAble
{
    public const EURO_CURRENCY = 'EUR';

    public function isEuro(): bool;
}
