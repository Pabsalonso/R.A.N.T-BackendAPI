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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Length;

class RecipeController extends AbstractFOSRestController{
    /**
     * @Annotations\Get(path="/recipes")
     * @Annotations\View(serializerGroups={"recipe", "step"}, serializerEnableMaxDepthChecks=true)
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
        $requestStep = $request->get('step', null);
        $user = $userRepository->find($request->get('userId', null));
        $form = $this->createForm(RecipeFormType::class, $recipe);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() /*&& $form->isValid()*/){
            $recipe->setUser($user);
            $recipeRepository->add($recipe);
            
            for ($i=0; $i < count($requestStep); $i++) { 
                $step = new Step();
                $step->setTitle($requestStep[$i]['stepTitle']);
                $step->setStepText($requestStep[$i]['stepText']);
                $step->setImgb64($requestStep[$i]['stepImg']);
                $step->setStepNo($requestStep[$i]['stepNo']);
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
    public function editRecipe(int $id, Request $request, RecipeRepository $recipeRepository, SerializerInterface $serializer){
        $recipe = $recipeRepository->find($id);

        if(!empty($request->get('title', null))) $recipe->setTitle($request->get('title', null));
        if(!empty($request->get('text', null))) $recipe->setText($request->get('text', null));
        if(!empty($request->get('rating', null))) $recipe->setRating($request->get('rating', null));
        // foreach ($request->get('ingredients',null) as $key => $value) {
        // }

        // if(!empty($request->get('ingredients', null))) $recipe->addIngredient($request->get('ingredients', null));
        // if(!empty($request->get('steps', null))) $recipe->setTitle($request->get('steps', null));

        // $em->flush();
        return $request->get('ingredients',null)[0];
    }

    /**
     * @Annotations\Delete(path="/recipe/delete/{id}")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository){
        $recipe = $recipeRepository->find($id);
        $recipeRepository->remove($recipe); 
    }
}