<?php

namespace App\Movie\Search;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsDecorator(decorates: OmdbApiConsumer::class)]
class CacheableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly CacheInterface $cache,
        private readonly SluggerInterface $slugger,
    ) {}

    public function fetchMovie(SearchTypes $type, string $value): iterable
    {
        $key = sprintf("movie_search_%s_%s", $type->value, $this->slugger->slug($value));

        return $this->cache->get(
            $key,
            fn() => $this->consumer->fetchMovie($type, $value) // if no cache, this will be called
        );
    }
}
