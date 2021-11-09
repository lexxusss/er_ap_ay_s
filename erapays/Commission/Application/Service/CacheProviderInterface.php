<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

use EraPays\Commission\Domain\Enum\Currency;

interface CacheProviderInterface
{
    public function getCountryCode(int $bin): ?string;
    public function setCountryCode(int $bin, string $countryCode): void;
    public function getRate(Currency $currency): ?float;
    public function setRate(Currency $currency, float $rate): void;
}
