<?php

namespace App\Repository;

use App\Entity\TiquetFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TiquetFactura|null find($id, $lockMode = null, $lockVersion = null)
 * @method TiquetFactura|null findOneBy(array $criteria, array $orderBy = null)
 * @method TiquetFactura[]    findAll()
 * @method TiquetFactura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiquetFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TiquetFactura::class);
    }
    
    

    // /**
    //  * @return TiquetFactura[] Returns an array of TiquetFactura objects
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
    public function findOneBySomeField($value): ?TiquetFactura
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
