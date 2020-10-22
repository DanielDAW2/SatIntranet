<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use App\Entity\PartNumber;
use Doctrine\ORM\EntityManager;
use App\Repository\PartNumberRepository;


class PartNumberTransformer implements DataTransformerInterface
{
    private $em;
    private $repo;
    
    private function __construct(EntityManager $em, PartNumberRepository $pn) {
        $this->em = $em;
    }

    public function transform($value)
    {
        if($value)
            return $value->getPartNumber();
    }

    public function reverseTransform($value)
    {
        $pn = $this->repo->findOneBy(["partnumber"=>$value]);
        if ($pn) {
            return $pn;
        }
        $pn = new PartNumber();
        $this->em->persist($pn);
        $this->em->flush();
        return $value;
    }
}

