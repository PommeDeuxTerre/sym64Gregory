<?php

namespace App\Controller;

use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SectionController extends AbstractController
{
    #[Route('/sections', name: 'sections')]
    public function index(SectionRepository $SectionRepository): Response
    {
        $user = $this->getUser();
        $sections = $SectionRepository->findAll();
        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
            'sections' => $sections,
        ]);
    }

    #[Route('/section/{id}', name: 'section')]
    public function section(int $id, SectionRepository $SectionRepository): Response
    {
        $user = $this->getUser();
        $section = $SectionRepository->find($id);
        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
            'section' => $section,
        ]);
    }
}
