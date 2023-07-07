<?php

namespace App\Controller\Back;

use App\Entity\Devis;
use App\Entity\ProduitDevis;
use App\Form\DevisType;
use App\Form\ProduitDevisType;
use App\Repository\DevisRepository;
use App\Service\PdfService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\KernelInterface;

#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(Request $request, DevisRepository $devisRepository,  PaginatorInterface $paginator): Response
    {
        //initialement tri par id et si _sort_by = par exemple montant alors on tri par montant
        $sortBy = $request->query->get('_sort_by', 'id');
        $sortOrder = $request->query->get('_sort_order', 'asc');

        $queryBuilder = $devisRepository->createQueryBuilder('devi')
            ->orderBy('devi.' . $sortBy, $sortOrder);

        $devis = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('back/devis/index.html.twig', [
            'devis' => $devis,
            'sort_order' => $sortOrder,
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DevisRepository $devisRepository): Response
    {
        $devi = new Devis();

        $form = $this->createForm(DevisType::class, $devi);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($devi->getProduitDevis() as $produitDevis) {
                $produitDevis->setDevis($devi);
            }
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

    #[Route('/devis-pdf/{id}', name: 'app_devis_pdf', methods: ['GET'])]
    public function showPdf(Devis $devi, PdfService $pdf): Response
    {
        $html = $this->renderView('back/devis/showPdf.html.twig', [
            'devi' => $devi,
        ]);

        $pdfGenerate = $pdf->generateBinaryPdf($html);

        // Renvoyer le PDF comme réponse
        $response = new Response($pdfGenerate);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }


    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, DevisRepository $devisRepository): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($devi->getProduitDevis() as $produitDevis) {
                $produitDevis->setDevis($devi);
            }
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
