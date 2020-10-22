<?php

namespace App\Repository;

use App\Entity\ConfCierreReparacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ConfCierreReparacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfCierreReparacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfCierreReparacion[]    findAll()
 * @method ConfCierreReparacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfCierreReparacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConfCierreReparacion::class);
    }
    public function getSituacion($situacion, $default = true)
    {
        return $this->createQueryBuilder("s")
        ->select()
        ->andWhere("s.SituacionOrigen = :situacion")
        ->andWhere("s.pordefecto = :defecto")
        ->setParameters(["defecto" => $default,"situacion"=>$situacion])
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }
    // /**
    //  * @return ConfCierreReparacion[] Returns an array of ConfCierreReparacion objects
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
    public function findOneBySomeField($value): ?ConfCierreReparacion
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
