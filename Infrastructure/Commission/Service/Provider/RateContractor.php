<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use EraPays\Commission\Application\Exception\InvalidResponseException;
use EraPays\Commission\Application\Exception\RateProviderException;
use EraPays\Commission\Application\Service\CacheProviderInterface;
use EraPays\Commission\Application\Service\RateProviderInterface;
use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Commission\Domain\Enum\Rate;

class RateContractor implements RateContract
{
    private CacheProviderInterface $cacheProvider;
    private RateProviderInterface $rateProvider;

    public function __construct(
        CacheProviderInterface $cacheProvider,
        RateProviderInterface $rateProvider
    ) {
        $this->cacheProvider = $cacheProvider;
        $this->rateProvider = $rateProvider;
    }

    public function getRate(Currency $currency): Rate
    {
        $rate = $this->cacheProvider->getRate($currency);

        if (null === $rate) {
            try {
                $rate = $this->rateProvider->getRate($currency);
            } catch (InvalidResponseException $e) {
                throw new RateProviderException($e->getMessage());
            }

            $this->cacheProvider->setRate($currency, $rate);
        }

        return new Rate($rate);
    }
}
