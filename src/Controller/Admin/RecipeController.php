<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// Ajouter un préfixe "admin" sur toutes les routes ou les différentes propiétés du RecipeController (/admin/recettes)
#[Route("/admin/recettes", name: 'admin.recipe.')]
// Ainsi toutes les routes définis en dessous de ce notre route admin.recipe seront préfixer par un "/admin"
class RecipeController extends AbstractController
{
    
    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        //Récupérer et afficher la liste complète des recettes grâce au repository via la méthode "find"
        $recipes = $repository->findAll();
        
        //Envoyer la liste des recettes à la vue
        return $this->render('admin/recipe/index.html.twig', ['recipes' => $recipes]);
    }
    
    //Création d'un route et d'une fonction pour ajouter une nouvelle recette
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        // Création d'une nouvelle recette
        $recipe = new Recipe();
        // Création de notre formulaire d'ajout de recette grâce à la fonction du AbstractController "createForm"
        // pour faire appel à notre formulair d'ajout créé dans le dossier form 
        $form = $this->createForm(RecipeType::class, $recipe);
        // Gestion du formulaire grâce à la méthode "handleRequest" du formulaire
        $form -> handleRequest($request);
        // Si le formulaire a été soumis et valide, on persiste la recette dans la base de données et on redirige vers la liste des recettes
        // Ajout d'un message de création réussi avec la méthode addFlash
        if ($form->isSubmitted() && $form->isValid())
        {
            // Ajout des dates de création et modification automatiques à la recette
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            // La méthode persist permet d'ajouter une nouvelle entité à la liste de mise à jour
            $em->persist($recipe);
            // La méthode flush permet de mettre à jour notre base de données après les modifications faites
            $em -> flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form
        ]);
    }


    //Création d'un route et d'une fonction pour modifier les recettes
    // Le Route::method permet de spécifier que cette route accepte des requêtes avec un certain type (GET, POST, PUT, DELETE...)
    // La class requirements:['id'=> Requirement::DIGITS] permet de spécifier que comme requirements, que nous voulons que notre id ne soit uniquement que des nombres.
    // DIGITS est une constante qui comporte les chiffres de [0-9]+
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements:['id'=> Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em, FormFactoryInterface $formFactory)
    {
        // Création de notre formulaire d'édition de recette grâce à la fonction du AbstractController "createForm"
        // pour faire appel à notre formuluaire d'édition créé dans le dossier src/form/RecipeType.php 
        $form = $this->createForm(RecipeType::class, $recipe);
        // Création de notre formulaire d'édition grâce au FormFactoryInterface en utilisant ma méthode create
        // $form = $this->$formFactory->create(RecipeType::class, $recipe);
        // Gestion du formulaire grâce à la méthode "handleRequest" du formulaire
        $form -> handleRequest($request);
        // Si le formulaire a été soumis et valide, on met à jour la recette dans la base de données et on redirige vers la liste des recettes
        // Ajout d'un méssage de modification réussi avec la méthode addFlash
        if ($form->isSubmitted() && $form->isValid())
        {
        // Ajout de la date modification automatique à la recette
            $recipe->setUpdatedAt(new \DateTimeImmutable());
        // La méthode flush permet de mettre à jour notre base de données après les modifications faites 
            $em -> flush();
        // Ajout d'un message de modification réussi avec la méthode addFlash
            $this->addFlash('success', 'La recette a bien été modifiée');
        // Redirection vers la liste des recettes
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }


    // Création d'un route et d'une fonction pour supprimer une recette
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements:['id'=> Requirement::DIGITS])]
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        // Suppression de la recette de la base de données
        $em->remove($recipe);
        // La méthode flush permet de mettre à jour notre base de données après les modifications faites
        $em->flush();
        // Ajout d'un message de suppression réussi avec la méthode addFlash
        $this->addFlash('success', 'La recette a bien été supprimée');
        // Redirection vers la liste des recettes
        return $this->redirectToRoute('admin.recipe.index');
    }
}
