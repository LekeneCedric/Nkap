<?php

namespace Code237\Nkap\Account\tests\Unit\Repository;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;

class InMemoryAccountRepository implements AccountRepository
{
    /**
     * @var Account[]
     */
    public array $accounts = [];
    public function save(Account $account): true
    {
        $this->accounts[$account->id()->value()] = $account;

        return true;
    }
}