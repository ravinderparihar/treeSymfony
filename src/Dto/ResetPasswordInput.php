<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordInput
{
    #[Groups(['password:reset:write'])]
    #[Assert\NotBlank]
    public ?string $token = null;

    #[Groups(['password:reset:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public ?string $newPassword = null;
}
