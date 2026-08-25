<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordChangeInput
{
    #[Assert\NotBlank]
    public ?string $currentPassword = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public ?string $newPassword = null;
}