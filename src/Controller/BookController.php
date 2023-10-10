<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookRepository $repository): Response
    {
        $books = $repository->findBy([], ['id' => 'DESC'], 9);
        $total = $repository->count([]);

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'total' => $total,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 2],
        methods: ['GET'],
        //condition: "request.isXmlHttpRequest()"
    )]
    public function show(BookRepository $repository, int $id = 1): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $repository->find($id),
        ]);
    }
}
