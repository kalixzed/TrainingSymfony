<?php

namespace App\Controller;

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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeController extends AbstractController
{


    
    #[Route('/recettes', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        //Récupérer et afficher la liste complète des recettes grâce au repository via la méthode "find"
        $recipes = $repository->findAll();
        // Affichons la somme de la durée de toutes nos recettes via la méthode findTotalDuration créé dans notre repository
        // dd($repository->findTotalDuration());
        //Modifier un paramètre ou attribut d'une recette(title,content,duration,slug...)via EntityManagerInterface $em
        // $recipes[0]->setTitle('Pâtes Bolognaises');
        // Ainsi, recette en tête de liste (0) change de title pour Pâtes Bolognaises
        // La méthode flush permet de mettre à jour notre base de données après les modifications faites
        // EnityManager sera utilisé lors des modifications au niveau de la base de données
        // $em->flush();
        //Récupérer la liste des recettes par rapport à la durée (durée inférieure à...) de préparation via notre fonction créé dans le repository(findWithDurationLowerThan)
        // $recipes = $repository->findWithDurationLowerThan(20); ou
        //$recipes = $em->getRepository(Recipe::class)->findWithDurationLowerThan(20);
        // La méthode $em->getRepository(Recipe::class) nous evite de passer RecipeRepository $repository en paramètre à notre fonction index
        // Création d'une nouvelle recette avec des valeurs réeles
        // $recipe = new Recipe();
        // $recipe->setTitle('Barbe à papa')
        //     ->setSlug('barbe-papa')
        //     ->setContent('Mettez du sucre dans la machine à barbe à papa')
        //     ->setDuration(5)
        //     ->setCreatedAt(new \DateTimeImmutable())
        //     ->setUpdateAt(new \DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();

        // Suppression de notre recette de barbe à papa dans la base de données
        // $em->remove($recipes[0]);
        // $em->flush();

        //Envoyer la liste des recettes à la vue
        return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
    }
    
    
    // Requirement permet de spécificier le format de données attendu pour les paramètres de notre URL sous frome de tableau
    // requirements:['id'=> '\d+'] permet de spécificier que comme requirements, nous voulons que notre 
    // id ne prenne en compte uniquement que des chiffres ou des nombres.
    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements:['id'=> '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        // Méthode (find, findOneBy) de récupération d'une recette en fonction d'un ou de ses identifiants dans la db(id, slug)
        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug){
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        // $recipe = $repository->findOneBy(['slug' => $slug]);

         // Méthode pour renvoyer une vue suivie d'un tableau associatif
        return $this->render("recipe/show.html.twig", [
            'recipe' => $recipe
        ]);

        // Les méthodes de renvoi de reponse en Json
        // return $this->json([
        //     'slug' => $slug
        // ]);
        // return new JsonResponse([
        //     'slug' => $slug
        // ]);
        // return new Response('Recette: ' . $slug);


        // dd($request);
        // Pour récupérer les attributs avec les méthodes attibutes et get
        // dd($request->attributes->get('slug'), $request->attributes->getInt('id'));
        // On peut directement préciser les paramètres directement au niveau de notre méthode(fucntion index)
        // dd($slug, $id);
        // return $this->render('recipe/index.html.twig', [
        //     'controller_name' => 'RecipeController',
        // ]);
    }

    //Création d'un route et d'une fonction pour modifier les recettes
    
    #[Route('/recettes/{id}/edit', name: 'recipe.edit', methods: ['GET', 'POST'])]

    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em, FormFactoryInterface $formFactory)
    {
        // Création de notre formulaire d'édition de recette grâce à la fonction du AbstractController "createForm"
        // pour faire appel à notre formuluaire d'édition créé dans le dossier form 
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
            $recipe->setUpdateAt(new \DateTimeImmutable());
        // La méthode flush permet de mettre à jour notre base de données après les modifications faites  
            $em -> flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    //Création d'un route et d'une fonction pour ajouter une nouvelle recette

    #[Route('/recettes/create', name: 'recipe.create')]
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
            $recipe->setUpdateAt(new \DateTimeImmutable());
            // La méthode persist permet d'ajouter une nouvelle entité à la liste de mise à jour
            $em->persist($recipe);
            // La méthode flush permet de mettre à jour notre base de données après les modifications faites
            $em -> flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form
        ]);
    }

    // Création d'un route et d'une fonction pour supprimer une recette

    #[Route('/recettes/{id}/delete', name: 'recipe.delete', methods: ['DELETE'])]
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        // Suppression de la recette de la base de données
        $em->remove($recipe);
        // La méthode flush permet de mettre à jour notre base de données après les modifications faites
        $em->flush();
        // Ajout d'un message de suppression réussi avec la méthode addFlash
        $this->addFlash('success', 'La recette a bien été supprimée');
        // Redirection vers la liste des recettes
        return $this->redirectToRoute('recipe.index');
    }
    // // Création d'un route et d'une fonction pour afficher une recette sous forme de PDF
    // #[Route('/recettes/{id}/pdf', name:'recipe.pdf')]
    // public function pdf(Recipe $recipe, KnpSnappy\KnpSnappyPdf $snappy)
    // {
    //     // Création du contenu HTML à partir des données de la recette
    //     $html = $this->renderView('recipe/pdf.html.twig', ['recipe' => $recipe]);

    //     // Génération du PDF avec la librairie KnpSnappy
    //     $pdf = $snappy->getOutputFromHtml($html);

    //     // Envoi du PDF au navigateur avec une redirection vers une page de téléchargement
    //     return new Response($pdf, 200, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'attachment; filename="recette-'.$recipe->getTitle().'.pdf"'
    //     ]);
    // }
}
