<?php

namespace App\Movie\Search\Transformer;

use App\Entity\Genre;

class OmdbToGenreTransformer implements TransformerInterface
{

    public function transform(mixed $value): Genre
    {
        if (!\is_string($value)) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }
}
