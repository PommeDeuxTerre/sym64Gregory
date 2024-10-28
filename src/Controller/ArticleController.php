<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_article')]
    public function index(Request $request, int $id, ArticleRepository $ArticleRepository, EntityManagerInterface $entityManager, CommentRepository $CommentRepository): Response
    {
        $user = $this->getUser();
        $article = $ArticleRepository->getArticleById($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setArticle($article);
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        $comments = $CommentRepository->findAll();
        // filter out the comments of other articles (yes I should have done that in the up line)
        $comments = array_filter($comments, fn($comment) => $comment->getArticle() == $article);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
            'comments' => $comments,
            'form' => $form,
            'user' => $user,
        ]);
    }
}