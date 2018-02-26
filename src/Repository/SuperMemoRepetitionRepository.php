<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\SuperMemoRepetition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class SuperMemoRepetitionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SuperMemoRepetition::class);
    }

    public function findAllForItem(Item $item): ?array {
        return $this->createQueryBuilder('smr')
            ->andWhere('smr.item = :item')
            ->setParameter('item', $item)
            ->orderBy('smr.repeatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
