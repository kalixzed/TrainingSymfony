<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // Création d'un objet vide de notre DTO (Data Transfer Object) pour stocker les données du formulaire
        $data = new ContactDTO();

        // Création de notre formulaire de contact grâce à la fonction du AbstractController "createForm"
        // pour faire appel à notre formuluaire de contact créé dans le dossier src/form/ContactType.php 

        $form = $this->createForm(ContactType::class, $data);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Création du message email avec les données du formulaire
            $mail = (new TemplatedEmail())
            // ->to('contact@demo.com') permet de définir l'adresse email à laquelle le mail de notre utilisateur sera envoyé
                ->to($data->service)
            // ->from($data->email) définit la provenance de notre mail
                ->from($data->email)
            // ->subject('Demande de Contact') définit le sujet du mail
                ->subject('Demande de Contact')
            // ->textTemplate('mail/contact.html.twig') définit le template ou la vue du texte du mail 
                ->htmlTemplate('emails/contact.html.twig')
            // ->context($data) définit les données à passer au template du mail
                ->context(['data' => $data]);
            try{
                // $mailer->send($mail) permet de d'anvoyer le mail via le MailerInterface grâce à $mailer
                $mailer->send($mail);
                // Ajout d'un message de confirmation avec la méthode addFlash
                $this->addFlash('success', 'Votre mail a bien été envoyé');
                // Redirection vers la page de contact
                return $this->redirectToRoute('contact');
            } catch (\Exception $e){
                $this->addFlash('danger ', 'Impossible d\'envoyer le mail.');
            }
        }
        // Si le formulaire est soumis et valide, on envoie le mail de contact à notre adresse email
        // Redirection vers une page de confirmation
        // return $this->redirectToRoute('contact_success');
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
