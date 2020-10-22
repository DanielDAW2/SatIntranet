<?php

namespace App\Repository;

use App\Entity\EstadosClinica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadosClinica|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadosClinica|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadosClinica[]    findAll()
 * @method EstadosClinica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadosClinicaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadosClinica::class);
    }

    // /**
    //  * @return EstadosClinica[] Returns an array of EstadosClinica objects
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
    public function findOneBySomeField($value): ?EstadosClinica
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
