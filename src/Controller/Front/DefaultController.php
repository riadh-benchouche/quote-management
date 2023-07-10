<?php

namespace App\Controller\Front;

use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'front_default_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, DevisRepository $devisRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // get last 5 factures
        $factures = $factureRepository->findBy(
            [],
            ['date' => 'DESC'],
            5,
            0
        );
        $devis = $devisRepository->findBy(
            [],
            ['date' => 'DESC'],
            5,
            0
        );
        $totalFactures = $factureRepository->findAll() ? count($factureRepository->findAll()) : 0;
        $totalDevis = $devisRepository->findAll() ? count($devisRepository->findAll()) : 0;

        if ($user && $user->isVerified()) {
            return $this->render('front/default/index.html.twig', [
                'totalFactures' => $totalFactures,
                'totalDevis' => $totalDevis,
                'factures' => $factures,
                'devis' => $devis,
            ]);
        } else {
            return $this->render('front/default/please-verify-mail.html.twig');
        }
    }
}
