<?php

namespace Code237\Nkap\Account\Domain\Services;

use Code237\Nkap\Shared\VO\Id;

interface CheckIfUserLinkedToAccountExistOrThrowException
{
    /**
     * @param Id $userid
     * @return bool
     */
    public function execute(Id $userid): bool;
}