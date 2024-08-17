<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {

    #[Route("/", name: "home")]
    public function index (Request $request): Response {
        // dd($request); La fonction dd permet d'executer uniquement une partie du code allant du haut jusqu'a dd et rien après
        // return new Response('Bonjour tout le monde.');
        // return new Response('Bonjour '. $_GET['name']);
        // return new Response('Bonjour '. $request->query->get('name', 'Inconnu'));
        return $this->render('home/index.html.twig');

    }

}