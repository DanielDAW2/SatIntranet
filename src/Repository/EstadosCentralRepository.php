<?php

namespace App\Repository;

use App\Entity\EstadosCentral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadosCentral|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadosCentral|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadosCentral[]    findAll()
 * @method EstadosCentral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadosCentralRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadosCentral::class);
    }

    // /**
    //  * @return EstadosCentral[] Returns an array of EstadosCentral objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstadosCentral
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
