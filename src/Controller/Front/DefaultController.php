<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'front_default_index', methods: ['GET'])]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user && $user->isVerified()) {
            return $this->render('front/default/index.html.twig');
        } else {
            return $this->render('front/default/please-verify-mail.html.twig');
        }
    }
}
