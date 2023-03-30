<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\Extension\Core\Type\NumberType;
//use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class CambiarPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::Class, 
                    array('label' => 'Password Actual', 
                           'required' => 'required', 
                           'always_empty'=>true, 
                           'attr' => ['class' => 'form-control', 
                                      'placeholder' => 'password actual',
                                      'data-validation'=>"length",
                                      'data-validation-length'=>'3-30'
                                    ]))
            ->add('nuevaPassword', PasswordType::Class, 
                    array('label' => 'Nueva Password', 
                           'required' => 'required', 
                           'mapped'=>false, 
                           'always_empty'=>true,  
                           'attr' => ['class' => 'form-control', 
                                      'placeholder' => 'Nueva Password',
                                      'data-validation'=>"length",
                                      'data-validation-length'=>'3-30'
                                    ]))
            ->add('repetirPassword', PasswordType::Class, 
                    array('label' => 'Repetir Password', 
                           'required' => 'required', 
                           'mapped'=>false,  
                           'always_empty'=>true, 
                           'attr' => ['class' => 'form-control', 
                                      'placeholder' => 'repetir password',
                                      'data-validation'=>"length",
                                      'data-validation-length'=>'3-30'
                                    ]))    
            
                
            ->add('boton', SubmitType::class, array('label'=>'Guardar','attr' => array('class' => 'btn btn-outline-success  pull-center')))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           //'data_class' => Usuario::class,
           'data_class' => null
        ]);
    }
}
