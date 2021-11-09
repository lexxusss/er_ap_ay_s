<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Applier;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\Service\CountryCoefficientApplierInterface;
use EraPays\Infrastructure\Commission\Service\FloatDecorator;
use EraPays\Infrastructure\Commission\Service\Provider\CountryCodeContractor;

class CountryCoefficientApplier implements CountryCoefficientApplierInterface
{
    private CountryCodeContractor $countryCodeContractor;
    private FloatDecorator $floatDecorator;
    private float $euroCoefficient;
    private float $coefficient;

    public function __construct(
        CountryCodeContractor $countryCodeContractor,
        FloatDecorator $floatDecorator,
        float $euroCoefficient,
        float $coefficient
    ) {
        $this->countryCodeContractor = $countryCodeContractor;
        $this->floatDecorator = $floatDecorator;
        $this->euroCoefficient = $euroCoefficient;
        $this->coefficient = $coefficient;
    }

    public function apply(TransactionDto $transaction): void
    {
        $transaction->setCountryCode(
            $this->countryCodeContractor->getCountryCode(
                $transaction->getBin()
            )
        );

        $coefficient = $transaction->getCountryCode()->isEuro() ? $this->euroCoefficient : $this->coefficient;

        $transaction->setAmountCommissioned(
            $this->floatDecorator->roundHalfUp(
                $transaction->getAmountCommissioned() * $coefficient
            )
        );
    }
}
