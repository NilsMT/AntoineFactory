<?php

namespace App\Controller;

use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** 
     * Permet à l'utilisateur de consulter ses informations
    */
    #[Route('/information/{user_id}', 'user.information', methods:['GET','POST'])]
    public function information(int $user_id, UserRepository $repository): Response
    {
        $user = $repository->findOneBy(['id'=>$user_id]);
        if($user != $this->getUser()){
            return $this->redirectToRoute('error', ['id'=>$user_id]);
        }
        return $this->render('pages/user/informations.html.twig', [
             'user' => $user
        ]);
    }

    /** 
     * Permet à l'utilisateur de modifier ses informations (sauf mot de passe)
    */
    #[Route('/information/edit/{user_id}', 'user.edit', methods:['GET','POST'])]
    public function edit(int $user_id, UserRepository $repository, Request $request, EntityManagerInterface $manager,UserPasswordHasherInterface $hasher): Response
    {
        $user = $repository->findOneBy(['id'=>$user_id]);
        if($user != $this->getUser()){
            return $this->redirectToRoute('error', ['id'=>$user_id]);
        }
        $form = $this->createForm(UserType::class, $user,[
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user ,$form->getData()->getPlainPassword())){ 
                $new_user=$form->getData();
                $manager->persist($new_user);
                $manager->flush();
                return $this->redirectToRoute('user.information', [
                    'user_id' => $user->getId(), 
            ]);
            }else{
               echo 'Mot de passe incorrect';
            } 
        }

        return $this->render('pages/user/edition.html.twig', [
             'form' => $form->createView(), 'user'=>$user
        ]);
    }

    /** 
     * Permet à l'utilisateur de modifier son mot de passe
    */
    #[Route('/information/edit/mot_de_passe/{user_id}', 'user.editPassword', methods:['GET','POST'])]
    public function editPassword(Request $request, 
    EntityManagerInterface $manager, 
    Int $user_id, 
    UserRepository $repository,
    UserPasswordHasherInterface $hasher):Response{

        $user=$repository->findOneBy(['id' => $user_id]);
        if($user != $this->getUser()){
            return $this->redirectToRoute('error', ['id'=>$user_id]);
        }
        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user,$form->getData()->getPlainPassword())){    
                $user->setPassword($hasher->hashPassword($user, $form->getData()->getNewPlainPassword()));
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('user.information', [
                    'user_id' => $user->getId(), 
            ]);
            }else{
                echo 'Le mot de passe est incorrect';
            }
        }

        return $this->render('pages/user/editionPassword.html.twig', [
           'form'=>$form->createView()
        ]);
    }


} 