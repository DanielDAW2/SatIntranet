<?php

namespace App\Form;

use App\Entity\PartNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Marca;
use App\Entity\Modelo;

class PartNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('partnumber')
            ->add('marca', EntityType::class,array("class"=>Marca::class,"choice_label"=>"nombre"))
            ->add('Modelo', EntityType::class,array("class"=>Modelo::class,"choice_label"=>"nombre"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PartNumber::class,
        ]);
    }
}
