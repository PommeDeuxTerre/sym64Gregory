<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\NewArticleType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/crud/article')]
final class CrudArticleController extends AbstractController
{
    #[Route(name: 'app_crud_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles()))return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        return $this->render('crud_article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_crud_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles()))return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        $article = new Article();
        $form = $this->createForm(NewArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles()))return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        return $this->render('crud_article/show.html.twig', [
            'article' => $article,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles()))return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles()))return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
