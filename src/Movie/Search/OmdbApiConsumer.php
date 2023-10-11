<?php

namespace App\Movie\Search;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public function __construct(private readonly HttpClientInterface $omdbClient) {}

    public function fetchMovie(SearchTypes $type, string $value): iterable
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            [
                'query' => [$type->value => $value],
            ]
        )->toArray();

        if (array_key_exists('Error', $data)) {
            if ($data['Error'] === 'Movie not found!') {
                throw new NotFoundHttpException('Movie not found!');
            }
            throw new \RuntimeException("Error while getting data.");
        }

        return $data;
    }
}
