<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Commission\Domain\Enum\Rate;

interface RateContract
{
    public function getRate(Currency $currency): Rate;
}
