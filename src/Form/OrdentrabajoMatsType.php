<?php

namespace App\Form;

use App\Entity\Ordentrabajo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class OrdentrabajoMatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('materiales', CollectionType::class, array(
            'entry_type' => MaterialesType::class,
            'entry_options' => array('label' => "Materiales"),
        ));
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ordentrabajo::class,
        ]);
    }
}
