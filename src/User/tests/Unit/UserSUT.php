<?php

namespace Code237\Nkap\User\tests\Unit;

use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\EmailVO;
use Code237\Nkap\Shared\VO\PasswordVO;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;

class UserSUT
{
    public User $user;
    public static function asSUT(): UserSUT
    {
       $self = new self();

       $self->user = User::create(
           name: new StringVO("Lekene"),
           surname: new StringVO("Luc cedric"),
           email: new EmailVO("lekene@gmail.com"),
           password: PasswordVO::fromPassword("hackerMan"),
           createdAt: new DateVO(), updatedAt: new DateVO()
       );

       return $self;
    }

    public function withName(string $name): static
    {
        $this->user->changeName(new StringVO($name));

        return $this;
    }

    public function withSurname(string $surname): static
    {
        $this->user->changeSurname(new StringVO($surname));

        return $this;
    }

    public function withEmail(string $email): static
    {
        $this->user->changeEmail(new EmailVO($email));

        return $this;
    }

    public function build(): static
    {
        return $this;
    }
}