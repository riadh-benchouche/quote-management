<?php

namespace App\Controller\Back;

use App\Entity\Facture;
use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\UserRepository;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, DevisRepository $devisRepository, UserRepository $userRepository): Response
    {
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
        $users = $userRepository->findBy(
            [],
            ['id' => 'DESC'],
            5,
            0
        );
        $totalFactures = $factureRepository->findAll() ? count($factureRepository->findAll()) : 0;
        $totalDevis = $devisRepository->findAll() ? count($devisRepository->findAll()) : 0;
        $totalUsers = $userRepository->findAll() ? count($userRepository->findAll()) : 0;
        return $this->render('back/default/index.html.twig' , [
            'totalFactures' => $totalFactures,
            'totalDevis' => $totalDevis,
            'totalUsers' => $totalUsers,
            'factures' => $factures,
            'devis' => $devis,
            'users' => $users,
        ]);
    }
}
