<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

use EraPays\Commission\Application\Exception\InvalidResponseException;
use EraPays\Commission\Domain\Enum\Currency;

interface RateProviderInterface
{
    /**
     * @param Currency $currency
     * @return float|null
     * @throws InvalidResponseException
     */
    public function getRate(Currency $currency): ?float;
}
