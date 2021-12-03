<?php

declare(strict_types=1);


class NumbersDetector
{
    private NumberHandleStrategyInterface $strategy;
    private StringParser $stringParser;

    public function __construct(NumberHandleStrategyInterface $strategy, StringParser $stringParser)
    {
        $this->strategy = $strategy;
        $this->stringParser = $stringParser;
    }

    public function detect(int $max, string $delimiter, array $mapper): string
    {
        return $this->stringParser->applyDelimiter(
            $this->strategy->detect($max, $mapper),
            $delimiter
        );
    }
}
