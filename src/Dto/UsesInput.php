<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;

class UsesInput
{
    #[Groups(['treeInput:write'])]
    public ?string $title = null;

    #[Groups(['treeInput:write'])]
    public ?string $description = null;
    
    #[Groups(['status:write'])]
    public ?string $status = null;
}