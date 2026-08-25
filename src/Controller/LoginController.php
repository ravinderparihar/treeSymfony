<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $credentials = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return new JsonResponse(['message' => 'Request body must be valid JSON.'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_array($credentials) || !is_string($credentials['password'] ?? null)) {
            return new JsonResponse(['message' => 'Username or email and password are required.'], Response::HTTP_BAD_REQUEST);
        }

        $identifier = $credentials['username'] ?? $credentials['email'] ?? null;
        if (!is_string($identifier) || $identifier === '') {
            return new JsonResponse(['message' => 'Username or email and password are required.'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneBy(['username' => $identifier])
            ?? $this->userRepository->findOneBy(['email' => $identifier]);

        if (!$user instanceof User || $user->getStatus() !== 1 || !$this->passwordHasher->isPasswordValid($user, $credentials['password'])) {
            return new JsonResponse(['message' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'message' => 'Login successful.',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'profileImage' => $user->getProfileImage(),
                'status' => $user->getStatus(),
            ],
        ]);
    }
}