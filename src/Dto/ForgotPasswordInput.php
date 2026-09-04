<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class ForgotPasswordInput
{
    #[Groups(['password:forgot:write'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;
}
