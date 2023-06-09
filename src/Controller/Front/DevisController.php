<?php

namespace App\Controller\Front;

use App\Entity\Devis;
use App\Entity\ProduitDevis;
use App\Form\DevisType;
use App\Form\ProduitDevisType;
use App\Repository\DevisRepository;
use App\Service\PdfService;
use App\Service\MailerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

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
        return $this->render('front/devis/index.html.twig', [
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

            return $this->redirectToRoute('front_app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('front/devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/devis-pdf/{id}', name: 'app_devis_pdf', methods: ['GET'])]
    public function showPdf(Devis $devi, PdfService $pdf): Response
    {
        $html = $this->renderView('front/devis/showPdf.html.twig', [
            'devi' => $devi,
        ]);

        $pdfGenerate = $pdf->generateBinaryPdf($html);

        // Renvoyer le PDF comme réponse
        $response = new Response($pdfGenerate);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    #[Route('/devis-download-pdf/{id}', name: 'app_devis_download', methods: ['GET'])]
    public function downloadPdf(Devis $devi, PdfService $pdfService): Response
    {
        // Générer le contenu HTML du devis
        $html = $this->renderView('front/devis/showPdf.html.twig', [
            'devi' => $devi,
        ]);

        $pdfService->downloadPdf($html, "devis.pdf");

        // Retourner une réponse vide car le téléchargement est géré par la méthode downloadPdf()
        return new Response();
    }

    #[Route('/send-mail-pdf/{id}', name: 'app_devis_send_mail', methods: ['GET'])]
    public function sendMailPdf(Devis $devi, PdfService $pdfService, MailerService $mailer): Response
    {
        $html = $this->renderView('front/devis/showPdf.html.twig', [
            'devi' => $devi,
        ]);

        // Générer le fichier PDF
        $pdfGenerate = $pdfService->generateBinaryPdf($html);

        // Enregistrer temporairement le fichier PDF
        $temporaryFilePath = sys_get_temp_dir() . '/devis.pdf';
        file_put_contents($temporaryFilePath, $pdfGenerate);

        // Envoyer l'e-mail avec le devis en pièce jointe
        $to = $devi->getClient()->getEmail(); // Récupérer l'e-mail du client depuis l'objet Devis
        $subject = 'Votre devis'; // Sujet de l'e-mail
        $content = 'Voici votre devis en pièce jointe.'; // Contenu du message
        $attachmentPath = $temporaryFilePath; // Chemin du fichier PDF
        $attachmentFileName = 'devis.pdf'; // Nom du fichier PDF

        $mailer->sendEmail($to, $content, $subject, $attachmentPath, $attachmentFileName);

        // Supprimer le fichier PDF temporaire
        unlink($temporaryFilePath);

        return $this->redirectToRoute('front_app_devis_show', ['id' => $devi->getId(), 'msg' => "Mail envoyé avec succès!"]);
    }


    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, DevisRepository $devisRepository, EntityManagerInterface $entityManager): Response
    {
        $originalProduitDevis = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($devi->getProduitDevis() as $produitDevis) {
            $originalProduitDevis->add($produitDevis);
        }

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($devi->getProduitDevis() as $produitDevis) {
                $produitDevis->setDevis($devi);
            }
            $devisRepository->save($devi, true);

            // Supprimer les ProduitDevis qui ne sont plus associés au Devis
            foreach ($originalProduitDevis as $produitDevis) {
                if (!$devi->getProduitDevis()->contains($produitDevis)) {
                    $devi->getProduitDevis()->removeElement($produitDevis);
                    $entityManager->remove($produitDevis);
                }
            }

            $entityManager->persist($devi);
            $entityManager->flush();

            return $this->redirectToRoute('front_app_devis_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->renderForm('front/devis/edit.html.twig', [
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

        return $this->redirectToRoute('front_app_devis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/devi/{id}/update-status', name: 'app_devis_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, Devis $devis, EntityManagerInterface $entityManager)
    {
        $newStatus = $request->request->get('status');

        // Mettre à jour le statut de la facture
        $devis->setStatus($newStatus);

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Rediriger vers la page de détails de la facture
        return $this->redirectToRoute('front_app_devis_show', ['id' => $devis->getId()]);
    }
}
