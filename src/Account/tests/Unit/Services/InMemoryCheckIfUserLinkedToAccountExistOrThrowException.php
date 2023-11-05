<?php

namespace Code237\Nkap\Account\tests\Unit\Services;

use Code237\Nkap\Account\Domain\Services\CheckIfUserLinkedToAccountExistOrThrowException;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\User\Domain\User;

class InMemoryCheckIfUserLinkedToAccountExistOrThrowException implements CheckIfUserLinkedToAccountExistOrThrowException
{
    /**
     * @var User[]
     */
    public array $users = [];
    /**
     * @inheritDoc
     */
    public function execute(Id $userid): bool
    {
        return array_key_exists($userid->value(), $this->users);
    }
}