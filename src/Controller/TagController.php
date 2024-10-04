<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'tags')]
    public function index(TagRepository $TagRepository): Response
    {
        $user = $this->getUser();
        $tags = $TagRepository->findAll();
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
            'user' => $user,
            'tags' => $tags,
        ]);
    }

    #[Route('/tag/{id}', name: 'tag')]
    public function tag(int $id, TagRepository $TagRepository): Response
    {
        $user = $this->getUser();
        $tag = $TagRepository->find($id);
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
            'user' => $user,
            'tag' => $tag,
        ]);
    }
}
