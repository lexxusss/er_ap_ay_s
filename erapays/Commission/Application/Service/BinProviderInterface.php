<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\Service;

use EraPays\Commission\Application\Exception\InvalidResponseException;

interface BinProviderInterface
{
    /**
     * @param int $bin
     * @return string
     * @throws InvalidResponseException
     */
    public function getCountryCode(int $bin): string;
}
