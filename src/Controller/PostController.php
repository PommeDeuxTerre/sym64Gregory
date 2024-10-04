<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post')]
    public function index(int $id, PostRepository $PostRepository): Response
    {
        $user = $this->getUser();
        $post = $PostRepository->getPostById($id);
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
            'user' => $user,
        ]);
    }
}
