<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Factory;

use EraPays\Commission\Application\Exception\FileParsingException;
use EraPays\Commission\Application\Service\StorageServiceInterface;
use EraPays\Commission\Application\Service\StringServiceInterface;
use JsonException;
use Webmozart\Assert\InvalidArgumentException;

class FileToArrayFactory
{
    private const DECODE_DEPTH = 2;

    private InputValidator $validator;
    private StorageServiceInterface $storageService;
    private StringServiceInterface $stringService;

    public function __construct(
        InputValidator $validator,
        StorageServiceInterface $storageService,
        StringServiceInterface $stringService
    ) {
        $this->validator = $validator;
        $this->storageService = $storageService;
        $this->stringService = $stringService;
    }

    public function convert(string $filename): array
    {
        $result = [];
        foreach ($this->storageService->getData($filename) as $item) {
            $item = $this->stringService->clearContent($item);

            try {
                $decoded = json_decode($item, true, self::DECODE_DEPTH, JSON_THROW_ON_ERROR);
                $this->validator->validateTransactionFormat($decoded);
            } catch (JsonException|InvalidArgumentException $e) {
                throw FileParsingException::make($filename, $item, $e->getMessage());
            }

            $result[] = $decoded;
        }

        return $result;
    }
}
