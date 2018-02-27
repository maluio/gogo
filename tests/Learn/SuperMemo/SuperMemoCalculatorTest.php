<?php

namespace App\Tests\Learn\SuperMemo;

use App\Learn\SuperMemo\SuperMemoCalculator;
use App\Utils\DateTimeProvider;
use PHPUnit\Framework\TestCase;

class SuperMemoCalculatorTest extends TestCase
{

    /**
     * @var SuperMemoCalculator
     */
    private $superMemoCalculator;

    public function setUp()
    {
        $dtProviderStub = $this->createMock(DateTimeProvider::class);
        $fakeNow = new \DateTime('5 march 2017 7pm');
       // $dtProviderStub->method('fromString')->willReturn($fakeNow);
        $this->superMemoCalculator = new \App\Learn\SuperMemo\SuperMemoCalculator($dtProviderStub);
    }

    public function FirstRepetition(){
        $this->superMemoCalculator->init(0);

        $this->assertEquals(
            1,
            $this->superMemoCalculator->getNewInterval()
        );
    }
}
