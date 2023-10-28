<?php

namespace Code237\Nkap\Account\Infrastructure\Repositories;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\Lib\PdoConnection;

readonly class PdoAccountRepository implements AccountRepository
{
    public function __construct(
        private PdoConnection $pdoConnection,
    )
    {
    }

    public function save(Account $account): true
    {
        $id = $account->id()->value();
        $userId = $account->userId()->value();
        $name = $account->name()->value();
        $isIncludeInTotal = $account->isIncludeInTotalBalance();
        $balance = $account->balance()->value();
        $totalIncomes = $account->totalIncomes()->value();
        $totalExpenses = $account->totalExpenses()->value();
        $lastTransactionDate = $account->lastTransactionDate()->formatYMDHIS();
        $iconName = $account->iconName()->value();
        $color = $account->color()->value();
        $createdAt = $account->createdAt()->formatYMDHIS();
        $updatedAt = $account->updatedAt()->formatYMDHIS();

        $sql = "
            INSERT INTO Accounts 
                (
                 uuid, user_id, name, is_include_in_total_balance, balance, total_incomes, total_expenses,
                 last_transaction_date, icon_name, color, created_at, updated_at
                 ) 
            VALUES (
                    :uuid, :user_id, :name, :isIncludeInTotalBalance, :balance, :totalIncomes, :totalExpenses,
                    :lastTransactionDate, :iconName, :color, :createdAt, :updatedAt
                    )
        ";

        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('uuid', $id);
        $statement->bindParam('user_id', $userId);
        $statement->bindParam('name', $name);
        $statement->bindParam('isIncludeInTotalBalance', $isIncludeInTotal);
        $statement->bindParam('balance', $balance);
        $statement->bindParam('totalIncomes', $totalIncomes);
        $statement->bindParam('totalExpenses', $totalExpenses);
        $statement->bindParam('lastTransactionDate', $lastTransactionDate);
        $statement->bindParam('iconName', $iconName);
        $statement->bindParam('color', $color);
        $statement->bindParam('createdAt', $createdAt);
        $statement->bindParam('updatedAt', $updatedAt);

        $statement->execute();

        return true;
    }
}