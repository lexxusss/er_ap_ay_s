<?php

declare(strict_types=1);

namespace Tests\Unit\EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Commission\Domain\Enum\Rate;
use EraPays\Infrastructure\Commission\Service\Applier\RateApplier;
use EraPays\Infrastructure\Commission\Service\FloatDecorator;
use EraPays\Infrastructure\Commission\Service\Provider\RateContractor;
use Tests\TestCase;

class RateApplierTest extends TestCase
{
    private RateApplier $rateApplier;
    private RateContractor $rateContractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rateContractor = $this->createMock(RateContractor::class);
        $this->rateApplier = new RateApplier(
            $this->rateContractor,
            app(FloatDecorator::class)
        );
    }

    /**
     * @dataProvider applyDataProvider
     */
    public function testApply(string $currency, float $amount, ?float $rate, float $res): void
    {
        $this->rateContractor
            ->expects(self::once())
            ->method('getRate')
            ->willReturn(new Rate($rate));

        $transaction = new TransactionDto(123, $amount, Currency::memberByValue($currency));
        $this->rateApplier->apply($transaction);

        $this->assertEquals($res, $transaction->getAmountCommissioned());
    }

    public function applyDataProvider(): array
    {
        return [
            // do not apply rate: curr == EUR
            [
                'currency' => 'EUR',
                'amount' => 100.0,
                'rate' => 1.02,
                'res' => 100.0,
            ],
            // do not apply rate: rate == null
            [
                'currency' => 'USD',
                'amount' => 100.0,
                'rate' => null,
                'res' => 100.0,
            ],
            // do not apply rate: rate == 0
            [
                'currency' => 'USD',
                'amount' => 100.0,
                'rate' => 0,
                'res' => 100.0,
            ],
            // do not apply rate: rate == 0.float
            [
                'currency' => 'USD',
                'amount' => 100.0,
                'rate' => 0.00,
                'res' => 100.0,
            ],
            // apply rate
            [
                'currency' => 'USD',
                'amount' => 100.0,
                'rate' => 1.02,
                'res' => 98.04,
            ],
        ];
    }
}
