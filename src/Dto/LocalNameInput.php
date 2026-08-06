<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\Groups;

class LocalNameInput
{
    #[Groups(['treeInput:write'])]
    public ?string $localName = null;

    #[Groups(['treeInput:write'])]
    public ?string $language = null;
}