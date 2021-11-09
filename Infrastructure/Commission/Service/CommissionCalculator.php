<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\DTO\TransactionsDtoCollection;
use EraPays\Commission\Application\Exception\ApplierException;
use EraPays\Commission\Application\Service\CommissionCalculatorInterface;
use EraPays\Commission\Application\Service\TransactionCommissionApplierInterface;

class CommissionCalculator implements CommissionCalculatorInterface
{
    /** @var TransactionCommissionApplierInterface[] */
    private array $appliers;

    public function __construct(
        TransactionCommissionApplierInterface ...$appliers
    ) {
        $this->appliers = $appliers;
    }

    public function calculate(TransactionsDtoCollection $transactions): void
    {
        $transactions->each(function (TransactionDto $transaction): void {
            foreach ($this->appliers as $applier) {
                try {
                    $applier->apply($transaction);
                } catch (ApplierException $e) {
                    $transaction->failCommissionApplying($e->getMessage());
                }
            }
        });
    }
}
