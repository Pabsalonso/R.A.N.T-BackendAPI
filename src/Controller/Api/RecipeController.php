<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;

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

    public function postAction(EntityManagerInterface $em){
        $recipe = new Recipe();
        $recipe->setTitle('Pruebita del fos_res');
        $recipe->setText('hola');
        $recipe->setRating(7);
        $em->persist($recipe);
        $em->flush();
     }
}