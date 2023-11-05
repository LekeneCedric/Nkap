<?php

namespace Code237\Nkap\User\tests\Unit\Repository;

use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    public array $users = [];
    public function save(User $user): true
    {
        $this->users[$user->id()->value()] = $user;

        return true;
    }

    public function byId(Id $userId): ?User
    {
        if (!array_key_exists($userId->value(), $this->users)) {
            return null;
        }
        return $this->users[$userId->value()];
    }

    public function deleteById(Id $userId): true
    {
        if (array_key_exists($userId->value(), $this->users)) {
            unset($this->users[$userId->value()]);
        }
        return true;
    }
}