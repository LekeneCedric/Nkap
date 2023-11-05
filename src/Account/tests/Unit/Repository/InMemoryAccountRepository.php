<?php

namespace Code237\Nkap\Account\tests\Unit\Repository;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Shared\VO\Id;

class InMemoryAccountRepository implements AccountRepository
{
    /**
     * @var Account[]
     */
    public array $accounts = [];
    public function create(Account $account): true
    {
        $this->accounts[$account->id()->value()] = $account;
        return true;
    }

    /**
     * @param Id $accountId
     * @return Account|null
     */
    public function getById(Id $accountId): ?Account
    {
        if (!array_key_exists($accountId->value() ,$this->accounts)) {
            return null;
        }
        return $this->accounts[$accountId->value()];
    }

    public function update(Account $accountToUpdate): true
    {
        $this->accounts[$accountToUpdate->id()->value()] = $accountToUpdate;
        return true;
    }

    public function delete(Id $accountId): true
    {
        unset($this->accounts[$accountId->value()]);

        return true;
    }
}