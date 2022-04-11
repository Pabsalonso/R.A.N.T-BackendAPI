<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends AbstractFOSRestController{
    /**
     * @Annotations\Get(path="/recipes")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */

     public function getAction(RecipeRepository $recipeRepository){
        return $recipeRepository->findAll();
     }

     /**
     * @Annotations\Post(path="/recipes")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */

    public function postAction(Request $request, RecipeRepository $recipeRepository){
        $recipe = new Recipe();
        $recipe->setTitle($request->get('title', null));
        $recipe->setText($request->get('text', null));
        $recipe->setRating($request->get('rating', null));

        $recipeRepository->add($recipe);
     }
}