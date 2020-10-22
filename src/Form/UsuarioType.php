<?php

namespace App\Form;

use App\Entity\Usuario;
use App\Entity\Delegacion;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Doctrine\DBAL\Types\TextType;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('nombre')
            ->add('apellidos')
            ->add('isactive', CheckboxType::class, ["label"=>"Usuario Activo","required"=>false])
            ->add('delegacion', EntityType::class,
            [
                'class'=> Delegacion::class,
                'choice_label'=>'nombre'
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                "label"=>"Cambiar Contraseña",
                "required"=>false,
                
                    ])
                    ->add('roles', ChoiceType::class,["choices"=>["Central"=>"ROLE_CENTRAL","Técnico"=>"ROLE_TECNIC","Usuario de fnac"=>"ROLE_USER"],"multiple"=>true,"expanded"=>"false"])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
