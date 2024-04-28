<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController{


    /** 
     *Permet à l'administrateur d'avoir un visuel et de pouvoir gérer ses commandes 
     */    
    

    #[Route('/product/commandes', 'product.commandes', methods:['GET', 'POST'])]
    public function commandes(CommandeRepository $repository, PaginatorInterface $paginator,  Request $request): Response{
        if ($this->isGranted("ROLE_ADMIN")){
            $valide = false;
            $commandes = $repository->findAll();
             $commandes = $paginator->paginate(
                $repository->findAll(), /* query*/
                $request->query->getInt('page',1 ), /*page number*/
                5
            );

            return $this->render('pages/product/commandes.html.twig', [
                'commandes' => $commandes,
                'valide' => $valide
            ]);
        }else{
            return $this->redirectToRoute('home.index');
        }
    }
}