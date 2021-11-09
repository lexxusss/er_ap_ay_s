<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service;

use EraPays\Commission\Application\Service\StorageServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;

class StorageService implements StorageServiceInterface
{
    private Filesystem $storage;
    private StringService $stringService;

    public function __construct($storage, StringService $stringService)
    {
        $this->storage = $storage;
        $this->stringService = $stringService;
    }

    public function getData(string $filename): array
    {
        $content = trim($this->storage->get($filename));

        return $this->stringService->splitStrings($content);
    }
}
