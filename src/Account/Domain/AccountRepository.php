<?php

namespace Code237\Nkap\Account\Domain;

interface AccountRepository
{
    /**
     * @param Account $account
     * @return true
     */
    public function save(Account $account): true;
}