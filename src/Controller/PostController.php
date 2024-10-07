<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post')]
    public function index(Request $request, int $id, PostRepository $PostRepository, EntityManagerInterface $entityManager, CommentRepository $CommentRepository): Response
    {
        $user = $this->getUser();
        $post = $PostRepository->getPostById($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comments = $CommentRepository->findAll();
        // filter out the comments of other posts (yes I should have done that in the up line)
        $comments = array_filter($comments, fn($comment) => $comment->getPost() == $post);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'comments' => $comments,
            'form' => $form,
            'user' => $user,
        ]);
    }
}
