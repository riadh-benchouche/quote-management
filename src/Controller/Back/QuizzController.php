<?php

namespace App\Controller\Back;

use App\Entity\Quizz;
use App\Form\QuizzType;
use App\Repository\QuizzRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizz')]
class QuizzController extends AbstractController
{
    #[Route('/', name: 'app_quizz_index', methods: ['GET'])]
    public function index(QuizzRepository $quizzRepository): Response
    {
        return $this->render('back/quizz/index.html.twig', [
            'quizzs' => $quizzRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_quizz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizzRepository $quizzRepository): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizzRepository->save($quizz, true);

            return $this->redirectToRoute('back_app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/quizz/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_quizz_show', methods: ['GET'])]
    public function show(Quizz $quizz): Response
    {
        return $this->render('back/quizz/show.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_quizz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quizz $quizz, QuizzRepository $quizzRepository): Response
    {
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizzRepository->save($quizz, true);

            return $this->redirectToRoute('back_app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/change-position/{order}', name: 'app_quizz_change_position', requirements: ['position' => 'up|down'], methods: ['GET'])]
    public function changePosition(Quizz $quizz, string $order, EntityManagerInterface $manager)
    {
        if ($order === 'up') {
            $quizz->setPosition($quizz->getPosition()-1);
        } else {
            $quizz->setPosition($quizz->getPosition()+1);
        }

        $manager->flush();
        return $this->redirectToRoute('back_app_quizz_index');
    }

    #[Route('/{slug}', name: 'app_quizz_delete', methods: ['POST'])]
    public function delete(Request $request, Quizz $quizz, QuizzRepository $quizzRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizz->getId(), $request->request->get('_token'))) {
            $quizzRepository->remove($quizz, true);
        }

        return $this->redirectToRoute('back_app_quizz_index', [], Response::HTTP_SEE_OTHER);
    }
}
