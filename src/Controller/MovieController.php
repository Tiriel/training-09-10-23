<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieType;
use App\Movie\Search\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(?Movie $movie, ValidatorInterface $validator): Response
    {
        $errors = $validator->validate($movie);
        if (\count($errors) > 0) {
            //
        }
        $this->denyAccessUnlessGranted(MovieVoter::RATED, $movie);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'app_movie_omdb', methods: ['GET'])]
    public function omdb(string $title, MovieProvider $provider): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $provider->getOne($title),
        ]);
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id<\d+>}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function save(Request $request, EntityManagerInterface $manager, ?Movie $movie = null): Response
    {
        if ($movie) {
            $this->denyAccessUnlessGranted(MovieVoter::EDIT, $movie);
        }
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$movie->getId() && ($user = $this->getUser()) instanceof User) {
                $movie->setCreatedBy($user);
            }
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/save.html.twig', [
            'form' => $form,
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
