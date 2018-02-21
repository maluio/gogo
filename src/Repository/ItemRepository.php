<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }


    /**
     * @return Item|null
     */
    public function findLatestDue(): ?Item {
        $now = new \DateTime();

        $result = null;

        try{
            $result = $this->createQueryBuilder('item')
                ->andWhere('item.dueAt > :now')
                ->setParameter('now', $now->format('Y-m-d H:i:s'))
                ->orderBy('item.dueAt', 'DESC')
                // this kinda works around the NonUniqueResultException, but it needs to be catches in any case
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
        catch (NonUniqueResultException $e){}

        return  $result;
    }

}
