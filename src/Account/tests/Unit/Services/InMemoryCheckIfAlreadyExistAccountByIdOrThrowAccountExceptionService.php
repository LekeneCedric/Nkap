<?php

namespace Code237\Nkap\Account\tests\Unit\Services;

use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Services\CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService;
use Code237\Nkap\Shared\VO\Id;

class InMemoryCheckIfAlreadyExistAccountByIdOrThrowAccountExceptionService implements CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService
{
    public array $accounts = [];

    /**
     * @throws NotFoundAccountException
     */
    public function execute(Id $accountId): bool
    {
        $isAccountAlreadyExist = array_key_exists($accountId->value(), $this->accounts);
        if (!$isAccountAlreadyExist) {
            throw new NotFoundAccountException("Ce compte n'existe pas !");
        }

        return true;
    }
}