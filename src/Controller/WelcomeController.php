<?php
// src/Controller/WelcomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome")
     */
    public function index(): Response
    {
        // Renderiza la vista templates/welcome.html.twig
        return $this->render('welcome.html.twig');
    }
}
