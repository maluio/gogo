<?php

namespace App\Tests\Learn\SuperMemo;

use App\Entity\Item;
use App\Entity\SuperMemoRepetition;
use App\Learn\SuperMemo\SuperMemoCalculator;
use App\Repository\SuperMemoRepetitionRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class SuperMemoHandlerTest extends TestCase
{

    private $repoMock;

    private $calcMock;

    private $emMock;

    private $subject;

    public function setUp()
    {
        parent::setUp();
        $this->repoMock = $this->createMock(SuperMemoRepetitionRepository::class);
        $this->calcMock = $this->createMock(SuperMemoCalculator::class);
        $this->emMock =  $this->createMock(EntityManager::class);
        $this->subject = new \App\Learn\SuperMemo\SuperMemoHandler(
            $this->repoMock, $this->calcMock, $this->emMock
        );

    }

    public function testPersistsSuperMemoRep(){

        $this->calcMock->expects($this->once())
            ->method('getNewEFactor')
            ->willReturn(12345);

        $this->calcMock->expects($this->once())
            ->method('getNewInterval')
            ->willReturn(54321);

        $this->emMock->expects($this->once())
            ->method('persist')
            ->with(
                $this->callback(function($subject){
                    return is_callable([$subject, 'getFactor']) &&
                        $subject->getFactor() == 12345 &&
                       is_callable([$subject, 'getInterval']) &&
                       $subject->getInterval() == 54321;
                    }
                )
        );

        $this->emMock->expects($this->once())
            ->method('flush');

        $item = new Item();

        $this->subject->handle($item, 4);
    }

    public function testFirstRepetition(){

        $item = new Item();

        $this->repoMock->expects($this->once())
            ->method('findAllForItem')
            ->with(
                $this->callback(function ($subject) use ($item) {
                    return $subject == $item;
                })
            )
            ->willReturn([]);

        $this->calcMock->expects($this->once())
            ->method('init')
            ->with(5);


        $this->subject->handle($item, 5);
    }

    public function testSecondRepetition(){

        $smrp = new SuperMemoRepetition();
        $smrp->setInterval(22);
        $smrp->setFactor(44);

        $this->repoMock->expects($this->once())
            ->method('findAllForItem')
            ->willReturn([$smrp]);

        $this->calcMock->expects($this->once())
            ->method('init')
            ->with(5, 22, 44.0);

        $item = new Item();

        $this->subject->handle($item, 5);
    }

    public function testHandleReturnsSelf(){
        $item = new Item();

        $self = $this->subject->handle($item, 4);

        $this->assertEquals(
            $this->subject,
            $self
        );
    }

    public function testGetNewDueDate(){

        $this->calcMock->expects($this->once())
            ->method('getNewDueDate');

        $this->subject->getNewDueDate();
    }
}
