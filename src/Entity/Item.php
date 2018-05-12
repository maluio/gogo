<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use App\Utils\ObjectStorage;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ExclusionPolicy("all")
 */
class Item
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
     * @ORM\Column(type="text", nullable=true)
     * @var string
     * @Expose
     */
    private $question;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     * @Expose
     */
    private $answer;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     * @Expose
     *
     */
    private $dueAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="item", cascade="persist")
     * @ORM\OrderBy({"ratedAt" = "ASC"})
     * @var ArrayCollection|Rating[]
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SuperMemoRepetition", mappedBy="item", cascade="persist")
     * @ORM\OrderBy({"repeatedAt" = "DESC"})
     * @var ArrayCollection|SuperMemoRepetition[]
     */
    private $superMemoRepetitions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", cascade="persist")
     * @Expose
     * @var ArrayCollection|Category[]
     */
    private $categories;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isReminderSend = false;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @var array
     * @Expose
     */
    private $data;

    /**
     * Item constructor.
     */
    public function __construct()
    {
        $this->dueAt = new \DateTime();
        $this->ratings = new ArrayCollection();
        $this->superMemoRepetitions = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function __clone()
    {
       $this->id = null;

       foreach ($this->getSuperMemoRepetitions() as $smr){
           if(!isset(ObjectStorage::$superMemoMapping[$smr->getId()])){
               ObjectStorage::$superMemoMapping[$smr->getId()] = clone $smr;
           }
           $this->removeSuperMemoRepetition($smr);
           $this->addSuperMemoRepetition(ObjectStorage::$superMemoMapping[$smr->getId()]);
       }

        foreach ($this->getCategories() as $category){
            if(!isset(ObjectStorage::$categoryMapping[$category->getId()])){
                ObjectStorage::$categoryMapping[$category->getId()] = clone $category;
            }
            $this->removeCategory($category);
            $this->addCategory(ObjectStorage::$categoryMapping[$category->getId()]);

        }

        foreach ($this->ratings as $rating){
            if(!isset(ObjectStorage::$ratingMapping[$rating->getId()])){
                ObjectStorage::$ratingMapping[$rating->getId()] = clone $rating;
            }
            $this->removeRating($rating);
            $this->addRating(ObjectStorage::$ratingMapping[$rating->getId()]);
        }
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
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * @param string $question
     */
    public function setQuestion(?string $question): void
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(?string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return \DateTime
     */
    public function getDueAt(): \DateTime
    {
        return $this->dueAt;
    }

    /**
     * @param \DateTime $dueAt
     */
    public function setDueAt(\DateTime $dueAt): void
    {
        $this->dueAt = $dueAt;
    }

    /**
     * @return Rating[]|ArrayCollection
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param Rating[]|ArrayCollection $ratings
     */
    public function setRatings($ratings): void
    {
        $this->ratings = $ratings;
    }

    /**
     * @param Rating $rating
     */
    public function addRating(Rating $rating): void
    {
        $rating->setItem($this);
        $this->ratings->add($rating);
    }

    public function removeRating(Rating $rating){
        if($this->ratings->contains($rating)){
            $this->ratings->removeElement($rating);
        }
    }

    /**
     * @return SuperMemoRepetition[]|ArrayCollection
     */
    public function getSuperMemoRepetitions()
    {
        return $this->superMemoRepetitions;
    }

    /**
     * @param SuperMemoRepetition[]|ArrayCollection $superMemoRepetitions
     */
    public function setSuperMemoRepetitions($superMemoRepetitions): void
    {
        $this->superMemoRepetitions = $superMemoRepetitions;
    }

    /**
     * @param SuperMemoRepetition
     */
    public function addSuperMemoRepetition(SuperMemoRepetition $superMemoRepetition): void {
        $superMemoRepetition->setItem($this);
        $this->superMemoRepetitions->add($superMemoRepetition);
    }

    /**
     * @param SuperMemoRepetition $superMemoRepetition
     */
    public function removeSuperMemoRepetition(SuperMemoRepetition $superMemoRepetition){
        if($this->superMemoRepetitions->contains($superMemoRepetition)){
            $this->superMemoRepetitions->removeElement($superMemoRepetition);
        }
    }

    /**
     * @return string
     */
    public function questionRevealed(): string {
        return $this->question;
    }

    /**
     * @return ArrayCollection|Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection|Category[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category){
        if(!$this->categories->contains($category)){
            $category->addItem($this);
            $this->categories->add($category);
        }
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category){
        if($this->categories->contains($category)){
            $this->categories->removeElement($category);
        }
    }

    /**
     * @return bool
     */
    public function isReminderSend(): bool
    {
        return $this->isReminderSend;
    }

    /**
     * @param bool $isReminderSend
     */
    public function setIsReminderSend(bool $isReminderSend): void
    {
        $this->isReminderSend = $isReminderSend;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }
}
