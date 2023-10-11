<?php

namespace App\Movie\Search\Provider;

use App\Entity\Genre;
use App\Movie\Search\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider implements ProviderInterface
{
    public function __construct(
        private readonly GenreRepository $repository,
        private readonly OmdbToGenreTransformer $transformer,
    ) {}

    public function getFromOmdbString(string $genres): iterable
    {
        foreach (explode(', ', $genres) as $name) {
            yield $this->getOne($name);
        }
    }

    public function getOne(string $value): Genre
    {
        return $this->repository->findOneBy(['name' => $value])
            ?? $this->transformer->transform($value);
    }
}
