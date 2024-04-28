<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Retourne la page des mentions légales
 */
class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions_legales', name: 'mentions_legales.index')]
    public function index(): Response
    {
        return $this->render('pages/mentions_legales.html.twig', [
            'controller_name' => 'MentionsLegalesController',
        ]);
    }
}