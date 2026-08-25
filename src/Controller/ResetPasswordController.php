<?php

namespace App\Controller;

use App\Dto\ResetPasswordInput;
use App\Entity\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ResetPasswordController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(ResetPasswordInput $data): JsonResponse
    {
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
