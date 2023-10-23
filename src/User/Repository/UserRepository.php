<?php

namespace Code237\Nkap\User\Repository;

use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\User\Domain\User;

interface UserRepository
{
    /**
     * @param User $user
     * @return true
     */
    public function save(User $user): true;
    public function byId(Id $userId): ?User;

    public function deleteById(Id $userId): true;
}