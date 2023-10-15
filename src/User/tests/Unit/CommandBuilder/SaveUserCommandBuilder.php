<?php

namespace Code237\Nkap\User\tests\Unit\CommandBuilder;

use Code237\Nkap\User\Application\Command\Save\SaveUserCommand;

class SaveUserCommandBuilder
{
    private ?string $name = null;
    private ?string $surname = null;
    private ?string $email = null;
    public static function asSUT(): SaveUserCommandBuilder
    {
        return new self();
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function build(): SaveUserCommand
    {
        $name = $this->name;
        $surname = $this->surname;
        $email = $this->email;

        return new SaveUserCommand(
            name: $name,
            surname: $surname,
            email: $email
        );
    }
}