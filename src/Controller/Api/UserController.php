<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
// use App\Form\Type\RecipeFormType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserController extends AbstractFOSRestController{

    /**
     * @Annotations\Get(path="/user")
     * @Annotations\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */

     public function getAction(Request $request, UserRepository $userRepository){
        return $this->json([
            'message' => 'test!',
     ]);
        // return $userRepository->findOneBy([
        //     'email'=>$request->get('email'),
        // ]);
     }


     /**
     * @Annotations\Get(path="/profile")
     * @Annotations\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
     public function getUserbyToken(Request $request, UserRepository $userRepository)
    {
        try {
            $token = $request->headers->get('X-AUTH-TOKEN');
            $credentials = str_replace('Bearer ', '', $token);
            $jwt = (array) JWT::decode(
                $credentials,
                new Key($this->getParameter('jwt_secret'),'HS256')
            );
            return $userRepository
                    ->findOneBy([
                            'email' => $jwt['email'],
                    ]);
        }catch (\Exception $exception) {
                throw new AuthenticationException($exception->getMessage());
        }
    }

}