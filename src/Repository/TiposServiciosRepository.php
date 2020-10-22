<?php

namespace App\Repository;

use App\Entity\TiposServicios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TiposServicios|null find($id, $lockMode = null, $lockVersion = null)
 * @method TiposServicios|null findOneBy(array $criteria, array $orderBy = null)
 * @method TiposServicios[]    findAll()
 * @method TiposServicios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiposServiciosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TiposServicios::class);
    }

    // /**
    //  * @return TiposServicios[] Returns an array of TiposServicios objects
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
    public function findOneBySomeField($value): ?TiposServicios
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
