<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController{
    /**
     * @Route("/recipe", name = "recipe_get")
     */

    public function list(RecipeRepository $recipeRepository){
        $recipes = $recipeRepository->findAll();
        $recipesAsArray = [];

        foreach ($recipes as $recipe) {
            $recipesAsArray[] = 
                [
                    'id' => $recipe->getId(),
                    'text' => $recipe->getText(),
                    'rating' => $recipe->getRating(),
                ];
        }

        $response = new JsonResponse();
        $response->setData($recipesAsArray);
        return $response;
    }   

    /**
     * @Route("/recipes/new", name = "crear_recipe")
     */

    public function createRecipe(Request $request, EntityManagerInterface $em){
        // $recipe = new Recipe();
        // $recipe->setTitle('Testeando');
        // $recipe->setText('hola');
        // $recipe->setRating(4);
        // $em->persist($recipe);
        // $em->flush();
        // $response = new JsonResponse();
        // $response->setData([
        //     'success'=> true,
        //     'data'=> [
        //         [
        //             'id'=>$recipe->getId(),
        //             'nombre'=> $recipe->getTitle()
        //         ],
        //     ]
        // ]);
        // return $response;

    }
}
?>