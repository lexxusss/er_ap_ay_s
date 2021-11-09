<?php

declare(strict_types=1);

namespace EraPays\Commission\Domain\Enum;

use EraPays\Infrastructure\Enum\AbstractEnumeration;

/**
 * @method static EUR()
 * @method static USD()
 * @method static JPY()
 * @method static GBP()
 */
class Currency extends AbstractEnumeration implements EuroAble
{
    public const EUR = self::EURO_CURRENCY;
    public const USD = 'USD';
    public const JPY = 'JPY';
    public const GBP = 'GBP';

    public function isEuro(): bool
    {
        return $this->equals(static::EUR());
    }
}
