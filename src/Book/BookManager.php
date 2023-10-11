<?php

namespace App\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        private readonly BookRepository $repository,
        #[Autowire(param: 'app.books_per_page')]
        private readonly int $booksPerPage,
    ) {}

    public function getByTitle(string $title): Book|iterable
    {
        return $this->repository->findByApproxTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        return $this->repository->findBy([], [], $this->booksPerPage, $offset);
    }
}
