<?php

namespace App\Form;

use App\Entity\Materiales;
use App\Entity\EstadosCentral;
use App\Entity\EstadosClinica;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MaterialesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('estado_central', EntityType::class,
                    [
                        'class'=>EstadosCentral::class,
                        "choice_label"=>"nombre"
                    ])
            ->add('estado_clinica', EntityType::class,
                    [
                        'class'=>EstadosClinica::class,
                        "choice_label"=>"nombre"
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiales::class,
        ]);
    }
}
