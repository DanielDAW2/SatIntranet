<?php

namespace App\Repository;

use App\Entity\PartNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PartNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartNumber[]    findAll()
 * @method PartNumber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartNumberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PartNumber::class);
    }

    // /**
    //  * @return PartNumber[] Returns an array of PartNumber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PartNumber
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
