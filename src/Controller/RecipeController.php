<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        // La fonction flush permet de mettre à jour notre base de données après les modifications faites
        // EnityManager sera utilisé lors des modifications au niveau de la base de données
        // $em->flush();
        //Récupérer la liste des recettes par rapport à la durée (durée inférieure à...) de préparation via notre fonction créé dans le repository(findWithDurationLowerThan)
        // $recipes = $repository->findWithDurationLowerThan(20); ou
        $recipes = $em->getRepository(Recipe::class)->findWithDurationLowerThan(20);
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
}
