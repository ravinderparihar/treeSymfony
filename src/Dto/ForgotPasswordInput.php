<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ForgotPasswordInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;
}
