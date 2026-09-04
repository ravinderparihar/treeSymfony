<?php

namespace App\Controller;

use App\Dto\ForgotPasswordInput;
use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ForgotPasswordController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private string $passwordResetUrl,
        private string $mailerFrom,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        // Not type-hinting ForgotPasswordInput directly: since this controller is tagged
        // controller.service_arguments, Symfony's ServiceValueResolver would resolve it as
        // a fresh container-instantiated service (all fields null) instead of the request's
        // deserialized data, so it's deserialized manually here instead.
        try {
            $data = $this->serializer->deserialize($request->getContent(), ForgotPasswordInput::class, 'json');
        } catch (\Throwable) {
            return new JsonResponse(['message' => 'Request body must be valid JSON.'], Response::HTTP_BAD_REQUEST);
        }

        $violations = $this->validator->validate($data);
        if (\count($violations) > 0) {
            return new JsonResponse(['message' => (string) $violations], Response::HTTP_BAD_REQUEST);
        }

        $message = 'If an account exists for this email, a password reset link has been sent.';
        $user = $this->userRepository->findOneBy(['email' => $data->email]);

        if (!$user instanceof User || $user->getStatus() !== 1 || $user->getEmail() === null) {
            return new JsonResponse(['message' => $message]);
        }

        $plainToken = Uuid::v4()->toRfc4122().bin2hex(random_bytes(16));
        $resetToken = new PasswordResetToken(
            $user,
            hash('sha256', $plainToken),
            new \DateTimeImmutable('+1 hour'),
        );

        $this->entityManager->persist($resetToken);
        $this->entityManager->flush();

        $resetLink = rtrim($this->passwordResetUrl, '?&').'?' . http_build_query(['token' => $plainToken]);
        $email = (new Email())
            ->from($this->mailerFrom)
            ->to($user->getEmail())
            ->subject('Reset your password')
            ->text("Use this link to reset your password. It expires in 1 hour:\n\n{$resetLink}");

        $this->mailer->send($email);

        return new JsonResponse(['message' => $message], Response::HTTP_ACCEPTED);
    }
}
