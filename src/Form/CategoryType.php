<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{

    public function __construct(private FormListenerFactory $listenerFactory)
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Nom de la catégorie',
                'empty_data' => ''])
            ->add('slug', TextType::class,
            [
                'label' => 'URL de la catégorie',
                // 'required' => false stipule que le slug n'est pas requit ou obligatoire
                'required' => false,
                // empty_data => '' stipule que la base de données peur recevoir une chaîne de caractère vide au niveau du slug
                'empty_data' => ''
            ])
            // Ajout manuel d'un nouveau bouton "Save" à notre formulaire
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])

            // Ajout d'un événement PRE_SUBMIT pour générer le slug automatiquement lorsque le champ n'est pas remplit en utilisant notre contructeur (__construct) qui fait appel au FormListenerFactory.php
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('name'))
            // Ajout d'un événement POST_SUBMIT pour ajouter des timestamps automatiquement en utilisant notre contructeur (__construct) qui fait appel au FormListenerFactory.php
            -> addEventListener(FormEvents::POST_SUBMIT, $this->listenerFactory->timestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
