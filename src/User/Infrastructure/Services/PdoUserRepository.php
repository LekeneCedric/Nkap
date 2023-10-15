<?php

namespace Code237\Nkap\User\Infrastructure\Services;

use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\User\Domain\User;
use Code237\Nkap\User\Repository\UserRepository;

class PdoUserRepository implements UserRepository
{

    public function save(User $user): true
    {

        $sql = "";
        return true;
    }

    public function byId(Id $userId): ?User
    {
        return null;
    }
}