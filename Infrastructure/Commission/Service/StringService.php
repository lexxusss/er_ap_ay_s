<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service;

use EraPays\Commission\Application\Service\StringServiceInterface;

class StringService implements StringServiceInterface
{
    private const STRING_DELIMITER = "\n";

    public function splitStrings(string $content): array
    {
        return explode(self::STRING_DELIMITER, $content);
    }

    public function clearContent(string $item): string
    {
        return trim($item);
    }
}
