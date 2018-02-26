<?php


namespace App\Learn\SuperMemo;


use App\Entity\Item;
use App\Entity\SuperMemoRepetition;
use App\Repository\SuperMemoRepetitionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SuperMemoHandler
{
    /**
     * @var SuperMemoRepetitionRepository
     */
    private $memoRepetitionRepository;
    /**
     * @var SuperMemoCalculator
     */
    private $superMemoCalculator;

    private $newInterval;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(SuperMemoRepetitionRepository $memoRepetitionRepository, SuperMemoCalculator $superMemoCalculator, EntityManagerInterface $entityManager)
    {
        $this->memoRepetitionRepository = $memoRepetitionRepository;
        $this->superMemoCalculator = $superMemoCalculator;
        $this->entityManager = $entityManager;
    }

    public function handle(Item $item, int $quality)
    {

        $smrs = $this->memoRepetitionRepository->findAllForItem($item);

        $repetitions = count($smrs);
        $oldEFactor = $smrs[0]->getFactor();
        $oldInterval = $smrs[0]->getInterval();

        $newEFactor =  $this->superMemoCalculator->calcNewEFactor($oldEFactor, $quality);
        $this->newInterval  = $this->superMemoCalculator->calcInterval($repetitions + 1, $newEFactor, $oldInterval);

        $newSmrs = new SuperMemoRepetition();
        $newSmrs->setItem($item);
        $newSmrs->setFactor($newEFactor);
        $newSmrs->setInterval($this->newInterval);

        $this->entityManager->persist($newSmrs);
        $this->entityManager->flush();

    }

    public function getNewInterval(): int
    {
        return $this->newInterval;
    }
}