<?php

namespace Tests\AppBundle\Calculator;

use AppBundle\Calculator\CalculatorInterface;
use AppBundle\Model\Change;
use AppBundle\Calculator\Mk2Calculator;
use PHPUnit\Framework\TestCase;

class Mk2CalculatorTest extends TestCase
{
    /**
     * @var CalculatorInterface
     */
    private $calculator;

    public function dataProviderGetChangeHardPossibleChange(): array
    {
        return [
            '17 - only with greedy algorithm' => [
                17,
                [
                    'bill10' => 1,
                    'bill5' => 1,
                    'coin2' => 1,
                    'coin1' => 0,
                ],
            ],
            '16 - using second loop after initial greedy algorithm' => [
                16,
                [
                    'bill10' => 1,
                    'bill5' => 0,
                    'coin2' => 3,
                    'coin1' => 0,
                ],
            ],
        ];
    }

    protected function setUp()
    {
        $this->calculator = new Mk2Calculator();
    }

    public function testGetSupportedModel()
    {
        $this->assertEquals('mk2', $this->calculator->getSupportedModel());
    }

    public function testGetChangeEasy()
    {
        $change = $this->calculator->getChange(2);
        $this->assertInstanceOf(Change::class, $change);
        $this->assertEquals(1, $change->coin2);
    }

    public function testGetChangeImpossible()
    {
        $change = $this->calculator->getChange(1);
        $this->assertNull($change);
    }

    /**
     * @dataProvider dataProviderGetChangeHardPossibleChange
     * @param int $amount
     * @param array $expected
     */
    public function testGetChangeHardPossibleChange(int $amount, array $expected)
    {
        $change = $this->calculator->getChange($amount);
        $this->assertInstanceOf(Change::class, $change);
        $this->assertEquals($expected['bill10'], $change->bill10);
        $this->assertEquals($expected['bill5'], $change->bill5);
        $this->assertEquals($expected['coin2'], $change->coin2);
        $this->assertEquals($expected['coin1'], $change->coin1);
    }

    /**
     * @dataProvider getChangeHardDataProvider
     * @covers \AppBundle\Calculator\Mk2Calculator::getChange
     *
     * @param int $amount
     * @param int $oneCoin
     * @param int $twoCoin
     * @param int $fiveBill
     * @param int $tenBill
     */
    public function testGetChangeHard(int $amount, int $oneCoin, int $twoCoin, int $fiveBill, int $tenBill)
    {
        $change = $this->calculator->getChange($amount);

        $this->assertInstanceOf(Change::class, $change);
        $this->assertEquals($oneCoin, $change->coin1);
        $this->assertEquals($twoCoin, $change->coin2);
        $this->assertEquals($fiveBill, $change->bill5);
        $this->assertEquals($tenBill, $change->bill10);
    }

    public function getChangeHardDataProvider() : array
    {
        return [
            [2, 0, 1, 0, 0],
            [4, 0, 2, 0, 0],
            [5, 0, 0, 1, 0],
            [6, 0, 3, 0, 0],
            [7, 0, 1, 1, 0],
            [8, 0, 4, 0, 0],
            [9, 0, 2, 1, 0],
            [10, 0, 0, 0, 1],
            [11, 0, 3, 1, 0],
            [12, 0, 1, 0, 1],
            [13, 0, 4, 1, 0],
            [14, 0, 2, 0, 1],
            [15, 0, 0, 1, 1],
            [16, 0, 3, 0, 1],
            [17, 0, 1, 1, 1],
            [18, 0, 4, 0, 1],
            [19, 0, 2, 1, 1],
            [20, 0, 0, 0, 2],
            [21, 0, 3, 1, 1],
            [22, 0, 1, 0, 2],
            [23, 0, 4, 1, 1],
            [24, 0, 2, 0, 2],
            [25, 0, 0, 1, 2],
            [27, 0, 1, 1, 2],
            [30, 0, 0, 0, 3],
            [35, 0, 0, 1, 3],
            [42, 0, 1, 0, 4],
            [44, 0, 2, 0, 4],
            [46, 0, 3, 0, 4],
            [56, 0, 3, 0, 5],
            [58, 0, 4, 0, 5],
            [59, 0, 2, 1, 5],
            [100, 0, 0, 0, 10],
            [101, 0, 3, 1, 9],
        ];
    }
}
