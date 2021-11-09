<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Applier;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\Service\RateApplierInterface;
use EraPays\Infrastructure\Commission\Service\FloatDecorator;

class MoneyConvertApplier implements RateApplierInterface
{
    private const AMOUNT_OF_COINS_PER_UNIT = 100;

    private FloatDecorator $floatDecorator;

    public function __construct(FloatDecorator $floatDecorator)
    {
        $this->floatDecorator = $floatDecorator;
    }

    public function apply(TransactionDto $transaction): void
    {
        $transaction->setAmountCommissioned(
            $this->floatDecorator->roundHalfUp(
                $transaction->getAmountCommissioned() / self::AMOUNT_OF_COINS_PER_UNIT
            )
        );
    }
}
