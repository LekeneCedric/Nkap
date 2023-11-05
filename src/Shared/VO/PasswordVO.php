<?php

namespace Code237\Nkap\Shared\VO;

class PasswordVO
{
    public function __construct(
        private ?string $password = null,
        private ?string $hashedPassword = null,
    )
    {
    }

    public static function fromPassword(string $password): PasswordVO
    {
        $self = new self();

        $self->password = $password;
        $self->hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        return $self;
    }

    public static function fromHashedPassword(string $hashedPassword): PasswordVO
    {
        $self = new self();

        $self->hashedPassword = $hashedPassword;

        return $self;
    }

    public function MatchPassword(string $password): bool
    {
        return password_verify($password, $this->hashedPassword);
    }
    public function hashedPasswordValue(): string
    {
        return $this->hashedPassword;
    }

    public function passwordValue(): ?string
    {
        return $this->password;
    }
}