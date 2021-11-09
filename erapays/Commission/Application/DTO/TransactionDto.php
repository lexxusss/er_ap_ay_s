<?php

declare(strict_types=1);

namespace EraPays\Commission\Application\DTO;

use EraPays\Commission\Domain\Enum\CountryCode;
use EraPays\Commission\Domain\Enum\Currency;
use EraPays\Commission\Domain\Enum\Rate;

class TransactionDto
{
    private int $bin;
    private float $amount;
    private Currency $currency;
    private ?float $amountCommissioned;
    private ?CountryCode $countryCode = null;
    private ?Rate $rate = null;
    private ?string $commissionFailedReason = null;

    public function __construct(int $bin, float $amount, Currency $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->amountCommissioned = $amount;
    }

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmountCommissioned(): float
    {
        return $this->amountCommissioned;
    }

    public function setAmountCommissioned(float $amountCommissioned): void
    {
        $this->amountCommissioned = $amountCommissioned;
    }

    public function getCommissionFailedReason(): ?string
    {
        return $this->commissionFailedReason;
    }

    public function failCommissionApplying(string $commissionFailedReason): void
    {
        $this->amountCommissioned = null;
        $this->commissionFailedReason = $commissionFailedReason;
    }

    public function getCountryCode(): ?CountryCode
    {
        return $this->countryCode;
    }

    public function setCountryCode(?CountryCode $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getRate(): ?Rate
    {
        return $this->rate;
    }

    public function setRate(?Rate $rate): void
    {
        $this->rate = $rate;
    }
}
