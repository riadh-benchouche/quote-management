<?php

namespace App\Controller\Front;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdfService;
use App\Service\MailerService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(Request $request, FactureRepository $factureRepository,  PaginatorInterface $paginator): Response
    {

        $sortBy = $request->query->get('_sort_by', 'id');
        $sortOrder = $request->query->get('_sort_order', 'asc');

        $queryBuilder = $factureRepository->createQueryBuilder('facture')
            ->orderBy('facture.' . $sortBy, $sortOrder);

        $factures = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('front/facture/index.html.twig', [
            'factures' => $factures,
            'sort_order' => $sortOrder,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($facture->getProduitFacture() as $produitFacture) {
                $produitFacture->setFacture($facture);
            }
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('front_app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('front/facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/facture-pdf/{id}', name: 'app_facture_pdf', methods: ['GET'])]
    public function showPdf(Facture $facture, PdfService $pdf): Response
    {
        $html = $this->renderView('front/facture/showPdf.html.twig', [
            'facture' => $facture,
        ]);

        $pdfGenerate = $pdf->generateBinaryPdf($html);

        // Renvoyer le PDF comme réponse
        $response = new Response($pdfGenerate);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    #[Route('/facture-download-pdf/{id}', name: 'app_facture_download', methods: ['GET'])]
    public function downloadPdf(Facture $facture, PdfService $pdfService): Response
    {
        // Générer le contenu HTML du devis
        $html = $this->renderView('front/facture/showPdf.html.twig', [
            'facture' => $facture,
        ]);

        $pdfService->downloadPdf($html, "facture.pdf");

        // Retourner une réponse vide car le téléchargement est géré par la méthode downloadPdf()
        return new Response();
    }

    #[Route('/send-mail-pdf/{id}', name: 'app_facture_send_mail', methods: ['GET'])]
    public function sendMailPdf(Facture $facture, PdfService $pdfService, MailerService $mailer): Response
    {
        $html = $this->renderView('front/facture/showPdf.html.twig', [
            'facture' => $facture,
        ]);

        // Générer le fichier PDF
        $pdfGenerate = $pdfService->generateBinaryPdf($html);

        // Enregistrer temporairement le fichier PDF
        $temporaryFilePath = sys_get_temp_dir() . '/devis.pdf';
        file_put_contents($temporaryFilePath, $pdfGenerate);

        // Envoyer l'e-mail avec le devis en pièce jointe
        $to = $facture->getClient()->getEmail(); // Récupérer l'e-mail du client depuis l'objet Devis
        $subject = 'Votre facture'; // Sujet de l'e-mail
        $content = 'Voici votre facture en pièce jointe.'; // Contenu du message
        $attachmentPath = $temporaryFilePath; // Chemin du fichier PDF
        $attachmentFileName = 'facture.pdf'; // Nom du fichier PDF

        $mailer->sendEmail($to, $content, $subject, $attachmentPath, $attachmentFileName);

        // Supprimer le fichier PDF temporaire
        unlink($temporaryFilePath);

        return $this->redirectToRoute('front_app_facture_show', ['id' => $facture->getId(), 'msg' => "Mail envoyé avec succès!"]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository, EntityManagerInterface $entityManager): Response
    {
        $originalProduitFacture = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($facture->getProduitFacture() as $produitFacture) {
            $originalProduitFacture->add($produitFacture);
        }

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            foreach ($facture->getProduitFacture() as $produitFacture) {
                $produitFacture->setFacture($facture);
            }
            $factureRepository->save($facture, true);

            // Supprimer les ProduitDevis qui ne sont plus associés au Devis
            foreach ($originalProduitFacture as $produitFacture) {
                if (!$facture->getProduitFacture()->contains($produitFacture)) {
                    $facture->getProduitFacture()->removeElement($produitFacture);
                    $entityManager->remove($produitFacture);
                }
            }

            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('front_app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('front_app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
