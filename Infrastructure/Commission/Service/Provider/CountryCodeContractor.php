<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use EraPays\Commission\Application\Exception\BinProviderException;
use EraPays\Commission\Application\Exception\InvalidResponseException;
use EraPays\Commission\Application\Service\BinProviderInterface;
use EraPays\Commission\Application\Service\CacheProviderInterface;
use EraPays\Commission\Domain\Enum\CountryCode;

class CountryCodeContractor implements CountryCodeContract
{
    private CacheProviderInterface $cacheProvider;
    private BinProviderInterface $binProvider;

    public function __construct(
        CacheProviderInterface $cacheProvider,
        BinProviderInterface $binProvider
    ) {
        $this->cacheProvider = $cacheProvider;
        $this->binProvider = $binProvider;
    }

    public function getCountryCode(int $bin): CountryCode
    {
        $countryCode = $this->cacheProvider->getCountryCode($bin);

        if (null === $countryCode) {
            try {
                $countryCode = $this->binProvider->getCountryCode($bin);
            } catch (InvalidResponseException $e) {
                throw new BinProviderException($e->getMessage());
            }

            $this->cacheProvider->setCountryCode($bin, $countryCode);
        }

        return new CountryCode($countryCode);
    }
}
