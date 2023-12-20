<?php

// src/Controller/ExceptionController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends AbstractController
{
    #[Route('/error404', name: 'app_error_404')]
    public function show404(): Response
    {
        // Render the custom 404 error template
        return $this->render('error404.html.twig', [], new Response('', 404));
    }
}
