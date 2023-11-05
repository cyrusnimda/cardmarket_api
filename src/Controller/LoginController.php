<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{
    #[Route('/login')]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        
        $parameters = json_decode($request->getContent(), true);
        $username = $parameters['username'];
        $password = $parameters['password'];

        $user = $entityManager->getRepository(User::class)->findOneByUsername($username);
        if(!$user) {
            $response = [
                'status' => 'NOK',
                'message' => 'User not found'
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        if (!password_verify($password, $user->getPassword())) {
            $response = [
                'status' => 'NOK',
                'message' => 'Incorrect username or password'
            ];
            return new JsonResponse($response, Response::HTTP_UNAUTHORIZED);
        } 

        $key        = $this->getParameter('jwt.secret_key');
        $date       = new DateTimeImmutable();
        $expire_at  = $date->modify('+60 minutes')->getTimestamp(); 
        $domainName = "cardmarket.cyrusnimda.com";
        $username   = $user->getUsername();                                           
        $payload = [
            'iss'  => $domainName,                      // Issuer
            'iat'  => $date->getTimestamp(),            // Issued at: time when the token was generated
            'nbf'  => $date->getTimestamp(),            // Not before
            'exp'  => $expire_at,                       // Expire in 1 hour
            'userName' => $username,                    // User name
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        $response = [
            'status' => 'OK',
            'token' => $jwt,
        ];
        return new JsonResponse($response);
    }
}
