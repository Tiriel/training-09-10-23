<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => []
        ]);
    }

    #[Route('/{id}', name: 'app_book_show',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 2],
        methods: ['GET'],
        //condition: "request.isXmlHttpRequest()"
    )]
    public function show(int $id = 1): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => []
        ]);
    }
}
