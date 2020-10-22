<?php

namespace App\Form;

use App\Entity\Tiquets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;

class TiquetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class, ["label"=>"Tiquet Presupuesto",
                'attr'=>[
                    'size'=>'4','maxlength'=>'4',"autocomplete"=>"off"
                ],
                'constraints' => new Length(['min' => 4,'max'=> 4]),
            ])
            ->add('fecha', DateTimeType::class, array("widget"=>"single_text","format"=>"dd/MM/yyyy","attr"=>["pattern"=>"(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}","autocomplete"=>"off"],"label"=>"Fecha del tiquet (dd/mm/yyyy)"))
            ->add('Cupon', CheckboxType::class,["required"=>false])
            ->add('cobrado', CheckboxType::class,["required"=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tiquets::class,
        ]);
    }
}
