<?php

namespace App\Repository;

use App\Entity\Ordentrabajo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Ordentrabajo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordentrabajo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordentrabajo[]    findAll()
 * @method Ordentrabajo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdentrabajoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ordentrabajo::class);
    }
    
    public function paginate($dql, $page, $end)
    {
        $paginator = new Paginator($dql);
        $paginator->getQuery()->setFirstResult($end * ($page - 1))->setMaxResults($end);
        return $paginator;
    }

    // /**
    //  * @return Ordentrabajo[] Returns an array of Ordentrabajo objects
    //  */
    public function findByDelegacion($delegacion, $limit, $currenPage = 1, $filters)
    {
        $result = $this->createQueryBuilder('o')
            ->andWhere('o.delegacion = :val')
            ->setParameter('val', $delegacion)
            ->orderBy('o.fecha_entrada', 'DESC');
        foreach($filters as $filter => $value)
        {
            if($value)
            {
                $result->andWhere("o.$filter = $value");
            }
            
        }
        $result->getQuery();
        
        $paginator = $this->paginate($result, $currenPage, $limit);

        return array('paginator' => $paginator, 'query' => $result);
    }
    
    public function findAllp($currenPage, $limit, $filters)
    {
        $result = $this->createQueryBuilder('o')
            ->orderBy('o.fecha_entrada', 'DESC');
        foreach($filters as $filter => $value)
        {
            if($value)
            {
                $result->andWhere("o.$filter = $value");
            }
            
        }
        $result->getQuery();

        $paginator = $this->paginate($result, $currenPage, $limit);

        return array('paginator' => $paginator, 'query' => $result);
    }
    
    public function reset($clinica=null) {
        if($clinica)
        {
            return $this->createQueryBuilder("d")
            ->delete()
            ->andWhere("d.delegacion = :val")
            ->andWhere("d.Actualizar = :update")
            ->setParameters(array("val"=> $clinica,"update"=>true))
            ->getQuery()
            ->execute();
            ;
        }
        return $this->createQueryBuilder("d")
        ->delete()
        ->andWhere("d.Actualizar = :val")
        ->setParameter("val", true)
        ->getQuery()
        ->execute();
        ;

    }

    /*
    public function findOneBySomeField($value): ?Ordentrabajo
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
