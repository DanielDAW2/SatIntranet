<?php

namespace App\Repository;

use App\Entity\Prioridad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Prioridad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prioridad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prioridad[]    findAll()
 * @method Prioridad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrioridadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prioridad::class);
    }

    // /**
    //  * @return Prioridad[] Returns an array of Prioridad objects
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
    public function findOneBySomeField($value): ?Prioridad
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
