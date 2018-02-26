<?php


namespace App\Learn\SuperMemo;


use App\Entity\Item;
use App\Entity\SuperMemoRepetition;
use App\Learn\LearnHandlerInterface;
use App\Repository\SuperMemoRepetitionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SuperMemoHandler implements LearnHandlerInterface
{
    /**
     * @var SuperMemoRepetitionRepository
     */
    private $memoRepetitionRepository;

    /**
     * @var SuperMemoCalculator
     */
    private $superMemoCalculator;

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

    public function handle(Item $item, int $quality): LearnHandlerInterface
    {

        $smrs = $this->memoRepetitionRepository->findAllForItem($item);

        if (0 === count($smrs)){
            $this->superMemoCalculator->init($quality);
        }
        else {
            $this->superMemoCalculator->init($quality, $smrs[0]->getInterval(), $smrs[0]->getFactor());
        }

        $newSmrs = new SuperMemoRepetition();
        $newSmrs->setItem($item);
        $newSmrs->setFactor($this->superMemoCalculator->getNewEFactor());
        $newSmrs->setInterval($this->superMemoCalculator->getNewInterval());

        $this->entityManager->persist($newSmrs);
        $this->entityManager->flush();

        return $this;
    }

    public function getNewDueDate(): \DateTime
    {
        return $this->superMemoCalculator->getNewDueDate();
    }


}