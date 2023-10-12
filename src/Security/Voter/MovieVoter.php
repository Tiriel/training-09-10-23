<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Event\MovieUnderageEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MovieVoter extends Voter
{
    public const RATED = 'movie.rated';
    public const EDIT = 'movie.edit';

    public function __construct(private readonly EventDispatcherInterface $dispatcher) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::RATED, self::EDIT])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::RATED => $this->checkUnderage($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    public function checkUnderage(Movie $movie, User $user): bool
    {
        if ('G' === $movie->getRated()) {
            return true;
        }

        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y ?? null;

        $vote = match ($movie->getRated()) {
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false,
        };

        if (!$vote) {
            $this->dispatcher->dispatch(new MovieUnderageEvent($movie));
        }

        return $vote;
    }

    public function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkUnderage($movie, $user) && $user === $movie->getCreatedBy();
    }
}
