<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\PasswordChangeInput;
use App\Dto\UserInput;
use App\Dto\UserUpdateInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): mixed {
        if ($data instanceof PasswordChangeInput) {
            return $this->changePassword($data, $uriVariables);
        }

        if ($data instanceof UserUpdateInput) {
            return $this->updateProfile($data, $uriVariables);
        }

        if (!$data instanceof UserInput) {
            return $data;
        }

        $user = new User();
        $user->setUsername($data->username)
            ->setName($data->name ?? '')
            ->setEmail($data->email)
            ->setProfileImage($data->profileImage)
            ->setStatus($data->status)
            ->setPassword($this->passwordHasher->hashPassword($user, $data->password ?? ''));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function changePassword(PasswordChangeInput $data, array $uriVariables): User
    {
        $user = $this->entityManager->getRepository(User::class)->find($uriVariables['id'] ?? null);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $data->currentPassword ?? '')) {
            throw new BadRequestHttpException('Current password is invalid.');
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $data->newPassword ?? ''));
        $this->entityManager->flush();

        return $user;
    }

    private function updateProfile(UserUpdateInput $data, array $uriVariables): User
    {
        $user = $this->entityManager->getRepository(User::class)->find($uriVariables['id'] ?? null);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('User not found.');
        }

        if ($data->name !== null) {
            $user->setName($data->name);
        }

        if ($data->username !== null) {
            $user->setUsername($data->username);
        }

        if ($data->email !== null) {
            $user->setEmail($data->email);
        }

        if ($data->profileImage !== null) {
            $user->setProfileImage($data->profileImage);
        }

        if ($data->status !== null) {
            $user->setStatus($data->status);
        }

        $this->entityManager->flush();

        return $user;
    }
}