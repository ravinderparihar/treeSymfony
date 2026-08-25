<?php

namespace App\Entity;

use App\Enum\UserRole;
use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private ?int $id = null;
    private ?string $username = null;
    private string $name = '';
    private ?string $email = null;
    private string $password = '';
    private string $roles = '[]';
    private ?string $profileImage = null;
    private int $status = 1;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setRoles([UserRole::USER->value]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;
        $this->touch();

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        $this->touch();

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        $this->touch();

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? $this->name;
    }

    #[ApiProperty(readable: false, writable: false)]
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        $this->touch();

        return $this;
    }

    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true);

        $roles = is_array($roles) ? $roles : [];
        $roles = array_map(static fn (string $role): string => str_starts_with($role, 'ROLE_') ? $role : 'ROLE_'.$role, $roles);

        return array_values(array_unique(array_merge(['ROLE_USER'], $roles)));
    }

    public function setRoles(array $roles): static
    {
        $this->roles = json_encode(array_values(array_unique($roles)), JSON_THROW_ON_ERROR);
        $this->touch();

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): static
    {
        $this->profileImage = $profileImage;
        $this->touch();

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;
        $this->touch();

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function eraseCredentials(): void
    {
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}