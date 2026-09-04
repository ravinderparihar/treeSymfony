<?php

namespace App\Controller;

use App\Dto\ResetPasswordInput;
use App\Entity\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ResetPasswordController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        // Not type-hinting ResetPasswordInput directly: since this controller is tagged
        // controller.service_arguments, Symfony's ServiceValueResolver would resolve it as
        // a fresh container-instantiated service (all fields null) instead of the request's
        // deserialized data, so it's deserialized manually here instead.
        try {
            $data = $this->serializer->deserialize($request->getContent(), ResetPasswordInput::class, 'json');
        } catch (\Throwable) {
            return new JsonResponse(['message' => 'Request body must be valid JSON.'], Response::HTTP_BAD_REQUEST);
        }

        $violations = $this->validator->validate($data);
        if (\count($violations) > 0) {
            return new JsonResponse(['message' => (string) $violations], Response::HTTP_BAD_REQUEST);
        }

        $resetToken = $this->entityManager->getRepository(PasswordResetToken::class)->findOneBy([
            'tokenHash' => hash('sha256', $data->token ?? ''),
            'usedAt' => null,
        ]);

        if (!$resetToken instanceof PasswordResetToken || $resetToken->getExpiresAt() <= new \DateTimeImmutable()) {
            throw new BadRequestHttpException('The password reset token is invalid or expired.');
        }

        $user = $resetToken->getUser();
        $user->setPassword($this->passwordHasher->hashPassword($user, $data->newPassword ?? ''));
        $resetToken->markUsed();
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Password reset successfully.']);
    }
}
