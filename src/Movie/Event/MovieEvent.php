<?php

namespace App\Movie\Event;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

abstract class MovieEvent extends Event
{
    public function __construct(private ?Movie $movie = null) {}
    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): MovieEvent
    {
        $this->movie = $movie;
        return $this;
    }
}
