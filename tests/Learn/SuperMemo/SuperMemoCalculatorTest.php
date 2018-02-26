<?php

namespace App\Tests\Learn\SuperMemo;

use App\Learn\SuperMemo\SuperMemoCalculator;
use PHPUnit\Framework\TestCase;

class SuperMemoCalculatorTest extends TestCase
{

    /**
     * @var SuperMemoCalculator
     */
    private $superMemoCalculator;

    public function setUp()
    {
        $this->superMemoCalculator = new \App\Learn\SuperMemo\SuperMemoCalculator();
    }

    public function testFirstRepetition(){
        $this->assertEquals(
            1,
            $this->superMemoCalculator->calcInterval(1)
        );
    }

    public function testSecondRepetition(){
        $this->assertEquals(
            2,
            $this->superMemoCalculator->calcInterval(2, 3.4, 3)
        );
    }

    public function testThirdRepetition(){
        $this->assertEquals(
            7,
            $this->superMemoCalculator->calcInterval(3, 3.4, 2)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRepetition(){
        $this->superMemoCalculator->calcInterval(-4);
    }

    public function testCalcNewEfactor(){
        $this->assertEquals(
            3.26,
            $this->superMemoCalculator->calcNewEFactor(3,3.4)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidQuality(){
        $this->superMemoCalculator->calcNewEFactor(9,3.4);
    }
}