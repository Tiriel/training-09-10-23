<?php

namespace App\Movie\Notifications;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Notifications\Factory\NotificationFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    /** @var NotificationFactoryInterface[]  */
    private iterable $factories = [];

    public function __construct(
        #[TaggedIterator('app.notification_factory', defaultIndexMethod: 'getIndex')]
        iterable $factories,
        private readonly NotifierInterface $notifier,
    ) {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function sendNewMovieNotification(User $user, Movie $movie): void
    {
        $subject = sprintf("The movie %s is now available", $movie->getTitle());
        $channel = $user->getPreferredChannel();

        if (!NotificationChannels::tryFrom($channel)) {
            throw new \InvalidArgumentException();
        }

        $notification = $this->factories[$channel]->createNotification($subject);
        $recipient = new Recipient($user->getEmail());

        $this->notifier->send($notification, $recipient);
    }
}
