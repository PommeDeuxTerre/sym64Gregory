<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CrudController extends AbstractController
{
    #[Route('/crud', name: 'crud')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user)return $this->redirectToRoute('app_login');
        return $this->render('crud/index.html.twig', [
            'controller_name' => 'CrudController',
            'user' => $user,
        ]);
    }
}
