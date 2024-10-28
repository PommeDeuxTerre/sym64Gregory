<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;

class PublicController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ArticleRepository $ArticleRepository): Response
    {
        $user = $this->getUser();
        $articles = $ArticleRepository->findAllPublished();
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
            'articles' => $articles,
            'user' => $user,
        ]);
    }
}
