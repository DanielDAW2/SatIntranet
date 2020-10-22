<?php

namespace App\Form;

use App\Entity\CierreReparaciones;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CierreReparacionesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NOrden')
            ->add('fecha')
            ->add('comentario')
            ->add('Reparacion')
            ->add('TiquetCierre')
            ->add('Usuario')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CierreReparaciones::class,
        ]);
    }
}
