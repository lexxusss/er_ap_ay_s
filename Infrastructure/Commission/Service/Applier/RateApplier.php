<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Applier;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\Service\RateApplierInterface;
use EraPays\Infrastructure\Commission\Service\FloatDecorator;
use EraPays\Infrastructure\Commission\Service\Provider\RateContractor;

class RateApplier implements RateApplierInterface
{
    private RateContractor $rateContractor;
    private FloatDecorator $floatDecorator;

    public function __construct(
        RateContractor $rateContractor,
        FloatDecorator $floatDecorator
    ) {
        $this->rateContractor = $rateContractor;
        $this->floatDecorator = $floatDecorator;
    }

    public function apply(TransactionDto $transaction): void
    {
        $transaction->setRate(
            $this->rateContractor->getRate($transaction->getCurrency())
        );

        if ($transaction->getRate()->getValue() && !$transaction->getCurrency()->isEuro()) {
            $transaction->setAmountCommissioned(
                $this->floatDecorator->roundHalfUp(
                    $transaction->getAmountCommissioned() / $transaction->getRate()->getValue()
                )
            );
        }
    }
}
