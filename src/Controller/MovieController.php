<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => []
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $movie = [
            'id' => $id,
            'title' => 'Star Wars - Episode IV : A New Hope',
            'country' => 'United States',
            'releasedAt' => new \DateTimeImmutable('25-05-1977'),
            'plot' => 'Incoming',
            'genres' => [
                'Action',
                'Adventure',
                'Fantasy',
            ]
        ];

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    public function lastMovies(): Response
    {
        $movies = [
            ['title' => 'Movie 1'],
            ['title' => 'Movie 2'],
            ['title' => 'Movie 3'],
            ['title' => 'Movie 4'],
            ['title' => 'Movie 5'],
            ['title' => 'Movie 6'],
        ];

        return $this->render('includes/_last_movies.html.twig', [
            'movies' => $movies,
        ])->setTtl(3600);
    }
}
