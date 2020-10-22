<?php

namespace App\Form;

use App\Entity\Equipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Modelo;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\PartNumber;
use App\Entity\Marca;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\TipoEquipo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\DataTransformer\PartNumberToStringTransformer;
use App\Form\DataTransformer\MarcaToStringTransformer;
use App\Form\DataTransformer\ModeloToStringTransformer;

class EquipoType extends AbstractType
{
    private $transformer;
    private $marca;
    private $modelo;

    
    
    public function __construct(PartNumberToStringTransformer $transformer, MarcaToStringTransformer $mTransform, ModeloToStringTransformer $moTransformer)
    {
        $this->transformer = $transformer;
        $this->marca = $mTransform;
        $this->modelo = $moTransformer;

    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        
        $builder
            ->add('tipo_equipo',EntityType::class,[
                "class"=>TipoEquipo::class,
                "choice_label"=>"nombre",
                "multiple"=>false,
                "expanded"=>true
            ])
            ->add('n_serie', TextType::class,["label"=>"Número de serie"])
            ->add('color', TextType::class, ["label"=>"Color Base"])
            ->add('observaciones')
            ->add('partnumber', TextType::class)
            ->add('marca', TextType::class
                )
            ->add('color_lcd')
            ->add('top_cover',TextType::class,["label"=>"Top Cover / Marco de Movil"])
            ->add('back_cover', TextType::class,["label"=>"Back"])
            ->add('Modelo', TextType::class)
                ->add('tipo_pantalla',ChoiceType::class,["choices"=>[
                    "Selecciona una"=>null,
                    "Glossy"=>"glossy",
                    "Mate"=>"Mate",
                    "Táctil"=>"tactil"
                ]]
                    )
            ->add("tiquet", TextType::class,["label"=>"Tíquet Equipo","attr"=>["maxlenght"=>4]])    
            ->add('fechaCompraEquipo',DateTimeType::class, array("widget"=>"single_text","format"=>"dd/MM/yyyy","attr"=>["autocomplete"=>"off"])
                )
                    
        ;
                
         $builder->get('partnumber')
            ->addModelTransformer($this->transformer);
            $builder->get('marca')
            ->addModelTransformer($this->marca);
            $builder->get('Modelo')
            ->addModelTransformer($this->modelo);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipo::class,
        ]);
    }
}
