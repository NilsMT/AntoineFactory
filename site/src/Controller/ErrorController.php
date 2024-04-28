<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gère les erreurs et redirige vers la page 404
 */
class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function showError(): Response
    {
        return $this->render('pages/security/404.html.twig', [], new Response('', 404));
    }
}

?>