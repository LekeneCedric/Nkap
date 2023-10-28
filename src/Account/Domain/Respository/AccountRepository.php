<?php

namespace Code237\Nkap\Account\Domain\Respository;

use Code237\Nkap\Account\Domain\Account;

interface AccountRepository
{
    /**
     * @param Account $account
     * @return true
     */
    public function save(Account $account): true;
}