<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

class Mk2Calculator extends AbstractCalculator implements CalculatorInterface
{
    public array $currencies = [2, 5, 10,];

    public function getSupportedModel(): string
    {
        return 'mk2';
    }
}
