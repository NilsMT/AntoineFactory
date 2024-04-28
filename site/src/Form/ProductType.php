<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr'=>[
                    'class'=>'character-limit-input'
                ],
                'label'=> 'Nom : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max:45)
                ]
            ])
            ->add('prix', NumberType::class, [
                'attr'=>[
                    'class'=>'character-limit-input',
                    'min' => '0',
                    'max' => '9999'
                ],
                'label'=> 'Prix [€] :',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Assert\Length(max:6)
                ]
            ])
            ->add('promotion', IntegerType::class, [
                'attr'=>[
                    'class'=>'',
                    'min' => '0',
                    'max' => '100'
                ],
                'label'=> 'Promotion [%] : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max:3)
                ]
            ])
            ->add('stock', IntegerType::class, [
                'attr'=>[
                    'class'=>'',
                    'min' => '0',
                    'max' => '1000000000'
                ],
                'label'=> 'Stock : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Assert\Length(max:10)
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr'=>[
                    'class'=>''
                ],
                'label'=> 'Description : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('materiau', TextType::class, [
                'attr'=>[
                    'class'=>''
                ],
                'label'=> 'Matériau : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('longueur', IntegerType::class, [
                'attr'=>[
                    'class'=>'',
                    'min' => '0',
                    'max' => '1000'
                ],
                'label'=> 'Longueur [mm] : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max:4)
                ]
            ])
            ->add('largeur',  IntegerType::class, [
                'attr'=>[
                    'class'=>'',
                    'min' => '0',
                    'max' => '1000'
                ],
                'label'=> 'Largeur [mm] : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max:4)
                ]
            ])
            ->add('epaisseur', IntegerType::class, [
                'attr'=>[
                    'class'=>'',
                    'min' => '0',
                    'max' => '1000'
                ],
                'label'=> 'Epaisseur [mm] : ',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max:4)
                ]
            ]) 
            ->add('imageFile', VichImageType::class, [
                'attr'=>[
                    'class'=>''
                ],
                'label'=> 'Image : ',
                'label_attr' => [
                    'class' => '',
                ],
                'delete_label' => 'Supprimer?',
                'required' => false,
            ])
            ->add('categorie', ChoiceType::class, [
                'label'=> 'Categorie : ',
                'choices'  => [
                    'Porte-clé' => 'Porte-clé',
                    'Boule de noël' => 'Boule de noël',
                    'Sous-verre' => 'Sous-verre',
                    'Autre' => 'Autre'
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label'=> 'Status : ',
                'choices'  => [
                    'Nouveau' => 'new',
                    'Faible' => 'low',
                    'Limité' => 'limited',
                    'Aucun ' => ""
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'bouton',
                    'jaune' => ''
                ],
                'label'=> 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}