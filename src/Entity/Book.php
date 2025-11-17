<?php

namespace App\Entity;

use App\Enum\BookGenre;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $author;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull]
    #[Assert\GreaterThan(value: 0)]
    private string $price;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $excerpt;

    #[ORM\Column(enumType: BookGenre::class)]
    #[Assert\NotNull]
    private BookGenre $genre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): float
    {
        return (float) $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = (string) $price;

        return $this;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getGenre(): BookGenre
    {
        return $this->genre;
    }

    public function setGenre(BookGenre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}
