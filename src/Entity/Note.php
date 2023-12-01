<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $starNumber = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStarNumber(): ?int
    {
        return $this->starNumber;
    }

    public function setStarNumber(int $starNumber): static
    {
        $this->starNumber = $starNumber;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
