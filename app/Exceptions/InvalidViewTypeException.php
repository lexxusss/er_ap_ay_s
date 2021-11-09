<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class InvalidViewTypeException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(string $actualType, array $validTypes): self
    {
        return new static(sprintf(
            'Invalid View type "%s". Valid types are: [%s]',
            $actualType,
            implode(', ', $validTypes)
        ));
    }
}
