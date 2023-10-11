<?php

namespace App\Movie\Search\Provider;

use App\Entity\Genre;

class GenreProvider implements ProviderInterface
{
    public function getFromOmdbString(string $genres): iterable
    {

    }

    public function getOne(string $value): Genre
    {
        // TODO: Implement getOne() method.
    }
}
