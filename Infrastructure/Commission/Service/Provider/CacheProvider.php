<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use DateInterval;
use EraPays\Commission\Application\Service\CacheProviderInterface;
use EraPays\Commission\Domain\Enum\Currency;
use Illuminate\Cache\Repository;

class CacheProvider implements CacheProviderInterface
{
    private const COUNTRY_PREFIX_KEY = 'country.code.';
    private const RATE_PREFIX_KEY = 'currency.rate.';

    private Repository $storage;
    private string $countryTTL;
    private string $rateTTL;

    public function __construct($storage, string $countryTTL, string $rateTTL)
    {
        $this->storage = $storage;
        $this->countryTTL = $countryTTL;
        $this->rateTTL = $rateTTL;
    }

    public function getCountryCode(int $bin): ?string
    {
        return $this->storage->get(self::COUNTRY_PREFIX_KEY . $bin);
    }

    public function setCountryCode(int $bin, string $countryCode): void
    {
        $this->storage->set(self::COUNTRY_PREFIX_KEY . $bin, $countryCode, new DateInterval($this->countryTTL));
    }

    public function getRate(Currency $currency): ?float
    {
        return $this->storage->get(self::RATE_PREFIX_KEY . $currency->value());
    }

    public function setRate(Currency $currency, float $rate): void
    {
        $this->storage->set(self::RATE_PREFIX_KEY . $currency->value(), $rate, new DateInterval($this->rateTTL));
    }
}
