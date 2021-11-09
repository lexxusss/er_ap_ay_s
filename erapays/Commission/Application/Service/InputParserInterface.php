<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionsDtoCollection;

interface InputParserInterface
{
    public function parse(string $filename): TransactionsDtoCollection;
}
