<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractFOSRestController{
    /**
     * @Annotations\Get(path="/comments/{id}")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */

    public function getComments(
        int $id,
        RecipeRepository $recipeRepository,
    ){
        $recipe = $recipeRepository->find($id);
        $comments = $recipe->getComments();

        $response = [];
        for ($i=0; $i < count($comments); $i++) { 
            $arr = ['user' => $comments[$i]->getUser()->getName(), 'textComment' => $comments[$i]->getTextComment()];

            $response[$i] = $arr;
        }
        return $response;
        // return json_encode($response);
    }

    /**
     * @Annotations\Post(path="/comment")
     * @Annotations\View(serializerGroups={"recipe"}, serializerEnableMaxDepthChecks=true)
     */

    public function postComment(
        RecipeRepository $recipeRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        Request $request
    ){
        $comment = new Comment();
        $recipe = $recipeRepository->find($request->get('recipeId', null));
        $user = $userRepository->find($request->get('userId', null));
        $textComment = $request->get('textComment', null);
             
        $comment->setRecipe($recipe);
        $comment->setUser($user); 
        $comment->setTextComment($textComment);

        $commentRepository->add($comment);

        return ['user' => $user->getName(), 'textComment' => $comment->getTextComment()];
    }

}