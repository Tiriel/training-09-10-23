<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/song', name: 'app_song_get')]
class GetSongController
{
    public function __invoke(): Response
    {
        return new Response('Yay, new song!');
    }
}
