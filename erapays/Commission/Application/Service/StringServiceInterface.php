<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

interface StringServiceInterface
{
    public function splitStrings(string $content): array;
    public function clearContent(string $item): string;
}
