<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/crud/post')]
final class CrudPostController extends AbstractController
{
    #[Route(name: 'app_crud_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $user = $this->getUser();
        if (!$user)return $this->redirectToRoute('app_login');
        return $this->render('crud_post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_crud_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user)return $this->redirectToRoute('app_login');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_post/new.html.twig', [
            'post' => $post,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        $user = $this->getUser();
        if (!$user)return $this->redirectToRoute('app_login');
        return $this->render('crud_post/show.html.twig', [
            'post' => $post,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user)return $this->redirectToRoute('app_login');
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())return $this->redirectToRoute('app_login');
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
