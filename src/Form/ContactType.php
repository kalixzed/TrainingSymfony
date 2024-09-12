<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
            // empty_data => '' stipule que la base de données peur recevoir une chaîne de caractère vide au niveau du name (nom d'utilisateur)
                'empty_data' => ''
            ])
            ->add('email', EmailType::class, [
                // empty_data => '' stipule que la base de données peur recevoir une chaîne de caractère vide au niveau du email
                'empty_data' => ''
            ])
            ->add('message', TextareaType::class, [
                // empty_data => '' stipule que la base de données peur recevoir une chaîne de caractère vide au niveau du message
                'empty_data' => ''
            ])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])

            // Gestion des différent services qui recevront des mails
            ->add('service', ChoiceType::class, [
                'choices' => [
                    'Compta'=>'compta@demo.com',
                    'Support' => 'support@demo.com',
                    'Commercial' => 'marketing@demo.com',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
