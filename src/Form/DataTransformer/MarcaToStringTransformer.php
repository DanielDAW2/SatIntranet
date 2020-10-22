<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PartNumber;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Marca;

class MarcaToStringTransformer implements DataTransformerInterface
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
        if($value === null)
        {
            return "";
        }
        return $value->getNombre();
    }

    public function reverseTransform($value)
    {
        $marca = $this->entityManager->getRepository(Marca::class)->findOneBy(["nombre"=>$value]);
        if($marca === null)
        {
            $marca = new Marca();
            $marca->setNombre($value);
            $marca->setVisible(false);
            $this->entityManager->persist($marca);
            $this->entityManager->flush();
        }

        
        return $marca;
        
        
        
    }
}

