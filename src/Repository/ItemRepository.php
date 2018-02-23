<?php

namespace App\Repository;

use App\Entity\Item;
use App\Utils\DateTimeFormatHelper;
use App\Utils\DateTimeProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    public function __construct(RegistryInterface $registry, DateTimeProvider $dateTimeProvider)
    {
        parent::__construct($registry, Item::class);
        $this->dateTimeProvider = $dateTimeProvider;
    }

    /**
     * @return Item|null
     */
    public function findLatestDue(): ?Item {
        $now = $this->dateTimeProvider->now();

        $result = null;

        try{
            $result = $this->createQueryBuilder('item')
                ->andWhere('item.dueAt < :now')
                ->setParameter('now', $now->format(DateTimeFormatHelper::DB_DATE_TIME))
                ->orderBy('item.dueAt', 'DESC')
                // this kinda works around the NonUniqueResultException, but it needs to be catches in any case
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
        catch (NonUniqueResultException $e){}

        return  $result;
    }

    public function getNumberOfDueItems(): int {
        $now = $this->dateTimeProvider->now();

        try{
            return $this->createQueryBuilder('item')
                ->select('count(item.id)')
                ->andWhere('item.dueAt < :now')
                ->setParameter('now', $now->format(DateTimeFormatHelper::DB_DATE_TIME))
                ->getQuery()
                ->getSingleScalarResult();
        }
        catch (NonUniqueResultException $e) {
            return 0;
        }
    }

}
