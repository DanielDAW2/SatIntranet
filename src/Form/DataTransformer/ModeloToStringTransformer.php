<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PartNumber;
use App\Entity\Marca;
use App\Entity\Modelo;

class ModeloToStringTransformer implements DataTransformerInterface
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
            return null;
        }
        
        return $value->getNombre();
    }

    public function reverseTransform($value)
    {
        $modelo = $this->entityManager->getRepository(Modelo::class)->findOneBy(["nombre"=>$value]);
       
        if($modelo === null)
        {
            $modelo = new Modelo();
            $modelo->setNombre($value);
            $modelo->setMarca($this->entityManager->getRepository(Marca::class)->findOneBy(["nombre"=>$this->req['ordentrabajo']['equipo']['marca']]));
            $modelo->setVisible(false);
            $this->entityManager->persist($modelo);
            $this->entityManager->flush();
        }
        return $modelo;

    }
}

