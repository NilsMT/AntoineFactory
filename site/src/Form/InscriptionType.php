<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Nom : ',
            'label_attr' => [
                'class' => ''
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
        ->add('prenom', TextType::class, [
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Prenom : ',
            'label_attr' => [
                'class' => ''
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ])
        ->add('email', EmailType::class, [
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Adresse mail : ',
            'label_attr' => [
                'class' => ''
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Email()
            ]
            
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Mot de passe : ',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ],
            'second_options' => [
                'label' => 'Confirmer le mot de passe : ',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ],
            'invalid_message' => 'Les mots de passes ne sont pas identiques',
        ])
        ->add('submit', SubmitType::class, [
            'attr'=>[
                'class'=>'bouton',
                'jaune'=>'',
                'id'=>'bouton'
            ],
            'label'=> 'S\'inscrire'
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
