<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Factory;

use Webmozart\Assert\Assert;

class InputValidator
{
    public const TRANSACTION_BIN = 'bin';
    public const TRANSACTION_AMOUNT = 'amount';
    public const TRANSACTION_CURRENCY = 'currency';

    public function validateTransactionFormat(array $input): bool
    {
        Assert::keyExists($input, self::TRANSACTION_BIN);
        Assert::keyExists($input, self::TRANSACTION_AMOUNT);
        Assert::keyExists($input, self::TRANSACTION_CURRENCY);

        return true;
    }
}
