<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
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
    public function section(int $id, SectionRepository $SectionRepository, ArticleRepository $ArticleRepository): Response
    {
        $user = $this->getUser();
        $section = $SectionRepository->find($id);
        $articles = $ArticleRepository->findAllPublished();
        // filter the articles that doesn't contain the section (yes I should have done that in the up line)
        $articles = array_filter($articles, fn($article) => in_array($section, $article->getSections()->toArray()));
        return $this->render('section/section.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
            'section' => $section,
            'articles' => $articles,
        ]);
    }
}
