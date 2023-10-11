<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchTypes;
use App\Movie\Search\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbToMovieTransformer $transformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getOne(string $value, SearchTypes $type = SearchTypes::Title): Movie
    {
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        /** @var Movie $movie */
        $movie = $this->transformer->transform($data);

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
