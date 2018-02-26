<?php


namespace App\Learn\SuperMemo;

use App\Exception\InitializationException;
use App\Utils\DateTimeProvider;

/**
 *
 * See https://www.supermemo.com/english/ol/sm2.htm
 *
 * 1. Split the knowledge into smallest possible items.
 * 2. With all items associate an E-Factor equal to 2.5.
 * 3. Repeat items using the following intervals:
 * I(1):=1
 * I(2):=6
 * for n>2: I(n):=I(n-1)*EF
 * where:
 * I(n) - inter-repetition interval after the n-th repetition (in days),
 * EF - E-Factor of a given item
 * If interval is a fraction, round it up to the nearest integer.
 * 4. After each repetition assess the quality of repetition response in 0-5 grade scale:
 * 5 - perfect response
 * 4 - correct response after a hesitation
 * 3 - correct response recalled with serious difficulty
 * 2 - incorrect response; where the correct one seemed easy to recall
 * 1 - incorrect response; the correct one remembered
 * 0 - complete blackout.
 * 5. After each repetition modify the E-Factor of the recently repeated item according to the formula:
 * EF':=EF+(0.1-(5-q)*(0.08+(5-q)*0.02))
 * where:
 * EF' - new value of the E-Factor,
 * EF - old value of the E-Factor,
 * q - quality of the response in the 0-5 grade scale.
 * If EF is less than 1.3 then let EF be 1.3.
 * 6. If the quality response was lower than 3 then start repetitions for the item from the beginning without changing the E-Factor (i.e. use intervals I(1), I(2) etc. as if the item was memorized anew).
 * 7. After each repetition session of a given day repeat again all items that scored below four in the quality assessment. Continue the repetitions until all of these items score at least four.
 */
class SuperMemoCalculator
{

    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    /**
     * @var int
     */
    private $quality;

    /**
     * @var int
     */
    private $oldInterval;

    /**
     * @var float
     */
    private $oldEFactor;

    /**
     * @var int
     */
    private $newInterval;


    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var float
     */
    private $newEFactor;

    public function __construct(DateTimeProvider $dateTimeProvider)
    {
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function init(int $quality, int $oldInterval = 0, float $oldEFactor = 2.5): void
    {

        if ($quality < 0 || $quality > 5) {
            throw new \InvalidArgumentException('Quality');
        }

        if ($oldInterval < 0) {
            throw new \InvalidArgumentException('Interval');
        }

        if ($oldEFactor < 0) {
            throw new \InvalidArgumentException('EFactor');
        }

        $this->quality = $quality;
        $this->oldInterval = $oldInterval;
        $this->oldEFactor = $oldEFactor;

        $this->calcInterval();
        $this->calcNewEFactor();

        $this->initialized = true;
    }

    public function getNewInterval(): int
    {
        if (false === $this->initialized) {
            throw new InitializationException('Not initialized');
        }

        return $this->newInterval;
    }

    public function getNewEFactor(): float
    {
        if (false === $this->initialized) {
            throw new InitializationException('Not initialized');
        }

        return $this->newEFactor;
    }

    public function getNewDueDate(): \DateTime
    {

        if (false === $this->initialized) {
            throw new InitializationException('Not initialized');
        }

        if ($this->quality < 4) {
            return $this->dateTimeProvider->now();
        }

        $nextReview = sprintf('+ %d days', $this->newInterval);
        return $this->dateTimeProvider->fromString($nextReview);
    }

    private function calcInterval()
    {
        if ($this->quality < 3) {
            $this->newInterval = 0;
            return;
        }

        if (0 === $this->oldInterval) {
            $this->newInterval = 1;
            return;
        }

        if (1 === $this->oldInterval) {
            // according to the algo, this should be 6, but 2 seems more reasonable for a new item
            $this->newInterval = 2;
            return;
        }

        $interval = $this->oldInterval * $this->oldEFactor;

        $this->newInterval = ceil($interval);
    }

    private function calcNewEFactor()
    {
        if ($this->quality < 3) {
            $this->newEFactor = $this->oldEFactor;
            return;
        }

        $newEFactor = $this->oldEFactor + (0.1 - (5 - $this->quality) * (0.08 + (5 - $this->quality) * 0.02));

        $this->newEFactor = max($newEFactor, 1.3);
    }
}
