<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Exception;

use RuntimeException;

class FileParsingException extends RuntimeException
{
    public static function make(string $filename, string $invalidJson, string $reason): self
    {
        return new static(sprintf('File %s contains invalid json: "%s. Reason: %s', $filename, $invalidJson, $reason));
    }
}
