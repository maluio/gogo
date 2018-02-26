<?php


namespace App\Learn;


use App\Entity\Item;

interface LearnHandlerInterface
{
    public function handle(Item $item, int $learnRating): LearnHandlerInterface;

    public function getNewDueDate(): \DateTime;
}