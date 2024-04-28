<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Retourne la page FAQ
 */
class FAQController extends AbstractController
{
    #[Route('/faq', name: 'faq.index')]
    public function index(): Response
    {
        return $this->render('pages/faq.html.twig', [
            'controller_name' => 'FAQController',
        ]);
    }
}
