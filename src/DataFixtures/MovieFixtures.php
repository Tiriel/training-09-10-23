<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getMovies() as $movie) {
            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getMovies(): iterable
    {
        $genres = [];
        foreach ($this->getMoviesData() as $datum) {
            $date = $datum['Released'] === 'N/A' ? $datum['Year'] : $datum['Released'];

            $movie = (new Movie())
                ->setTitle($datum['Title'])
                ->setPlot($datum['Plot'])
                ->setCountry($datum['Country'])
                ->setReleasedAt(new \DateTimeImmutable($date))
                ->setPoster($datum['Poster'])
                ->setPrice(5.0)
                ->setRated($datum['Rated'])
                ->setImdbId($datum['imdbID'])
            ;

            foreach (explode(', ', $datum['Genre']) as $genreName) {
                $genre = \array_key_exists($genreName, $genres)
                    ? $genres[$genreName]
                    : $genres[$genreName] = (new Genre())->setName($genreName);

                $movie->addGenre($genre);
            }

            yield $movie;
        }
    }

    public function getMoviesData(): iterable
    {
        $files = (new Finder())
            ->in(__DIR__)
            ->files()
            ->name('movie_fixtures.json');

        foreach ($files as $file) {
            $content = $file->getContents();

            foreach (\json_decode($content, true) as $item) {
                yield $item;
            }
        }
    }
}
