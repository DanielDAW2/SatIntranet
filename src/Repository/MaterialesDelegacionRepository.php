<?php

namespace App\Repository;

use App\Entity\MaterialesDelegacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaterialesDelegacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialesDelegacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialesDelegacion[]    findAll()
 * @method MaterialesDelegacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialesDelegacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaterialesDelegacion::class);
    }

    // /**
    //  * @return MaterialesDelegacion[] Returns an array of MaterialesDelegacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialesDelegacion
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
