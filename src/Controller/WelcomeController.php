<?php
// src/Controller/WelcomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
class WelcomeController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener el último producto
        $ultimoProducto = $entityManager->getRepository(Product::class)
            ->findBy([], ['id' => 'DESC'], 1, 0)[0] ?? null;

        // Renderiza la vista templates/welcome.html.twig y pasa el último producto
        return $this->render('welcome.html.twig', [
            'ultimoProducto' => $ultimoProducto
        ]);
    }
}
