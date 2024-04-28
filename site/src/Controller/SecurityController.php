<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** 
     * Permet à l'utilisateur de se connecter
    */
    #[Route('/Connexion', 'security.login', methods:['GET','POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/connexion.html.twig', [
            'errors'=>$authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /** 
     * Permet à l'utilisateur de se déconnecter
    */
    #[Route('/logout', 'security.logout')]
    public function logout()
    {
        //Nothing to do
    }

    /** 
     * Permet à un visiteur de se créer un compte
    */
    #[Route('/Inscription', 'security.register', methods:['GET','POST'])]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $user->setPassword($hasher->hashPassword($user, $form->getData()->getPlainPassword()));
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Le compte a bien été créé.'
            );
            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
