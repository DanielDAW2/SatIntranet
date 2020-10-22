<?php

namespace App\Form;

use App\Entity\Ordentrabajo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use App\Entity\Serie;
use App\Entity\Situacion;
use App\Entity\Usuario;
use App\Entity\Marca;
use App\Entity\Modelo;
use App\Entity\PartNumber;
use App\Entity\Prioridad;
use App\Entity\Tipo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\PartNumberToStringTransformer;
use App\Form\DataTransformer\MarcaToStringTransformer;
use Doctrine\Common\Collections\Collection;
use App\Form\DataTransformer\ModeloToStringTransformer;

class OrdentrabajoType extends AbstractType
{
   
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder
            ->add('n_caso',null,array("label"=>"Número de caso","attr"=>["maxlength"=>8]))
            ->add('tiquet',TiquetsType::class, ["label"=>"Tiquet Diagnóstico"])
            ->add('situacion', EntityType::class,
            [
                "class"=>Situacion::class,
                "choice_label"=>"nombre",
            ]
            )
            ->add('n_orden',null,array("label"=>"Número de orden","attr"=>array("readonly"=>true)))
            ->add('fecha_entrada', DateTimeType::class, array("widget"=>"single_text","format"=>"dd/MM/yyyy","attr"=>["autocomplete"=>"off"]))
            ->add('indicaciones_cliente', TextareaType::class,
            [
                "attr"=>[
                            "rows"=>5
                        ]

            ])
            ->add('averias_detectadas', TextareaType::class,[
                "attr"=>[
                    "rows"=>5
                ],
                "label"=>"Averias detectadas / Diagnóstico"
            ])
            ->add('TrabajosaRealizar', TextareaType::class,[
                "label"=>"Trabajos a realizar / Piezas a solicitar",
                "required"=>false,
                "attr"=>[
                    "rows"=>5
                ]
            ])
            ->add('accesorios')
            ->add('usuarios', EntityType::class, 
                    [
                        'class'=>Usuario::class,
                        'choices'=> $options["data"]->getDelegacion() ? $options["data"]->getDelegacion()->getUsuarios() : $options["user"]->getDelegacion()->getUsuarios(),
                        'choice_label' => 'nombre',
                        'multiple'     => true,
                        'expanded'=>true,
                        "label"=>"Técnicos:"
                    ])
            ->add('prioridad', EntityType::class, 
                    [
                        'class'=>Prioridad::class,
                        'choice_label' => 'nombre',
                        'choice_value' => 'id',
                    ])
            ->add('observaciones',TextareaType::class,[
                'attr'=>[
                    'size'=>'90','maxlength'=>'90',"autocomplete"=>"off"
                ],
                "required"=>false
            ])
            ->add('observaciones_int',TextareaType::class,[
                "label"=> "Observaciones Internas",
                "required" => false,
            ])
            ->add('equipo', EquipoType::class)
            ;  
            
            
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ordentrabajo::class,
            'user'=>null
        ]);
    }
}
