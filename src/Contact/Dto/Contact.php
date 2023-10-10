<?php

namespace App\Contact\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    public function __construct(
        #[Assert\NotBlank(message: 'Your name cannot be blank')]
        private ?string $name = null,
        #[Assert\Email()]
        private ?string $email = null,
        private ?string $subject = null,
        private ?string $message = null,
        private ?\DateTimeImmutable $createdAt = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): Contact
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
