<?php

namespace Code237\Nkap\User\Application\Command\Save;

use Code237\Nkap\Shared\VO\Id;

class SaveUserResponse
{
    public bool $isSaved = false;
    public ?string $userId = null;
}