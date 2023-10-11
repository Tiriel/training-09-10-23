<?php

namespace App\Movie\Search;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OmdbApiConsumer
{
    public function fetchMovie(SearchTypes $type, string $value): iterable
    {
        $data = $this->client->request(
            'GET',
            '',
            [
                'query' => [$type->value => $value],
            ]
        )->toArray();

        if (array_key_exists('Error', $data) && $data['Error'] === 'Movie not found!') {
            throw new NotFoundHttpException('Movie not found!');
        }

        return $data;
    }
}
