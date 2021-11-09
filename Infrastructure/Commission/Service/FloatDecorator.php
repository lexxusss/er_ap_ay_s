<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service;

class FloatDecorator
{
    private const PRECISION = 2;

    private const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    private const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    private const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    private const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    public function roundHalfUp(float $float): float
    {
        return round($float, self::PRECISION, self::ROUND_HALF_UP);
    }
}
