<?php

namespace App\Repository;

use App\Entity\Situacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Situacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Situacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Situacion[]    findAll()
 * @method Situacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SituacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Situacion::class);
    }

    // /**
    //  * @return Situacion[] Returns an array of Situacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Situacion
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
