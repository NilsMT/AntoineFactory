<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('newPlainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'class' => ''
                ],
            ],
            'second_options' => [
                'label' => 'Confirmer le nouveau mot de passe',
                'attr' => [
                    'class' => ''
                ],
            ],
            'invalid_message' => 'Les mots de passe doivent être identiques',
        ])
        ->add('plainPassword', PasswordType::class,[
            'attr'=>[
                'class'=>''
            ],
            'label'=> 'Ancien mot de passe',
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

}
