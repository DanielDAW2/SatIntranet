<?php

namespace App\Repository;

use App\Entity\NotasServicios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NotasServicios|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotasServicios|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotasServicios[]    findAll()
 * @method NotasServicios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotasServiciosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NotasServicios::class);
    }

    // /**
    //  * @return NotasServicios[] Returns an array of NotasServicios objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotasServicios
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
