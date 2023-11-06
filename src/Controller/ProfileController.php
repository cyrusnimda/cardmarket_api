<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    public function index(Request $request): JsonResponse
    {
        return $this->json([
            'message' => 'OK',
            'data' => [
                'username' => $this->getUser()->getUsername(),
            ],
        ]);
    }

    #[Route('/update', name: 'app_profile_update')]
    public function update(): JsonResponse
    {
        return $this->json([
            'message' => 'OK',
        ]);
    }
}
