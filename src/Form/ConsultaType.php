<?php

namespace App\Form;

use App\Entity\Consulta;
use App\Entity\Partidos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ConsultaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->setAttributes(['autocomplete' => 'off', 'enctype' => 'multipart/form-data'])
                ->add('apellido', TextType::Class, array(
                    'label' => 'Apellido ',
                      'label_attr' => array('id' => 'apellido'),
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Apellido',
                        'title' => 'Apellido',
                        'data-validation' => "length custom",
                       // 'data-validation-regexp' => "^([a-zA-ZáéíóúÁÉÍÓÚñÑçÇäëïöüÄËÏÖÜâêîôûÂÊÎÔÛàèìòùÀÈÌÒÙ\s\']+)$",
                        'data-validation-length' => '0-30'
                    ]
                ))
                ->add('nombre', TextType::Class, array(
                    'label' => 'Nombre ',
                    'label_attr' => array('id' => 'nombre'),
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nombre',
                        'title' => 'Nombre',
                        'data-validation' => " length ",
                       // 'data-validation-regexp' => "^([a-zA-ZáéíóúÁÉÍÓÚñÑçÇäëïöüÄËÏÖÜâêîôûÂÊÎÔÛàèìòùÀÈÌÒÙ\s\']+)$",
                        'data-validation-length' => '0-30'
                    ]
                ))
                ->add('dni', TextType::class, [
                    'label' => 'DNI ',
                     'label_attr' => array('id' => 'dni'),
                    'required' => false,
                    'attr' => ['class' => 'form-control',
                        'placeholder' => 'Documento Nacional de Identidad',
                        'data-validation' => " length ",
                        'data-validation-length' => '0-8']
                ])

                ->add('barrio', TextType::Class, array(
                    'label' => 'Nombre  urbanización cerrada ',
                     'label_attr' => array('id' => 'barrio'),
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nombre urbanización cerrada',
                        'title' => 'Nombre urbanización cerrada',
                        'data-validation' => " length custom",
                        'data-validation-length' => '0-30'
                    ]
                ))
            
                ->add('mail', TextType::Class, array(
                    'label' => 'E-mail',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'E-mail',
                        'title' => 'E-mail',
//                        'data-validation' => " email",
                    ]
                ))
//                ->add('mail_confirm', TextType::Class, array(
//                    'label' => 'Confirmación E-mail  *',
//                    'required' => false,
//                    'mapped' => false,
//                    'attr' => [
//                        'class' => 'form-control',
//                        'placeholder' => 'E-mail',
//                        'title' => 'Confirmación domicilio electrónico constituido',
//                        'data-validation' => "required email confirmation",
//                        'data-validation-confirm' => "consulta[mail]"
//                    ]
//                ))
                ->add('telefonoContacto', TextType::Class, array(
                    'label' => 'Celular ',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Teléfono',
                        'title' => 'Teléfono',
                        'data-validation' => " length",
                        'data-validation-length' => '0-100'
                    ]
                ))
                ->add('domicilio', TextType::Class, [
                    'label' => 'Domicilio ',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Domicilio',
                        'title' => 'Domicilio',
                        'data-validation' => " length",
                        'data-validation-length' => '0-100'
                    ]
                ])
                ->add('observaciones', TextareaType::class, [
                    'label' => 'Observación',
                    'attr' => ['class' => 'form-control',
                        'placeholder' => 'Observaciones',
                        'title' => 'Observaciones',
                        'data-validation' => "required",
                    ],
                    'required' => false,
                ])
   
 
                ->add('partido', EntityType::class, [
                    'label' => 'Partido ',
                    'attr' => [
                        'class' => 'form-control select2',
//                        'data-validation' => 'required'
                    ],
                    'class' => Partidos::class,
                    'required' => false,
                    'placeholder' => 'Seleccione'
                ])
           

        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Consulta::class,
        ]);
    }

}
