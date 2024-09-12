<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    // Création d'une propriété public qui sera une chaîne de caratère et qui représente le nom de notre utilisateur qui veut envoyer le mail
    // Elle a pour valeur initiale une chaîne de caractère vide
    // La contrainte Assert\NotBlank() veille à ce qu'on n'ai pas de valeur null ou un champ vide au niveau de notre "name"
    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:200)]
    public string $name = '';

    // Création d'une propriété public qui sera une chaîne de caractère et qui représente l'email de notre utilisateur qui veut envoyer le mail
    // La contrainte Assert\NotBlank() veille à ce qu'on n'ai pas de valeur null ou un champ vide au niveau de notre "email" pour la DB
    // La contrainte Assert\Email veille à ce que l'addresse email soit valide
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    // Création d'une propriété public qui sera une chaîne de caractère et qui représente le message de notre utilisateur qui veut envoyer le mail
    // Elle a pour valeur initiale une chaîne de caractère vide
    // La contrainte Assert\NotBlank() veille à ce qu'on n'ai pas de valeur null ou un champ vide au niveau de notre "message"
    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:500)]
    public string $message = '';

    // Création d'une propriété public qui sera une chaîne de caractère et qui représente le service de notre utilisateur qui veut envoyer le mail
    // La contrainte Assert\NotBlank() veille à ce qu'on n'ai pas de valeur null ou un champ vide au niveau de notre "service" pour la DB
    // La contrainte Assert\Choice veille à ce que la valeur de "service" soit une des options proposées
    #[Assert\NotBlank]
    public string $service = '';
}