<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @UniqueEntity("name")
 * @ExclusionPolicy("all")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     * @Expose
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item")
     * @var Item[]
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __clone()
    {
        $this->id = null;
        $this->items = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection|Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection|Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item) {
        if (!$this->items->contains($item)){
            $this->items->add($item);
        }
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item) {
        if ($this->items->contains($item)) {
            $this->items->remove($item);
        }
    }
}