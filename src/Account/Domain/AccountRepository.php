<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Shared\VO\Id;

interface AccountRepository
{
    /**
     * @param Account $account
     * @return true
     */
    public function create(Account $account): true;

    /**
     * @param Id $accountId
     * @return Account|null
     */
    public function getById(Id $accountId): ?Account;

    /**
     * @param Account $accountToUpdate
     * @return true
     */
    public function update(Account $accountToUpdate): true;

    public function delete(Id $accountId): true;
}