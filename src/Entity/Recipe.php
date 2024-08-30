<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use App\Validator\BanWord;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
// La contrainte UniqueEntity('title') permet de ne pas avoir une répétiton de 'title' dans nos recettes c-à-dire deux recettes qui ont un même title
#[UniqueEntity('title', message:'Une recette porte déjà ce titre. Essayez un autre titre.')]
// La contrainte UniqueEntity('slug') permet de ne pas avoir une répétition de 'slug' dans nos recettes c-à-dire deux recettes qui ont un même slug
#[UniqueEntity('slug', message:'Une recette porte déjà cet URL. Essayez un autre URL.')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // La contrainte BanWord permet de vérifier que le title ne contient pas de mots bannis
    // groups: ['Extra'] stipule que la contrainte #BanWord est dans le groupe de contrainte nommé 'Extra' qui sera appelé dans le RecipeType dans "public function configureOptions {validation_groups}"
    // #[BanWord(groups: ['Extra'])]
    #[BanWord()]
    // La contrainte Assert\Length(min:5) veille à ce que le title soit >= 5 caractère
    // groups: ['Extra'] stipule que la contrainte #Assert\Length fait parti du groupe de contrainte nommé 'Extra' qui sera appelé dans le RecipeType dans "public function configureOptions {validation_groups}"
    // #[Assert\Length(min:5, groups: ['Extra'])]
    #[Assert\Length()]
    private string $title = '';

    #[ORM\Column(length: 255)]
    // La contrainte Assert\Length(min:5) veille à ce que le slug soit >= 5 caractère
    #[Assert\Length(min:5)]
    // La contrainte Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Le slug est invalide (Invalid slug).') veille à ce que le slug est une chaîne de caractères alphanumériques séparées par des tirets.
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Le slug est invalide (Invalid slug).')]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    // La contrainte Assert\Length(min:10) veille à ce que le content soit >= 10 caractère
    #[Assert\Length(min:10)]
    private string $content = '';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column(nullable: true)]
    // La contrainte Assert\Positive() veille à ce que la valeur de "duration" soit positive(que ce soit un entier)
    #[Assert\Positive(message:'La valeur de ce champ doit être positive.')]
    // La contrainte Assert\NotBlank() veille à ce qu'on n'ai pas de valeur null ou un champ vide au niveau de notre "duration"
    // #[Assert\NotBlank(message:'Ce champ ne peut être vide. Elle doit contenir un nombre entier.')]
    // La contrainte Assert\LessThan(value:1440) veille à ce que aucune recette ne dépasse 24h = 1440min
    #[Assert\LessThan(value:1440, message:'La durée de préparation de votre recette doit inférieur à 1440 minutes soit 24h.')]
    private ?int $duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
