<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateInput
{
    public ?string $username = null;

    public ?string $name = null;

    #[Assert\Email]
    public ?string $email = null;

    public ?string $profileImage = null;
    public ?int $status = null;
}