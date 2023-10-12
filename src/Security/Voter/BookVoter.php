<?php

namespace App\Security\Voter;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookVoter extends Voter
{
    public const EDIT = 'book.edit';
    public const DELETE = 'book.delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Book
            &&  \in_array($attribute, [self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        /** @var Book $subject */
        return match ($attribute) {
            self::EDIT => $token->getUser() === $subject->getCreatedBy(),
            default => false
        };
    }
}
