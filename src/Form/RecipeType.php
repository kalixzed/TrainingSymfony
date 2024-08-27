<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Modification des label de nos champs du formulaire
        $builder
            ->add('title', TextType::class, ['label' => 'Nom de la recette'])
            ->add('slug', TextType::class, ['label' => 'URL de la recette'])
            ->add('content', TextType::class, ['label' => 'Liste des Ingrédients'])
            ->add('duration', TextType::class, ['label' => 'Temps de prépartion ou de cuisson (en minutes)'])
            // Ajout manuel d'un nouveau bouton "Save" à notre formulaire
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
