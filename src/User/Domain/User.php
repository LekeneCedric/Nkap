<?php

namespace Code237\Nkap\User\Domain;

use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\EmailVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\PasswordVO;
use Code237\Nkap\Shared\VO\StringVO;

class User
{
    public function __construct(
        private readonly Id      $id,
        private StringVO         $name,
        private StringVO         $surName,
        private EmailVO          $email,
        private PasswordVO       $password,
        private readonly ?DateVO $createdAt,
        private ?DateVO          $updatedAt,
    )
    {
    }

    public static function create(
        StringVO   $name,
        StringVO   $surname,
        EmailVO    $email,
        PasswordVO $password,
        ?Id        $id = null,
        ?DateVO    $createdAt = null,
        ?DateVO    $updatedAt = null,
    ): self
    {
        return new self(
            id: $id ?? new Id(),
            name: $name,
            surName: $surname,
            email: $email,
            password: $password,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    /**
     * @param StringVO $name
     * @return void
     */
    public function changeName(StringVO $name): void
    {
        $this->name = $name;
        $this->updatedAt = new DateVO();
    }

    /**
     * @param StringVO $surname
     * @return void
     */
    public function changeSurname(StringVO $surname): void
    {
        $this->surName = $surname;
        $this->updatedAt = new DateVO();
    }

    /**
     * @param EmailVO $email
     * @return void
     */
    public function changeEmail(EmailVO $email): void
    {
        $this->email = $email;
        $this->updatedAt = new DateVO();
    }

    public function changePassword(PasswordVO $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateVO();
    }
    public function id(): Id
    {
        return $this->id;
    }
    /**
     * @return StringVO
     */
    public function name(): StringVO
    {
        return $this->name;
    }

    /**
     * @return StringVO
     */
    public function surName(): StringVO
    {
        return $this->surName;
    }

    /**
     * @return EmailVO
     */
    public function email(): EmailVO
    {
        return $this->email;
    }

    public function password(): PasswordVO
    {
        return $this->password;
    }

    public function createdAt(): ?DateVO
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateVO
    {
        return $this->updatedAt;
    }

    /**
     * @throws \Exception
     */
    public function mappedData(): array
    {
        return [
          "uuid" => $this->id->value(),
          "name" => $this->name->value(),
          "surname" => $this->surName->value(),
          "email" => $this->email->value(),
          "password" => $this->password->hashedPasswordValue(),
          "created_at" => $this->createdAt->formatYMDHIS(),
          "updated_at" => $this->updatedAt->formatYMDHIS(),
        ];
    }
}
