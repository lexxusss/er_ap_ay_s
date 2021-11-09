<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use EraPays\Commission\Domain\Enum\CountryCode;

interface CountryCodeContract
{
    public function getCountryCode(int $bin): CountryCode;
}
