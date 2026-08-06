<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;

class ImageInput
{
    #[Groups(['treeInput:write'])]
    public ?string $imageType = null;

    #[Groups(['treeInput:write'])]
    public ?string $imageUrl = null;

    
}