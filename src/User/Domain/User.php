<?php

namespace Code237\Nkap\User\Domain;

use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\EmailVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\PasswordVo;
use Code237\Nkap\Shared\VO\StringVO;

class User
{
    public function __construct(
        private readonly Id      $id,
        private StringVO         $name,
        private StringVO         $surName,
        private EmailVo          $email,
        private PasswordVo       $password,
        private readonly ?Datevo $createdAt,
        private ?Datevo          $updatedAt,
    )
    {
    }

    public static function create(
        StringVO $name,
        StringVO $surname,
        EmailVo $email,
        PasswordVo $password,
        ?Id $id = null,
        ?Datevo $createdAt = null,
        ?Datevo $updatedAt = null,
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
        $this->updatedAt = new Datevo();
    }

    /**
     * @param StringVO $surname
     * @return void
     */
    public function changeSurname(StringVO $surname): void
    {
        $this->surName = $surname;
        $this->updatedAt = new Datevo();
    }

    /**
     * @param EmailVo $email
     * @return void
     */
    public function changeEmail(EmailVo $email): void
    {
        $this->email = $email;
        $this->updatedAt = new Datevo();
    }

    public function changePassword(PasswordVo $password): void
    {
        $this->password = $password;
        $this->updatedAt = new Datevo();
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
     * @return EmailVo
     */
    public function email(): EmailVo
    {
        return $this->email;
    }

    public function password(): PasswordVo
    {
        return $this->password;
    }

    public function createdAt(): ?Datevo
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?Datevo
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
