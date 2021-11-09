<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionDto;

interface TransactionCommissionApplierInterface
{
    public function apply(TransactionDto $transaction): void;
}
