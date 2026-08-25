<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserInput
{
    public ?string $username = null;

    #[Assert\NotBlank]
    public ?string $name = null;

    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public ?string $password = null;

    public ?string $profileImage = null;
    public int $status = 1;
}