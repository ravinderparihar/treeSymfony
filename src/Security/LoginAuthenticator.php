<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

final class LoginAuthenticator extends AbstractAuthenticator
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->isMethod('POST') && $request->getPathInfo() === '/api/login_check';
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = json_decode($request->getContent(), true);
        $identifier = is_array($credentials) ? ($credentials['email'] ?? $credentials['username'] ?? '') : '';
        $password = is_array($credentials) ? ($credentials['password'] ?? '') : '';

        return new Passport(
            new UserBadge((string) $identifier, function (string $identifier): User {
                $user = $this->userRepository->findOneBy(['username' => $identifier])
                    ?? $this->userRepository->findOneBy(['email' => $identifier]);

                if (!$user instanceof User || $user->getStatus() !== 1) {
                    throw new AuthenticationException('Invalid credentials.');
                }

                return $user;
            }),
            new PasswordCredentials((string) $password),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse(['message' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
    }
}