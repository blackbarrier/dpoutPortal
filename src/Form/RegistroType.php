<?php

namespace App\Form;

use App\Entity\ProveedorProtocolo;
use App\Entity\Rol;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttributes(['autocomplete'=>'off'])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                //'require'=>false,
                'attr' => ['class' => 'form-control', 
                           'placeholder' => 
                           'Documento Nacional de Identidad',
                           //'data-validation'=>"required number length",
                           //'data-validation-length'=>'4-8'
                            ]
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-control', 
                           'placeholder' => 'Nombre del Usuario',
                  
                           'data-validation'=>"length custom",
                           'data-validation-regexp'=>"^([a-zA-ZáéíóúÁÉÍÓÚñÑçÇäëïöüÄËÏÖÜâêîôûÂÊÎÔÛàèìòùÀÈÌÒÙ\s\']+)$",
                           'data-validation-length'=>'3-30'
                    ]
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido',
                'attr' => ['class' => 'form-control', 
                           'placeholder' => 'Apellido del Usuario',
                    
                           'data-validation'=>"length custom",
                           'data-validation-regexp'=>"^([a-zA-ZáéíóúÁÉÍÓÚñÑçÇäëïöüÄËÏÖÜâêîôûÂÊÎÔÛàèìòùÀÈÌÒÙ\s\']+)$",
                           'data-validation-length'=>'3-30'
                           ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control', 
                           'placeholder' => 'Email del Usuario',
                           'data-validation'=>"email"
                        ]
            ])
            ->add('funeraria', EntityType::Class, array(
                    'label' => 'Cocheria',
                    'required' => true,
                    'mapped' => false,
                    'class' => 'App\Entity\Funerarias',
//                    'placeholder' => 'Seleccione',
                    'choice_label' => 'razonSocial',
                    'attr' => ['class' => 'form-control',
                        'title' => 'Funeraria',
                    ]
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
