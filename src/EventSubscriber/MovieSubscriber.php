<?php

namespace App\EventSubscriber;

use App\Movie\Event\MovieUnderageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovieSubscriber implements EventSubscriberInterface
{
    public function onMovieUnderageEvent(MovieUnderageEvent $event): void
    {
        dump('listened');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieUnderageEvent::class => 'onMovieUnderageEvent',
        ];
    }
}
