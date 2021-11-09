<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Provider;

use EraPays\Commission\Application\Service\BinProviderInterface;
use EraPays\Commission\Application\Service\CacheProviderInterface;
use EraPays\Commission\Application\Service\CountryCoefficientApplierInterface;
use EraPays\Commission\Application\Service\CommissionCalculatorInterface;
use EraPays\Commission\Application\Service\InputParserInterface;
use EraPays\Commission\Application\Service\MoneyConvertApplierInterface;
use EraPays\Commission\Application\Service\RateApplierInterface;
use EraPays\Commission\Application\Service\RateProviderInterface;
use EraPays\Commission\Application\Service\StorageServiceInterface;
use EraPays\Commission\Application\Service\StringServiceInterface;
use EraPays\Infrastructure\Commission\Service\Applier\CountryCoefficientApplier;
use EraPays\Infrastructure\Commission\Service\Applier\MoneyConvertApplier;
use EraPays\Infrastructure\Commission\Service\CommissionCalculator;
use EraPays\Infrastructure\Commission\Service\InputParser;
use EraPays\Infrastructure\Commission\Service\Provider\BinProvider;
use EraPays\Infrastructure\Commission\Service\Provider\CacheProvider;
use EraPays\Infrastructure\Commission\Service\Provider\RateProvider;
use EraPays\Infrastructure\Commission\Service\Applier\RateApplier;
use EraPays\Infrastructure\Commission\Service\StorageService;
use EraPays\Infrastructure\Commission\Service\StringService;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class CommissionServiceProvider extends ServiceProvider
{
    private const COMMISSION_APPLIERS = 'erapays.commission.appliers';

    public function boot()
    {
        $this->app->bind(StorageServiceInterface::class, StorageService::class);
        $this->app
            ->when(StorageService::class)
            ->needs('$storage')
            ->give(Storage::disk('transactions'));

        $this->app->bind(InputParserInterface::class, InputParser::class);
        $this->app->bind(StringServiceInterface::class, StringService::class);
        $this->app->bind(CacheProviderInterface::class, CacheProvider::class);
        $this->app
            ->when(CacheProvider::class)
            ->needs('$storage')
            ->give(Cache::store('commissions'));
        $this->app
            ->when(CacheProvider::class)
            ->needs('$countryTTL')
            ->give(config('cache.stores.commissions.country_code_ttl'));
        $this->app
            ->when(CacheProvider::class)
            ->needs('$rateTTL')
            ->give(config('cache.stores.commissions.currency_rate_ttl'));
        $this->app->bind(BinProviderInterface::class, BinProvider::class);
        $this->app
            ->when(BinProvider::class)
            ->needs('$client')
            ->give(new Client([
                'base_uri' => config('binprovider.url'),
            ]));
        $this->app->bind(RateProviderInterface::class, RateProvider::class);
        $this->app
            ->when(RateProvider::class)
            ->needs('$client')
            ->give(new Client([
                'base_uri' => config('rateprovider.url'),
            ]));
        $this->app
            ->when(RateProvider::class)
            ->needs('$key')
            ->give(config('rateprovider.key'));
        $this->app->bind(RateApplierInterface::class, RateApplier::class);
        $this->app->bind(CountryCoefficientApplierInterface::class, CountryCoefficientApplier::class);
        $this->app
            ->when(CountryCoefficientApplier::class)
            ->needs('$euroCoefficient')
            ->give(config('coefficientapplier.euro_coefficient'));
        $this->app
            ->when(CountryCoefficientApplier::class)
            ->needs('$coefficient')
            ->give(config('coefficientapplier.coefficient'));
        $this->app->bind(MoneyConvertApplierInterface::class, MoneyConvertApplier::class);


        $this->app->tag(
            [
                RateApplierInterface::class,
                CountryCoefficientApplierInterface::class,
                MoneyConvertApplierInterface::class,
            ],
            [self::COMMISSION_APPLIERS]
        );

        $this->app->bind(CommissionCalculatorInterface::class, CommissionCalculator::class);
        $this->app->bind(CommissionCalculatorInterface::class, static function (Application $app) {
            return new CommissionCalculator(
                ...$app->tagged(self::COMMISSION_APPLIERS)
            );
        });
    }
}
