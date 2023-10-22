<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

   /**
    * @return Card[] Returns an array of Card objects
    */
   public function getTotalCards(): int
   {
        $qb = $this->createQueryBuilder('c');
        $qb->select($qb->expr()->count('c.id'));
        $totalCards = (int) $qb->getQuery()->getSingleScalarResult();

        return $totalCards;
   }

   public function searchByName(string $name): ?array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.name LIKE :val')
           ->setParameter('val', "%$name%")
           ->getQuery()
           ->getResult()
       ;
   }
}
