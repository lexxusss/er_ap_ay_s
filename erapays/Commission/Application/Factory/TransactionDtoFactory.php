<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Factory;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Domain\Enum\Currency;

class TransactionDtoFactory
{
    private InputValidator $validator;

    public function __construct(InputValidator $validator)
    {
        $this->validator = $validator;
    }

    public function fromRaw(array $raw): TransactionDto
    {
        $this->validator->validateTransactionFormat($raw);

        return new TransactionDto(
            (int) $raw[InputValidator::TRANSACTION_BIN],
            (float) $raw[InputValidator::TRANSACTION_AMOUNT],
            Currency::memberByValue($raw[InputValidator::TRANSACTION_CURRENCY])
        );
    }
}
