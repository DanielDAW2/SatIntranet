<?php

namespace App\Form;

use App\Entity\Delegacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DelegacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('direccion')
            ->add('codigopostal')
            ->add('provincia')
            ->add('telefono')
            ->add('contacto')
            ->add('email')
            ->add('idTr')
            ->add('poblacion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delegacion::class,
        ]);
    }
}
