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
        return $this->render('crud/index.html.twig', [
            'controller_name' => 'CrudController',
            'user' => $user,
        ]);
    }
}
