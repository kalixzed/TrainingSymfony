<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategoryRepository::class)]
// La contrainte UniqueEntity('name') permet de ne pas avoir une répétiton de 'name' dans nos catégories c-à-dire deux catégories qui ont un même name
#[UniqueEntity('name', message:'Une catégorie porte déjà ce nom. Essayez un autre nom.')]
// La contrainte UniqueEntity('slug') permet de ne pas avoir une répétition de 'slug' dans nos catégories c-à-dire deux catégories qui ont un même slug
#[UniqueEntity('slug', message:'Une catégorie porte déjà cet URL. Essayez un autre URL.')]

class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    // La contrainte Assert\Length(min:5) veille à ce que le slug soit >= 5 caractère
    #[Assert\Length(min:5)]
    // En enlevant le ? de ?string, nous indiquons que le name n'est pas null par défaut, mais contient plutôt une chaîne de caractère par défaut
    private string $name = '';


    #[ORM\Column(length: 255)]
    // La contrainte Assert\Length(min:5) veille à ce que le slug soit >= 5 caractère
    #[Assert\Length(min:5)]
    // La contrainte Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Le slug est invalide (Invalid slug).') veille à ce que le slug est une chaîne de caractères alphanumériques séparées par des tirets.
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Le slug est invalide (Invalid slug).')]
    // En enlevant le ? de ?string, nous indiquons que le slug n'est pas null par défaut, mais contient plutôt une chaîne de caractère par défaut
    private string $slug = '';


    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
