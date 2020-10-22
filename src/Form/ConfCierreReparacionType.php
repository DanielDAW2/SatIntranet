<?php

namespace App\Form;

use App\Entity\ConfCierreReparacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Situacion;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ConfCierreReparacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('SituacionOrigen', EntityType::class, ["class"=>Situacion::class,"choice_label"=>"nombre"])
            ->add('SituacionFinal', EntityType::class, ["class"=>Situacion::class,"choice_label"=>"nombre"])
            ->add("pordefecto", CheckboxType::class, ["label"=>"Utilizar esta configuración en caso de duplicado en situacion origen.","required"=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConfCierreReparacion::class,
        ]);
    }
}
