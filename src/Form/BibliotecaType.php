<?php

namespace App\Form;

use App\Entity\Contenido;
use App\Entity\Organismo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BibliotecaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Titulo del contenido',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'data-validation' => 'required',
                ]
            ])
            ->add('url', UrlType::class, [
                'label' => 'Url',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Url'
                ]
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => 'App\Entity\Tag',
                'choice_label'=>'nombre',
                'multiple' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Tags'
                ]
            ])
            ->add('contacto', TextType::class, [
                'label' => 'Mail de contacto',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Contacto']
            ])
            ->add('texto', TextareaType::class, [
                'label' => 'contenido',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Contenido',
                ]
            ])
            ->add('activo', ChoiceType::class, [
                'label' => 'Estado', 'attr' => ['class' => 'form-control select2'],
                'placeholder' => 'Seleccione',
                'choices'  => [
                    'Activo' => 1,
                    'Bloqueado' => 0,
                ],
            ])
            ->add('adjuntos', FileType::class, [
                'label' => 'Adjunto',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Adjunto',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contenido::class,
        ]);
    }
}
