<?php

namespace App\Repository;

use App\Entity\Tiquets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tiquets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tiquets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tiquets[]    findAll()
 * @method Tiquets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiquetsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tiquets::class);
    }

    // /**
    //  * @return Tiquets[] Returns an array of Tiquets objects
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
    public function findOneBySomeField($value): ?Tiquets
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
