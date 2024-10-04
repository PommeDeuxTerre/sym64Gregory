<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SectionController extends AbstractController
{
    #[Route('/sections', name: 'sections')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
        ]);
    }

    #[Route('/section/{id}', name: 'section')]
    public function section(): Response
    {
        $user = $this->getUser();
        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
        ]);
    }
}
