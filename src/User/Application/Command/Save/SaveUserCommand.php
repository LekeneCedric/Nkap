<?php

namespace Code237\Nkap\User\Application\Command\Save;

class SaveUserCommand
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
    )
    {
    }
}