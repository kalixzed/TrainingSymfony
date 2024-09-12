<?php

namespace App\Form;

use App\Entity\Recipe;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{

    public function __construct(private FormListenerFactory $listenerFactory)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Modification des label de nos champs du formulaire
        $builder
            ->add('title', TextType::class, ['label' => 'Nom de la recette'])
            ->add('slug', TextType::class, [
                'label' => 'URL de la recette',
                'required' => false,
                // empty_data => '' stipule que la base de données peur recevoir une chaîne de caractère vide au niveau du slug
                'empty_data' => ''
                ])
            ->add('content', TextareaType::class, ['label' => 'Liste des Ingrédients'])
            ->add('duration', TextType::class, ['label' => 'Temps de prépartion ou de cuisson (en minutes)'])
            // Ajout manuel d'un nouveau bouton "Save" à notre formulaire
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            // Ajout d'un événement PRE_SUBMIT pour générer le slug automatiquement lorsque le champ n'est pas remplit en utilisant notre contructeur (__construct) qui fait appel au FormListenerFactory.php
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('title'))
            // Ajout d'un événement POST_SUBMIT pour ajouter des timestamps automatiquement en utilisant notre contructeur (__construct) qui fait appel au FormListenerFactory.php
            -> addEventListener(FormEvents::POST_SUBMIT, $this->listenerFactory->timestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définition des contraintes pour le formulaire RecipeType
        $resolver->setDefaults([
            // Définition de la classe à utiliser pour stocker les données du formulaire
            'data_class' => Recipe::class
            // Ici, on définit une validation par défaut pour tous les champs du formulaire
            // On peut également ajouter des groupes supplémentaires pour des cas spécifiques (le groupe ['Extra'] pour notre exemple)
            // Exemple : 'validation_groups' => ['Default']
            // On pourra, à volonté, activer ou désactiver certaines règles ou groupes de contraintes de nos entités et formulaires en les supprimants de 'validation_groups' => []
            // Pour que toutes nos règles et constraintes soient actives par défaut, il faut eviter de créer le 'validation_groups' => []
            //'validation_groups' => ['Default', 'Extra']
        ]);
    }
}
