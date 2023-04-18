<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'front_default_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/default/index.html.twig');
    }
}
