<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/crud/tag')]
final class CrudTagController extends AbstractController
{
    #[Route(name: 'app_crud_tag_index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('crud_tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_crud_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_tag_show', methods: ['GET'])]
    public function show(Tag $tag): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('crud_tag/show.html.twig', [
            'tag' => $tag,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_tag_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_tag_index', [], Response::HTTP_SEE_OTHER);
    }
}
