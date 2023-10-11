<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchTypes;
use App\Movie\Search\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider implements ProviderInterface
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbToMovieTransformer $transformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getOne(string $value, SearchTypes $type = SearchTypes::Title): Movie
    {
        $this->io?->text('Fetching from OMDb API...');
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            $this->io?->note('Movie already in database!');

            return $movie;
        }

        $this->io?->text('Creating the Movie object...');
        $movie = $this->transformer->transform($data);

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->io?->text('Saving in database...');
        $this->manager->persist($movie);
        $this->manager->flush();
        $this->io?->info('Movie saved!');

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
