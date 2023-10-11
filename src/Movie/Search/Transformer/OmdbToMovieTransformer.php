<?php

namespace App\Movie\Search\Transformer;

use App\Entity\Movie;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class OmdbToMovieTransformer implements TransformerInterface
{
    private const KEYS = [
        'Title',
        'Poster',
        'Country',
        'Plot',
        'Released',
        'Year',
        'imdbID',
        'Rated',
    ];

    public function __construct(private readonly OmdbToGenreTransformer $transformer) {}

    public function transform(mixed $value): mixed
    {
        if (!is_array($value) || \count(array_diff(self::KEYS, array_keys($value))) > 0) {
            throw new \InvalidArgumentException();
        }

        $date = $value['Released'] === 'N/A' ? '01-01-'.$value['Year'] : $value['Released'];

        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPlot($value['Plot'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($value['Poster'])
            ->setPrice(5.0)
        ;

        foreach (explode(', ', $value['Genre']) as $name) {
            $movie->addGenre($this->transformer->transform($name));
        }

        return $movie;
    }
}
