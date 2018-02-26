<?php


namespace App\Learn;


use App\Entity\Item;
use App\Exception\InitializationException;
use App\Learn\SuperMemo\SuperMemoHandler;
use App\Utils\DateTimeProvider;

class LearnHandler
{

    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    private $item = null;

    private $rating = null;
    /**
     * @var SuperMemoHandler
     */
    private $superMemoHandler;

    public function __construct(DateTimeProvider $dateTimeProvider, SuperMemoHandler $superMemoHandler)
    {
        $this->dateTimeProvider = $dateTimeProvider;
        $this->superMemoHandler = $superMemoHandler;
    }

    public function handle(Item $item, int $learnRating): LearnHandler
    {
        $this->item = $item;
        $this->rating = $learnRating;

        $this->superMemoHandler->handle($item, $learnRating);

        return $this;
    }

    public function getNewDueDate(): \DateTime
    {
        if(null === $this->rating || null === $this->item){
            throw new InitializationException('Handler not initialized via handle()');
        }

        $interval = $this->superMemoHandler->getNewInterval();

        $nextReview = sprintf('+ %d days', $interval);

        return $this->dateTimeProvider->fromString($nextReview);
    }

}