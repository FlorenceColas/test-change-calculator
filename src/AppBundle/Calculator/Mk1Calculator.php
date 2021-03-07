<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

class Mk1Calculator extends AbstractCalculator implements CalculatorInterface
{
    public array $currencies = [1,];

    public function getSupportedModel(): string
    {
        return 'mk1';
    }
}
