<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prenom', TextType::class, [
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Prénom :',
            'label_attr' => [
                'class' => ''
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
        ->add('nom', TextType::class, [
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Nom :',
            'label_attr' => [
                'class' => ''
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
        ->add('plainPassword', PasswordType::class, [
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Confirmez votre mot de passe',
            'label_attr' => [
                'class' => ''
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
        ->add('submit', SubmitType::class, [
            'attr'=>[
                'class'=>'bouton',
                'jaune'=>''
            ],
            'label'=> 'Mettre à jour'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
