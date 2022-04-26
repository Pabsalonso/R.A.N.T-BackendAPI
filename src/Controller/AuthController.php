<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Firebase\JWT\JWT;


class AuthController extends AbstractController
{
    /**
     * @Route("/auth/register", name="register", methods={"POST"})
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
     * @Route("/auth/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $encoder)
    {
            $user = $userRepository->findOneBy([
                    'email'=>$request->get('email'),
            ]);
            if (!$user || !$encoder->isPasswordValid($user, $request->get('password'))) {
                    return $this->json([
                        'message' => 'email or password is wrong.',
                    ]);
            }
        $payload = [
            "email" => $user->getEmail(),
            "exp"  => (new \DateTime())->modify("+5 minutes")->getTimestamp(),
        ];


            $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
            return $this->json([
                'message' => 'success!',
                'token' => sprintf('Bearer %s', $jwt),
            ]);
    }

    
}
