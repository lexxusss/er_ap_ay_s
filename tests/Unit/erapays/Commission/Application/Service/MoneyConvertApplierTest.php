<?php

declare(strict_types=1);

namespace Tests\Unit\EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\Service\MoneyConvertApplierInterface;
use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Infrastructure\Commission\Service\Applier\MoneyConvertApplier;
use Tests\TestCase;

class MoneyConvertApplierTest extends TestCase
{
    private MoneyConvertApplier $moneyConvertApplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moneyConvertApplier = app(MoneyConvertApplierInterface::class);
    }

    /**
     * @param float $amount
     * @param float $res
     * @dataProvider applyDataProvider
     */
    public function testApply(float $amount, float $res): void
    {
        $transaction = new TransactionDto(123, $amount, Currency::EUR());
        $this->moneyConvertApplier->apply($transaction);

        $this->assertEquals($res, $transaction->getAmountCommissioned());
    }

    public function applyDataProvider(): array
    {
        return [
            [
                'amount' => 100.04,
                'res' => 1.0,
            ],
            [
                'amount' => 109.49,
                'res' => 1.09,
            ],
            [
                'amount' => 109.50,
                'res' => 1.10,
            ],
            [
                'amount' => 0,
                'res' => 0,
            ],
        ];
    }
}
