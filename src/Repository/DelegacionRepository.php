<?php

namespace App\Repository;

use App\Entity\Delegacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Delegacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delegacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delegacion[]    findAll()
 * @method Delegacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Delegacion::class);
    }

    public function findByName($name)
    {
       return $this->createQueryBuilder("d")
        ->select("d.id")
        ->andWhere("d.nombre LIKE :val")
        ->setParameter("val","%$name%")
        ->getQuery()->getResult();
    }

    // /**
    //  * @return Delegacion[] Returns an array of Delegacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Delegacion
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
