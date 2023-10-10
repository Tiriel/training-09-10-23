<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $manager): Response
    {
        $books = $manager->getRepository(Book::class)->findAll();
        $book = (new Book())
            ->setTitle('1984')
            ->setAuthor('G. Orwell')
            ->setReleasedAt(new \DateTimeImmutable('01-01-1956'))
            ->setIsbn('931-132656-32')
            ->setCover('http://blah')
            ->setEditor('Whatever')
            ->setPlot('Just about now')
            ;

        $comment = (new Comment())
            ->setEmail('me@me.com')
            ->setName('Myself')
            ->setContent('This book is too real')
            ->setCreatedAt(new \DateTimeImmutable())
            ;
        $book->addComment($comment);

        $manager->persist($book);
        $manager->flush();

        return $this->render('book/new.html.twig');
    }
}
