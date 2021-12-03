<?php

declare(strict_types=1);


class StringParser
{
    public function applyDelimiter(array $data, string $delimiter): string
    {
        return join($delimiter, $data);
    }
}
