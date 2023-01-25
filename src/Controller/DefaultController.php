<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'default_')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'books' => $bookRepository->findAll()
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(BookRepository $bookRepository, Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->save($book, true);

            return $this->redirectToRoute('default_show', [
                'id' => $book->getId()
            ]);
        }

        return $this->render('default/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Book $book, BookRepository $bookRepository, Request $request): Response
    {
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->save($book, true);

            return $this->redirectToRoute('default_show', [
                'id' => $book->getId()
            ]);
        }

        return $this->render('default/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}/{token}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Book $book, string $token, BookRepository $bookRepository)
    {
        if (!$this->isCsrfTokenValid('delete' . $book->getId(), $token)) {
            throw $this->createAccessDeniedException();
        }

        $bookRepository->remove($book, true);

        return $this->redirectToRoute('default_index');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('default/show.html.twig', [
            'book' => $book
        ]);
    }
}
