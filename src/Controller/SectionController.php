<?php

namespace App\Controller;

use App\Repository\PostRepository;
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
    public function section(int $id, SectionRepository $SectionRepository, PostRepository $PostRepository): Response
    {
        $user = $this->getUser();
        $section = $SectionRepository->find($id);
        $posts = $PostRepository->findAllPublished();
        // filter the posts that doesn't contain the section (yes I should have done that in the up line)
        $posts = array_filter($posts, fn($post) => in_array($section, $post->getSections()->toArray()));
        return $this->render('section/section.html.twig', [
            'controller_name' => 'SectionController',
            'user' => $user,
            'section' => $section,
            'posts' => $posts,
        ]);
    }
}
