<?php

declare(strict_types=1);

namespace Tests\Unit\EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Domain\Enum\CountryCode;
use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Infrastructure\Commission\Service\Applier\CountryCoefficientApplier;
use EraPays\Infrastructure\Commission\Service\FloatDecorator;
use EraPays\Infrastructure\Commission\Service\Provider\CountryCodeContractor;
use Tests\TestCase;

class CountryCoefficientApplierTest extends TestCase
{
    private CountryCoefficientApplier $coefficientApplier;
    private CountryCodeContractor $countryCodeContractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->countryCodeContractor = $this->createMock(CountryCodeContractor::class);
        $this->coefficientApplier = new CountryCoefficientApplier(
            $this->countryCodeContractor,
            app(FloatDecorator::class),
            config('coefficientapplier.euro_coefficient'),
            config('coefficientapplier.coefficient')
        );
    }

    /**
     * @param string $countryCode
     * @param float $amount
     * @param float $res
     * @dataProvider applyDataProvider
     */
    public function testApply(string $countryCode, float $amount, float $res): void
    {
        $this->countryCodeContractor
            ->expects(self::once())
            ->method('getCountryCode')
            ->willReturn(new CountryCode($countryCode));

        $transaction = new TransactionDto(123, $amount, Currency::EUR());
        $this->coefficientApplier->apply($transaction);

        $this->assertEquals($res, $transaction->getAmountCommissioned());
    }

    public function applyDataProvider(): array
    {
        return [
            // countryCode is euro: 100.0 * 1.0 = 100.0
            [
                'countryCode' => 'PT',
                'amount' => 100.0,
                'res' => 100.0,
            ],
            // countryCode is not euro: 100.0 * 2.0 = 200.00
            [
                'countryCode' => 'US',
                'amount' => 100.0,
                'res' => 200.00,
            ],
        ];
    }
}
