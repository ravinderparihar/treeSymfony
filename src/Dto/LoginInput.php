<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class LoginInput
{
    #[Groups(['login:write'])]
    #[Assert\Email]
    public ?string $email = null;

    #[Groups(['login:write'])]
    public ?string $username = null;

    #[Groups(['login:write'])]
    #[Assert\NotBlank]
    public ?string $password = null;
}