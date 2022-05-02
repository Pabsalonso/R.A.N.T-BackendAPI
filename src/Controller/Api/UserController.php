<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
// use App\Form\Type\RecipeFormType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

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
     public function getUserbyToken(JWTEncoderInterface $jwtEncoder, Request $request, UserRepository $userRepository)
    {
        $token = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $token);
        $email= $jwtEncoder->decode($token)['email'];
        return $userRepository
                    ->findOneBy([
                            'email' => $email,
                    ]);
    }

}