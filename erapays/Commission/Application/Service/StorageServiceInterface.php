<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

interface StorageServiceInterface
{
    public function getData(string $filename): array;
}
