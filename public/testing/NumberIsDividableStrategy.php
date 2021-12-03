<?php

declare(strict_types=1);

require 'NumberHandleStrategyInterface.php';

class NumberIsDividableStrategy implements NumberHandleStrategyInterface
{
    public function detect(int $max, array $mapper): array
    {
        $res = [];
        for ($i = 1; $i <= $max; $i++) {
            $divides = false;
            foreach ($mapper as $num => $word) {
                if (false === $divides && 0 === $i % $num) {
                    $res[] = $word;
                    $divides = true;
                }
            }

            if (false === $divides) {
                $res[] = $i;
            }
        }

        return $res;
    }
}
