<?php

declare(strict_types=1);


interface NumberHandleStrategyInterface
{
    public function detect(int $max, array $mapper): array;
}
