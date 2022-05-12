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
        $picture = $request->get('picture');
        $name = $request->get('name');

        $user = new User();
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setPicture($picture);
        $user->setName($name);
        $userRepository->add($user);
    }

     /**
     * @Annotations\Post(path="/user/recipes")
     * @Annotations\View(serializerGroups={"user", "recipe", "step"}, serializerEnableMaxDepthChecks=true)
     */

    public function getUserRecipes(Request $request, UserRepository $userRepository){
        $userId = $request->get('userId', null);
        $user = $userRepository->findOneBy(['id' => $userId,]);
        return $user->getRecipes();
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

    /**
     * @Annotations\Get(path="/user/{id}/favourites")
     * @Annotations\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function getUserFavourites(int $id, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['id' => $id,]);
        return $user->getFavourites();
    }

}