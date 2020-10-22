<?php

namespace App\Repository;

use App\Entity\Servicios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\User;


/**
 * @method Servicios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Servicios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Servicios[]    findAll()
 * @method Servicios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiciosRepository extends ServiceEntityRepository
{


    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Servicios::class);
    }
    
    public function findByService($param,$delegacion) {
        return $this->createQueryBuilder("a")
        ->select()
        ->innerJoin("a.tipo","p")
        ->innerJoin("a.Solicitante","d")
        ->andWhere("p.slug = :slug")
        ->andWhere("a.Solicitante IS NOT NULL")
        ->andWhere("d.delegacion = :clinica")
        ->setParameter("slug", $param)
        ->setParameter("clinica", $delegacion)
        ->getQuery()
        ->getResult();
    }
    
    public function getCodeFree() {
        return $this->createQueryBuilder("p")
        ->select()
        ->andWhere("p.Solicitante IS NULL")
        ->setMaxResults(1)
        ->getQuery()
        ->getSingleResult()
        ;
    }

    // /**
    //  * @return Servicios[] Returns an array of Servicios objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Servicios
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
