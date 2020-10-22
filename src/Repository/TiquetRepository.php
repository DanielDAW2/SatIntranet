<?php

namespace App\Repository;

use App\Entity\Tiquet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tiquet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tiquet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tiquet[]    findAll()
 * @method Tiquet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiquetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tiquet::class);
    }

    // /**
    //  * @return Tiquet[] Returns an array of Tiquet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tiquet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
