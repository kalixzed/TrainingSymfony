<?php

namespace App\Form;

use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory
{

    public function __construct(private SluggerInterface $slugger)
    {
        // Rien à faire ici, la création du listener est dépendante du contexte
    }

    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field)
        {
            // Récupération des données du formulaire soumis
            $data = $event->getData();
            // Génération d'un slug à partir du titre si le slug est vide
            // Notre slug est représenté par le $field
            if (empty($data['slug'])) {
                // Utilisation de Symfony\Component\String\Slugger\AsciiSlugger pour générer un slug ASCII
                // $slugger = new AsciiSlugger();
                // Nous pouvonc aussi générer notre slug ASCII en utilisant la fonction__contruct qui fait appel à la sluggerInterface
                // Génération du slug à partir du titre
                // On utilise la fonction strtolower pour transformer le titre en minuscule pour que le slug soit généré en minuscule
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                // On réassigne les données à l'événement pour que Symfony valide le formulaire
                $event->setData($data);
            }
        };
    }

    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event)
        {
            // Récupération des données du formulaire soumis
            $data = $event->getData();
            // Ajout des timestamps au champs createdAt et updatedAt
            $data->setUpdatedAt(new \DateTimeImmutable());
            // Si le champ id est vide, on met à jour le champ créé_at
            if(!$data->getId())
            {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}