<?php

namespace App\Controller;

# on va charger le Repository (manager) de Section

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SectionRepository $sectionRepository): Response
    {
        // si on est déjà connecté et qu'on souhaite revenir sur login
        if($this->getUser()) {
            // on retourne sur l'accueil
            return $this->redirectToRoute('homepage');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => "Connexion",
            'sections' => $sectionRepository->findAll(),
            'user' => null,
        ]);
    }

    #[Route(path: '/signup', name: 'app_signup')]
    public function signup(Request $request, SectionRepository $sectionRepository, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setPassword(
                    $passwordEncoder->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setUniqid(uniqid());
    
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_login');
            }catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView(),
            'title' => "Connexion",
            'sections' => $sectionRepository->findAll(),
            'user' => null,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
