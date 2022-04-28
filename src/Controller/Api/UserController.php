<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
// use App\Form\Type\RecipeFormType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractFOSRestController{

    /**
     * @Annotations\Post(path="/register")
     * @Annotations\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function register(Request $request, UserPasswordHasherInterface $encoder, UserRepository $userRepository)
    {
        $password = $request->get('password');
        $email = $request->get('email');
        $user = new User();
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setEmail($email);
        $userRepository->add($user);
        return $this->json([
            'user' => $user->getEmail()
        ]);
    }

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
        return $this->json([
            'message' => 'test!',
     ]);
        // try {
        //     $token = $request->headers->get('X-AUTH-TOKEN');
        //     $credentials = str_replace('Bearer ', '', $token);
        //     $jwt = (array) JWT::decode(
        //         $credentials,
        //         new Key($this->getParameter('jwt_secret'),'HS256')
        //     );
        //     return $userRepository
        //             ->findOneBy([
        //                     'email' => $jwt['email'],
        //             ]);
        // }catch (\Exception $exception) {
        //         throw new AuthenticationException($exception->getMessage());
        // }
    }

}