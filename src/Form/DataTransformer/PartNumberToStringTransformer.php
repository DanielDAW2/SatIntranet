<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PartNumber;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Marca;
use App\Entity\Modelo;

class PartNumberToStringTransformer implements DataTransformerInterface
{

    private $entityManager;
    private $req;
    
    public function __construct(EntityManagerInterface $i)
    {
        $this->entityManager = $i;
        $this->req = $_POST;
    }
    
    public function transform($value)
    {
        
        if(null === $value)
        {
            return'';
        }
        
        return $value->getPartNumber();
    }

    public function reverseTransform($value)
    {

        $pn = $this->entityManager->getRepository(PartNumber::class)->findOneBy(["partnumber"=>$value]);
        
        if(!$pn)
        {
            $pn = new PartNumber();
            $pn->setPartnumber($value);
            
            $marca = $this->entityManager->getRepository(Marca::class)->findOneBy(["nombre"=>strtoupper($this->req["ordentrabajo"]["equipo"]['marca'])]);
            if(!$marca)
            {
                $marca = new Marca();
                $marca->setNombre($this->req["ordentrabajo"]["equipo"]['marca']);
                $marca->setVisible(false);
                $this->entityManager->persist($marca);
            }
            $modelo = $this->entityManager->getRepository(Modelo::class)->findOneBy(["nombre"=>strtoupper($this->req["ordentrabajo"]["equipo"]['Modelo'])]);
            if(!$modelo)
            {
                $modelo = new Modelo();
                $modelo->setMarca($marca);
                $modelo->setNombre($this->req["ordentrabajo"]["equipo"]['Modelo']);
                $this->entityManager->persist($modelo);
            }
            $pn->setModelo($modelo);
            $pn->setMarca($marca);
            $this->entityManager->persist($pn);
            $this->entityManager->flush();
        }
        
        return $pn;
        
        
        
    }
}

