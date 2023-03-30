<?php

namespace App\Form;

use App\Entity\TipoAdjunto;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdjuntosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
                ->setAttributes(['autocomplete'=>'off','enctype'=>'multipart/form-data'])

                ->add('tipoAdjunto',EntityType::Class, array(
                            'label' => 'Tipo Adjunto',
                            'required' => false, 
                            'class' => 'App\Entity\TipoAdjunto',
                            'placeholder' => 'Seleccione',
                            'choice_label' => 'descripcion',
                            'attr' => ['class' => 'form-control', 
                                       'title'=>'Tipo Adjunto',
                                       
                                       'data-validation'=>"required",
                                       ]
                            ))    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
