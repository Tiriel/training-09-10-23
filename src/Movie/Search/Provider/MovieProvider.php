<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchTypes;
use App\Movie\Search\Transformer\OmdbToMovieTransformer;
use App\Repository\MovieRepository;

class MovieProvider implements ProviderInterface
{
    public function __construct(
        private readonly MovieRepository $repository,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbToMovieTransformer $transformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getOne(string $value, SearchTypes $type = SearchTypes::Title): Movie
    {
        // call omdb to get array data
        // check if exists in database
            // if yes, return entity
        // if no build movie object
        // add Genres
        // save in database
        // return movie
    }
}
