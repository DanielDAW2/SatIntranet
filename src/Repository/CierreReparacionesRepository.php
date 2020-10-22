<?php

namespace App\Repository;

use App\Entity\CierreReparaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CierreReparaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method CierreReparaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method CierreReparaciones[]    findAll()
 * @method CierreReparaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CierreReparacionesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CierreReparaciones::class);
    }

    // /**
    //  * @return CierreReparaciones[] Returns an array of CierreReparaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CierreReparaciones
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
