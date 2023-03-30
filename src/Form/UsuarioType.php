<?php

namespace App\Form;


use App\Entity\Usuario;
use App\Repository\RolRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Security;


class UsuarioType extends AbstractType
{
    private $security;

    public function __construct(Security $security, RolRepository $rolRepository)
    {
        $this->security = $security;
        $this->rolRepository = $rolRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$user = $this->security->getUser();
        //var_dump($user->getRol()->getId());exit;


        $builder
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Documento Nacional de Identidad']
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nombre del Usuario']
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Apellido del Usuario']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email del Usuario']
            ])
            ->add('activo', ChoiceType::class, [
                'label' => 'Estado', 'attr' => ['class' => 'form-control select2'],
                'placeholder' => 'Seleccione',
                'choices'  => [
                    'Activo' => 1,
                    'Bloqueado' => 0,
                ],
            ])
            /*    
            ->add('rol', EntityType::class, [
                                                'label' => 'Rol', 
                                                'attr' => [
                                                            'class' => 'form-control select2',
                                                          ],
                                                'class' => Rol::class, 
                                                'choice_label' => 'descripcion',
                                                'placeholder' => 'Seleccione'
            ])
            */;
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                //$usuario=$event->getData();
                $form = $event->getForm();
                $user = $this->security->getUser();
                $rolRepository = $this->rolRepository;

                $form->add('rol', EntityType::class, [
                    'label' => 'Rol',
                    'attr' => [
                        'class' => 'form-control select2',

                    ],
                    'class' => 'App\Entity\Rol',
                    'query_builder' => function ($repository) {
                        return $repository->createQueryBuilder('r')->where('r.id = 3');
                    },
                    'choice_label' => 'descripcion',
                    'placeholder' => 'Seleccione'
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
