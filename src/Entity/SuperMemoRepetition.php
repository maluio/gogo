<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="super_memo_repetition")
 */
class SuperMemoRepetition
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $interval;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $factor;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $repeatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="superMemoRepetitions")
     * @var Item
     */
    private $item;

    public function __construct()
    {
        $this->repeatedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval(int $interval): void
    {
        $this->interval = $interval;
    }

    /**
     * @return float
     */
    public function getFactor(): float
    {
        return $this->factor;
    }

    /**
     * @param float $factor
     */
    public function setFactor(float $factor): void
    {
        $this->factor = $factor;
    }

    /**
     * @return \DateTime
     */
    public function getRepeatedAt(): \DateTime
    {
        return $this->repeatedAt;
    }

    /**
     * @param \DateTime $repeatedAt
     */
    public function setRepeatedAt(\DateTime $repeatedAt): void
    {
        $this->repeatedAt = $repeatedAt;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item): void
    {
        $this->item = $item;
    }
}