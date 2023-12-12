<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request, Security $security): Response
    {
        // Verificar si el usuario ya está autenticado
        if ($security->isGranted('ROLE_USER')) {
            $this->addFlash('success', '¡Ya estás autenticado!');
            return $this->redirectToRoute('welcome');
        }

        // Obtener el último error de autenticación
        $error = $authenticationUtils->getLastAuthenticationError();
        // Obtener el último nombre de usuario ingresado
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')) {
            // Aquí puedes agregar validaciones adicionales si es necesario
            if (!$error) {
                $this->addFlash('success', '¡Inicio de sesión exitoso!');
                return $this->redirectToRoute('welcome');
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}


