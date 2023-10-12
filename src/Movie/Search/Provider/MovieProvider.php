<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchTypes;
use App\Movie\Search\Transformer\OmdbToMovieTransformer;
use App\Security\Voter\MovieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MovieProvider implements ProviderInterface
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbToMovieTransformer $transformer,
        private readonly GenreProvider $genreProvider,
        private readonly Security $security,
    ) {}

    public function getOne(string $value, SearchTypes $type = SearchTypes::Title): Movie
    {
        $this->io?->text('Fetching from OMDb API...');
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            $this->io?->note('Movie already in database!');

            $this->checkUnderage($movie);

            return $movie;
        }

        $this->io?->text('Creating the Movie object...');
        $movie = $this->transformer->transform($data);

        $this->checkUnderage($movie);

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        if (($user = $this->security->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
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

    private function checkUnderage(Movie $movie): void
    {
        if (!$this->io && !$this->security->isGranted(MovieVoter::RATED, $movie)) {
            throw new AccessDeniedException();
        }
    }
}
