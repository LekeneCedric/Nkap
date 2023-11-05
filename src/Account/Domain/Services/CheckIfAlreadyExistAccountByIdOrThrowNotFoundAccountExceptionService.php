<?php

namespace Code237\Nkap\Account\Domain\Services;

use Code237\Nkap\Shared\VO\Id;

interface CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService
{
    /**
     * @param Id $accountId
     * @return bool
     */
    public function execute(Id $accountId): bool;
}