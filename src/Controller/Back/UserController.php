<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('back_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {
        $form = $this->createForm(UserType::class, null, [
            'data' => [
                'lastname' => $user->getLastname(),
                'firstname' => $user->getFirstname(),
                'email' => $user->getEmail(),
            ],
        ]);
        $form->setData($user); // Associer les données de l'entité User au formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if (!empty($newPassword) && !empty($plainPassword)) {
                if ($hasher->isPasswordValid($user, $plainPassword)) {
                    $user->setUpdatedAt(new \DateTime());
                    $user->setPassword(
                        $hasher->hashPassword($user, $newPassword)
                    );

                    $this->addFlash(
                        'success',
                        'Le mot de passe a été modifié.'
                    );
                } else {
                    $this->addFlash(
                        'warning',
                        'Le mot de passe renseigné est incorrect.'
                    );
                }
            }

            $user->setLastname($form->get('lastname')->getData());
            $user->setFirstname($form->get('firstname')->getData());
            $user->setEmail($form->get('email')->getData());

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('back_app_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('back_app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/profile/edit', name: 'app_user_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher,
        Security $security
    ): Response {
        $user = $security->getUser();

        $form = $this->createForm(UserType::class, null, [
            'data' => [
                'lastname' => $user->getLastname(),
                'firstname' => $user->getFirstname(),
                'email' => $user->getEmail(),
            ],
        ]);
        $form->setData($user); // Associer les données de l'entité User au formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if (!empty($newPassword) && !empty($plainPassword)) {
                if ($hasher->isPasswordValid($user, $plainPassword)) {
                    $user->setUpdatedAt(new \DateTime());
                    $user->setPassword(
                        $hasher->hashPassword($user, $newPassword)
                    );

                    $this->addFlash(
                        'success',
                        'Le mot de passe a été modifié.'
                    );
                } else {
                    $this->addFlash(
                        'warning',
                        'Le mot de passe renseigné est incorrect.'
                    );
                }
            }

            $user->setLastname($form->get('lastname')->getData());
            $user->setFirstname($form->get('firstname')->getData());
            $user->setEmail($form->get('email')->getData());

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('back_app_user_profile_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
