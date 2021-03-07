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

    public function testGetChangeHardNotPossibleChange()
    {
        $change = $this->calculator->getChange(18);
        $this->assertEquals(null, $change);
    }
}
