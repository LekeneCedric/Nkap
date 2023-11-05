<?php

namespace Code237\Nkap\User\Domain;

use Code237\Nkap\Shared\VO\Id;

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