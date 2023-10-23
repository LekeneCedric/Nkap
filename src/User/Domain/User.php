<?php

namespace Code237\Nkap\User\Domain;

use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\EmailVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class User
{
    private ?Datevo $createdAt;
    private ?Datevo $updatedAt;
    private bool $isDeleted = false;
    
    public function __construct(
        private readonly Id $id,
        private StringVO $name,
        private StringVO $surName,
        private EmailVo $email,
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
    }

    public static function create(
        StringVO $name,
        StringVO $surname,
        EmailVo $email,
        ?Id $id = null,
    ): self
    {
        return new self(
            id: $id ?? new Id(),
            name: $name,
            surName: $surname,
            email: $email,
        );
    }

    /**
     * @param StringVO $name
     * @return void
     */
    public function changeName(StringVO $name): void
    {
        $this->name = $name;
    }

    /**
     * @param StringVO $surname
     * @return void
     */
    public function changeSurname(StringVO $surname): void
    {
        $this->surName = $surname;
    }

    /**
     * @param EmailVo $email
     * @return void
     */
    public function changeEmail(EmailVo $email): void
    {
        $this->email = $email;
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

    public function delete(): bool
    {
        return $this->isDeleted;
    }

    public function createdAt(): ?Datevo
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?Datevo
    {
        return $this->updatedAt;
    }

    public function mappedData(): array
    {
        return [
          "uuid" => $this->id->value(),
          "name" => $this->name->value(),
          "surname" => $this->surName->value(),
          "email" => $this->email->value()
        ];
    }
}
