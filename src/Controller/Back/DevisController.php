<?php

namespace App\Controller\Back;

use App\Entity\Devis;
use App\Entity\ProduitDevis;
use App\Form\DevisType;
use App\Form\ProduitDevisType;
use App\Repository\DevisRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(Request $request, DevisRepository $devisRepository,  PaginatorInterface $paginator): Response
    {
        $devis = $paginator->paginate(
            $devisRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('back/devis/index.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DevisRepository $devisRepository): Response
    {
        $devi = new Devis();

        // dummy code - add some example tags to the task
        // (otherwise, the template will render an empty list of tags)
        $produitDevis1 = new ProduitDevis();
        $devi->getProduitDevis()->add($produitDevis1);
        // end dummy code

        $form = $this->createForm(DevisType::class, $devi);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devisRepository->save($devi, true);

            return $this->redirectToRoute('back_app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('back/devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, DevisRepository $devisRepository): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devisRepository->save($devi, true);

            return $this->redirectToRoute('back_app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, DevisRepository $devisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $devi->getId(), $request->request->get('_token'))) {
            $devisRepository->remove($devi, true);
        }

        return $this->redirectToRoute('back_app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
