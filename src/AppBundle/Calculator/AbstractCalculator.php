<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

use AppBundle\Model\Change;

abstract class AbstractCalculator
{
    public array $currencies = [];

    public function getChange(int $amount): ?Change
    {
        $change = new Change();

        // Order $this->currencies in descending
        rsort( $this->currencies);

        $result = [
            10 => 0,
            5 => 0,
            2 => 0,
            1 => 0,
        ];

        // Greedy algorithm
        $rest = $this->greedyAlgorithm($amount, 0, $result);

        // Greedy algorithm may not be the most optimum.
        // Go back up the chain of coins from the smallest one
        // to see if by removing 1 in the currency just above,
        // we can give back all the change.
        // example for 16: 10 + 5 -> rest 1 -> fail
        //                 10 + 2 + 2 + 2 -> rest 0 -> success
        if (0 < $rest) {
            $index = count($this->currencies) - 1;

            while (0 <= $index and 0 < $rest) {
                $currency = $this->currencies[$index];

                if (!empty($result[$currency])) {
                    $result[$currency]--;
                    $rest += $currency;

                    $rest = $this->greedyAlgorithm($rest, $index + 1, $result);

                    if (0 === $rest) {
                        break;
                    }

                    $index = count($this->currencies) - 1;
                } else {
                    $index--;
                }
            }
        }

        $change->bill10 = $result[10];
        $change->bill5 = $result[5];
        $change->coin2 = $result[2];
        $change->coin1 = $result[1];

        return (0 === $rest)
            ? $change
            : null;
    }

    private function greedyAlgorithm(int $amount, int $index, array &$result): int
    {
        for ($i = $index; $i < count($this->currencies); $i++) {
            $currency = $this->currencies[$i];

            while ($amount >= $currency) {
                $result[$currency]++;
                $amount -= $currency;

                if (0 === $amount) {
                    break;
                }
            }
        }

        return (int)$amount;
    }
}
