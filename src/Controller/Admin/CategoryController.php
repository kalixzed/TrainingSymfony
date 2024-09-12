<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;




// Ajouter un préfixe "admin" sur toutes les routes ou les différentes propiétés du RecipeController (/admin/recettes)
// Ainsi toutes les routes définis en dessous de ce notre route admin.recipe seront préfixer par un "/admin"
#[Route("/admin/category", name: 'admin.category.')]
class CategoryController extends AbstractController
{

    #[Route(name: 'index')]
    public function index(CategoryRepository $repository)
    {
        return $this->render('admin/category/index.html.twig', ['categories' => $repository->findAll()]);
    }

    //Création d'un route et d'une fonction pour ajouter une nouvelle category
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        // Création d'une nouvelle category
        $category = new Category();

        // Création de notre formulaire d'ajout de category grâce à la fonction du AbstractController "createForm" pour faire appel à notre formulair d'ajout créé dans le dossier form 
        $form = $this->createForm(CategoryType::class, $category);

        // Gestion du formulaire grâce à la méthode "handleRequest" du formulaire
        $form -> handleRequest($request);
        // Si le formulaire a été soumis et valide, on persiste la category dans la base de données et on redirige vers la liste des category
        // Ajout d'un message de création réussi avec la méthode addFlash
        if ($form->isSubmitted() && $form->isValid())
        {
            // La méthode persist permet d'ajouter une nouvelle entité à la liste de mise à jour
            $em->persist($category);
            // La méthode flush permet de mettre à jour notre base de données après les modifications faites
            $em -> flush();
            $this->addFlash('success', 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/create.html.twig', ['form' => $form]);
    }

    //Création d'un route et d'une fonction pour modifier une category
    // Le Route::method permet de spécifier que cette route accepte des requêtes avec un certain type (GET, POST, PUT, DELETE...)
    // La class requirements:['id'=> Requirement::DIGITS] permet de spécifier que comme requirements, que nous voulons que notre id ne soit uniquement que des nombres.
    // DIGITS est une constante qui comporte les chiffres de [0-9]+
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements:['id'=> Requirement::DIGITS])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em)
    {

                // Création de notre formulaire de modification de category grâce à la fonction du AbstractController "createForm" pour faire appel à notre formulair d'ajout créé dans le dossier form 
                $form = $this->createForm(CategoryType::class, $category);

                // Gestion du formulaire grâce à la méthode "handleRequest" du formulaire
                $form -> handleRequest($request);
                // Si le formulaire a été soumis et valide, on persiste la category dans la base de données et on redirige vers la liste des category
                // Ajout d'un message de création réussi avec la méthode addFlash
                if ($form->isSubmitted() && $form->isValid())
                {
                    // La méthode flush permet de mettre à jour notre base de données après les modifications faites
                    $em -> flush();
                    $this->addFlash('success', 'La catégorie a bien été modifié.');
                    return $this->redirectToRoute('admin.category.index');
                }

        return $this->render('admin/category/edit.html.twig', ['form' => $form, 'category' => $category]);
    }

    //Création d'un route et d'une fonction pour supprimer une category
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements:['id'=> Requirement::DIGITS])]
    public function remove(EntityManagerInterface $em, Category $category){

         // Suppression de la recette de la base de données
        $em->remove($category);
         // La méthode flush permet de mettre à jour notre base de données après les modifications faites
        $em->flush();
         // Ajout d'un message de suppression réussi avec la méthode addFlash
        $this->addFlash('success', 'La catégorie a bien été supprimée');
        // Redirection vers la liste des recettes
        return $this->redirectToRoute('admin.category.index');
    }
}