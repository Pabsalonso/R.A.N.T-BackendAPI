<?php

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Entity\Step;
use App\Repository\RecipeRepository;
use App\Form\Type\RecipeFormType;
use App\Repository\StepRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends AbstractFOSRestController{
    /**
     * @Annotations\Get(path="/recipes")
     * @Annotations\View(serializerGroups={"recipe", "step", "recipeUser"}, serializerEnableMaxDepthChecks=true)
     */

     public function getAction(RecipeRepository $recipeRepository){
        return $recipeRepository->findAll();
     }

     /**
     * @Annotations\Post(path="/recipe/new")
     * @Annotations\View(serializerGroups={"recipe", "step"}, serializerEnableMaxDepthChecks=true)
     */

    public function postAction(
        Request $request,
        RecipeRepository $recipeRepository, 
        StepRepository $stepRepository,
        UserRepository $userRepository)
        {
        // return $request->request->all();
        $recipe = new Recipe();
        $requestSteps = $request->get('steps', null);
        $user = $userRepository->find($request->get('userId', null));
        $form = $this->createForm(RecipeFormType::class, $recipe);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() /*&& $form->isValid()*/){
            $recipe->setUser($user);
            $recipeRepository->add($recipe);
            
            for ($i=0; $i < count($requestSteps); $i++) { 
                $step = new Step();
                $step->setTitle($requestSteps[$i]['title']);
                $step->setStepText($requestSteps[$i]['stepText']);
                $step->setStepImg($requestSteps[$i]['stepImg']);
                $step->setStepNo($requestSteps[$i]['stepNo']);
                $step->setRecipe($recipe);
                $stepRepository->add($step);
            }
            return $recipe;
        }
        return $form;
    }

    /**
     * @Annotations\Put(path="/recipe/edit/{id}")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */
    public function editRecipe(int $id, Request $request, RecipeRepository $recipeRepository, StepRepository $stepRepository,){
        $recipe = $recipeRepository->find($id);
        $recipe->setTitle($request->get('title', null));
        $recipe->setText($request->get('text', null));
        $recipe->setIngredients($request->get('ingredients', null));
        $recipe->setImg($request->get('img', null));
        $recipe->setPeople($request->get('people', null));
        $recipe->setDificulty($request->get('dificulty', null));
        $recipe->setPrepTime($request->get('prepTime', null));
                
        $requestSteps = $request->get('steps', null);
        $stepBuff = $stepRepository->findBy(['recipe' => $id], ['stepNo' => 'ASC']);
        for ($i=0; $i < count($requestSteps) || $i < count($stepBuff); $i++) {
            if (!(array_key_exists($i, $requestSteps))) {$stepRepository->remove($stepBuff[$i]);}
            else{
                $step = (array_key_exists($i, $stepBuff)) ? $stepBuff[$i] : new Step();
                $step->setTitle($requestSteps[$i]['title']);
                $step->setStepText($requestSteps[$i]['stepText']);
                $step->setStepImg($requestSteps[$i]['stepImg']);
                $step->setStepNo($requestSteps[$i]['stepNo']);
                $step->setRecipe($recipe);
                $stepRepository->add($step);
            }
        }
        return $recipe;
    }

    /**
     * @Annotations\Delete(path="/recipe/delete/{id}")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository){
        $recipe = $recipeRepository->find($id);
        $recipeRepository->remove($recipe); 
    }

    /**
     * @Annotations\Post(path="/recipe/favouriteCheck/{id}")
     * @Annotations\View(serializerGroups={"recipe", "step"}, serializerEnableMaxDepthChecks=true)
    */

    public function checkFavourite(
        int $id,
        RecipeRepository $recipeRepository,
        UserRepository $userRepository,
        Request $request
    ){
        $recipe = $recipeRepository->find($id);
        $user = $userRepository->find($request->get('userId', null));
        return $recipe->getUsersFavourite()->contains($user);
    }

     /**
     * @Annotations\Post(path="/recipe/favourite/{id}")
     * @Annotations\View(serializerGroups={"recipe", "step"}, serializerEnableMaxDepthChecks=true)
     */

    public function toggleFavourite(
        int $id,
        RecipeRepository $recipeRepository,
        UserRepository $userRepository,
        Request $request
    ){
        $recipe = $recipeRepository->find($id);
        $user = $userRepository->find($request->get('userId', null));
        $isFavourited = $recipe->getUsersFavourite()->contains($user);
        if($isFavourited) {
            $recipe->removeUsersFavourite($user);
        }else{
            $recipe->addUsersFavourite($user);
        }
        $recipeRepository->add($recipe);
        return !($isFavourited);
    }
}