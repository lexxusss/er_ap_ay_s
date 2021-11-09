<?php

declare(strict_types=1);

namespace Tests\Integration\EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\DTO\TransactionsDtoCollection;
use EraPays\Commission\Application\Service\BinProviderInterface;
use EraPays\Commission\Application\Service\CacheProviderInterface;
use EraPays\Commission\Application\Service\CommissionCalculatorInterface;
use EraPays\Commission\Application\Service\CountryCoefficientApplierInterface;
use EraPays\Commission\Application\Service\MoneyConvertApplierInterface;
use EraPays\Commission\Application\Service\RateApplierInterface;
use EraPays\Commission\Application\Service\RateProviderInterface;
use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Infrastructure\Commission\Service\Applier\CountryCoefficientApplier;
use EraPays\Infrastructure\Commission\Service\Applier\RateApplier;
use EraPays\Infrastructure\Commission\Service\CommissionCalculator;
use EraPays\Infrastructure\Commission\Service\FloatDecorator;
use EraPays\Infrastructure\Commission\Service\Provider\CountryCodeContractor;
use EraPays\Infrastructure\Commission\Service\Provider\RateContractor;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class CommissionCalculatorTest extends TestCase
{
    /**
     * @param int $bin
     * @param float $amount
     * @param string $currency
     * @param float $rate
     * @param string $countryCode
     * @param float $amountCommissioned
     * @dataProvider calculateDataProvider
     */
    public function testCalculate(
        int $bin,
        float $amount,
        string $currency,
        float $rate,
        string $countryCode,
        float $amountCommissioned
    ): void {
        $commissionCalculator = $this->buildCalculator($countryCode, $rate);

        $transaction = new TransactionDto($bin, $amount, Currency::memberByValue($currency));
        $commissionCalculator->calculate(
            new TransactionsDtoCollection([$transaction])
        );

        $this->assertEquals($transaction->getCountryCode()->getValue(), $countryCode);
        $this->assertEquals($transaction->getRate()->getValue(), $rate);
        $this->assertEquals($transaction->getAmountCommissioned(), $amountCommissioned);
    }

    public function calculateDataProvider(): array
    {
        return [
            // currency is euro & countryCode is euro: (300) * (1.0 / 100) = 3.0
            [
                'bin' => 45717360,
                'amount' => 300.00,
                'currency' => 'EUR',
                'rate' => 1.5,
                'countryCode' => 'PT',
                'amountCommissioned' => 3.0,
            ],
            // currency is euro & countryCode is not euro: (300) * (2.0 / 100) = 6.0
            [
                'bin' => 45717360,
                'amount' => 300.00,
                'currency' => 'EUR',
                'rate' => 1.5,
                'countryCode' => 'US',
                'amountCommissioned' => 6.0,
            ],
            // currency is not euro & countryCode is euro: (300 / 1.5) * (1.0 / 100) = 2.0
            [
                'bin' => 45717360,
                'amount' => 300.00,
                'currency' => 'USD',
                'rate' => 1.5,
                'countryCode' => 'RO',
                'amountCommissioned' => 2.0,
            ],
            // currency is not euro & countryCode is not euro: (300 / 1.5) * (2.0 / 100) = 4.0
            [
                'bin' => 45717360,
                'amount' => 300.00,
                'currency' => 'USD',
                'rate' => 1.5,
                'countryCode' => 'US',
                'amountCommissioned' => 4.0,
            ],
        ];
    }

    private function buildCalculator(string $countryCode, float $rate): CommissionCalculatorInterface
    {
        $cacheMock = $this->buildCacheMock($countryCode, $rate);

        $rateApplier = $this->buildRateApplier($cacheMock);
        $countryCoefficientApplier = $this->buildCountryCoefficientApplier($cacheMock);

        return new CommissionCalculator(
            $rateApplier,
            $countryCoefficientApplier,
            app(MoneyConvertApplierInterface::class)
        );
    }

    /**
     * @param MockObject|CacheProviderInterface $cacheMock
     * @return CountryCoefficientApplierInterface
     */
    private function buildCountryCoefficientApplier(MockObject $cacheMock): CountryCoefficientApplierInterface
    {
        $countryCodeContractor = new CountryCodeContractor(
            $cacheMock,
            app(BinProviderInterface::class)
        );

        return new CountryCoefficientApplier(
            $countryCodeContractor,
            app(FloatDecorator::class),
            config('coefficientapplier.euro_coefficient'),
            config('coefficientapplier.coefficient')
        );
    }

    /**
     * @param MockObject|CacheProviderInterface $cacheMock
     * @return RateApplierInterface
     */
    private function buildRateApplier(MockObject $cacheMock): RateApplierInterface
    {
        $rateContractor = new RateContractor(
            $cacheMock,
            app(RateProviderInterface::class)
        );

        return new RateApplier(
            $rateContractor,
            app(FloatDecorator::class)
        );
    }

    /**
     * @param string $countryCode
     * @param float $rate
     * @return MockObject|CacheProviderInterface
     */
    private function buildCacheMock(string $countryCode, float $rate): MockObject
    {
        $cacheMock = $this->createMock(CacheProviderInterface::class);
        $cacheMock
            ->expects(self::once())
            ->method('getCountryCode')
            ->willReturn($countryCode);
        $cacheMock
            ->expects(self::once())
            ->method('getRate')
            ->willReturn($rate);

        return $cacheMock;
    }
}
