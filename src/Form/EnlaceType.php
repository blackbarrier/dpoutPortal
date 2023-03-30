<?php

namespace App\Form;

use App\Entity\Contenido;
use App\Entity\Organismo;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EnlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                            'class' => 'form-control', 
                            'placeholder' => '',
                            'data-validation' => 'required'
                    ]
            ])
            ->add('url', TextType::class, [
                'label' => 'Url',
                'attr' => [
                            'class' => 'form-control', 
                            'placeholder' => '',
                            
                    ]
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => Tag::class,
                'choice_label'=>'nombre',
                'multiple' => true,
                'mapped' => true,

//                'query_builder' => function ($rTags) {
//                    return $rTags->createQueryBuilder('t')
//                        ->orderBy('t.nombre');
//                },
                'attr' => [
                    'class' => 'form-control select2',
                    'placeholder' => 'Tags',
                    'data-validation' => 'required'
                ]
            ])
            ->add('organismo', EntityType::class, [
                'label' => 'Organismo ',
                'attr' => [
                    'class' => 'form-control select2',
                    'data-validation' => 'required'
                ],
                'class' => Organismo::class,
                'required' => false,
                'placeholder' => 'Seleccione'
            ])
            ->add('activo', ChoiceType::class, [
                'label' => 'Estado', 
                'attr' =>[
                            'class' => 'form-control select2',
                            'data-validation' => 'required',
                    ],
                'placeholder' => false,
                'choices'  => [
                    'Activo' => 1,
                    'Bloqueado' => 0,
                ],
                
                
            ])
            ->add('texto', TextareaType::class, [
                'label' => 'contenido',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Contenido',
                    'data-validation' => 'required',

                ]
            ])    
            ->add('interno', ChoiceType::class, [
                'label' => '¿Interno?', 
                'attr' => [
                            'class' => 'form-control select2',
                            'data-validation' => 'required',
                           ],
                'placeholder' => false,
                'choices'  => [
                    'SI' => 1,
                    'NO' => 0,
                ],
                
                
            ])    
                
                ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contenido::class,
        ]);
    }
}
