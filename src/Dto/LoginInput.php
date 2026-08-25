<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginInput
{
    #[Assert\Email]
    public ?string $email = null;

    public ?string $username = null;

    #[Assert\NotBlank]
    public ?string $password = null;
}