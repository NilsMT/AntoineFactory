<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Retourne la page d'accueil 
 */
class HomeController extends AbstractController{

    #[Route('/', "home.index", methods:['GET'])]
    public function index(ProductRepository $repository): Response{

        $products = $repository->findAll();
        usort($products, [Product::class, 'meilleures_ventes']);
        return $this->render('pages/home.html.twig',[
            'products'=> $products
        ]);

    }

}
