<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service;

use EraPays\Commission\Application\DTO\TransactionsDtoCollection;
use EraPays\Commission\Application\Factory\FileToArrayFactory;
use EraPays\Commission\Application\Factory\TransactionDtoFactory;
use EraPays\Commission\Application\Service\InputParserInterface;

class InputParser implements InputParserInterface
{
    private FileToArrayFactory $fileToArrayFactory;
    private TransactionDtoFactory $dtoFactory;

    public function __construct(FileToArrayFactory $fileToArrayFactory, TransactionDtoFactory $dtoFactory)
    {
        $this->fileToArrayFactory = $fileToArrayFactory;
        $this->dtoFactory = $dtoFactory;
    }

    public function parse(string $filename): TransactionsDtoCollection
    {
        $collection = new TransactionsDtoCollection();
        foreach ($this->fileToArrayFactory->convert($filename) as $raw) {
            $collection->add(
                $this->dtoFactory->fromRaw($raw)
            );
        }

        return $collection;
    }
}
