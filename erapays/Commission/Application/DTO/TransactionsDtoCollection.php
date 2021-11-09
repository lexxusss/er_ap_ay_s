<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\DTO;

use App\Collection\DedicatedCollection;

class TransactionsDtoCollection extends DedicatedCollection
{
    public function defineAllowedTypes(): array
    {
        return [TransactionDto::class];
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->items as $item) {
            $data[] = $item->toArray();
        }

        return $data;
    }
}
