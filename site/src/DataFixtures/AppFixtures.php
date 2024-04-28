<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Panier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use APP\Entity\Product;
use App\Entity\User;
use Faker\Generator;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

## Permet de remplir la base de données de fausses informations
class AppFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->faker = Factory::create('fr_FR');
        $this->hasher=$hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $tab = ["","low","out","new","limited"];
        //Product
        // for ($i=1; $i<=30; $i++){
        //     $product = new Product();
        //     $product->setNom("porte-clés #".$i)
        //         ->setPrix(mt_rand(10,100))
        //         ->setPromotion(mt_rand(0,10))
        //         ->setStock(mt_rand(0,50))
        //         ->setDescription($this->faker->words(10,true))
        //         ->setMateriau('Bois')
        //         ->setLongueur(mt_rand(20,100))
        //         ->setNbVentes(mt_rand(0,100))
        //         ->setLargeur(mt_rand(20,100))
        //         ->setEpaisseur(mt_rand(20,100))
        //         ->setCategorie("Porte-clé")
        //         ->setStatus($tab[mt_rand(0,4)])
        //         ->setImageName('mer-659f903c5d6df698415964.jpg');
        //     $manager->persist($product);
        // }

        //User
        for ($i=1; $i<=5; $i++){
            $user = new User();
            $user->setNom($this->faker->lastName())
                ->setPrenom($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $hashPassword = $this->hasher->hashPassword($user, $user->getPlainPassword()); 
            $user->setPassword($hashPassword);
            $manager->persist($user);
        }

        $hashPassword = $this->hasher->hashPassword($user, 'password'); 
        $user->setPassword($hashPassword);
        $manager->persist($user);

        $admin = new User();
        $admin->setNom('Cretual')
            ->setPrenom('Antoine')
            ->setEmail('antoinefactory@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPlainPassword('link123');
        $hashPassword = $this->hasher->hashPassword($admin, $admin->getPlainPassword()); 
        $admin->setPassword($hashPassword);
        $manager->persist($admin);

        // $panier1 = new Panier();
        // $panier1->setIdUser($admin)
        //     ->setIdProduct($product)
        //     ->setQuantite(4);
        // $manager->persist($panier1);

        // $product2 = new Product();
        // $product2->setNom("porte-clés #")
        //     ->setPrix(mt_rand(10,100))
        //     ->setPromotion(mt_rand(0,10))
        //     ->setStock(mt_rand(0,50))
        //     ->setDescription($this->faker->words(10,true))
        //     ->setMateriau('Bois')
        //     ->setLongueur(mt_rand(20,100))
        //     ->setNbVentes(mt_rand(0,100))
        //     ->setLargeur(mt_rand(20,100))
        //     ->setEpaisseur(mt_rand(20,100))
        //     ->setCategorie("Porte-clé")
        //     ->setStatus($tab[mt_rand(0,4)])
        //     ->setImageName('mer-659f903c5d6df698415964.jpg');
        // $manager->persist($product2);

        // $panier2 = new Panier(); 
        // $panier2->setIdUser($admin)
        //     ->setIdProduct($product2)
        //     ->setQuantite(6);
        // $manager->persist($panier2);

        $manager->flush();
    }
}