<?php

namespace Code237\Nkap\Account\Infrastructure\Repositories;

use Code237\Nkap\Account\Application\Command\Create\AccountDto;
use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Shared\Lib\PdoConnection;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use Exception;
use PDO;

readonly class PdoAccountRepository implements AccountRepository
{
    public function __construct(
        private PdoConnection $pdoConnection,
    )
    {
    }

    public function create(Account $account): true
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
                    :uuid, :userId, :name, :isIncludeInTotalBalance, :balance, :totalIncomes, :totalExpenses,
                    :lastTransactionDate, :iconName, :color, :createdAt, :updatedAt
                    )
        ";

        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('uuid', $id);
        $statement->bindParam('userId', $userId);
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

    public function getById(Id $accountId): ?Account
    {
        $accountIdValue = $accountId->value();

        $sql = "
            SELECT 
                uuid as id,
                user_id as userId,
                name,
                is_include_in_total_balance as isIncludeInTotalBalance,
                balance,
                total_incomes as totalIncomes,
                total_expenses as totalExpenses,
                last_transaction_date as lastTransactionDate,
                icon_name as iconName,
                color,
                created_at as createdAt,
                updated_at as updatedAt
            
            FROM Accounts 
            WHERE uuid = :accountId
        ";
        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('accountId', $accountIdValue);
        $statement->setFetchMode(PDO::FETCH_CLASS, AccountDto::class);
        $statement->execute();

        $result = $statement->fetch();

        if (empty($result)){
            return null;
        }
        return Account::create(
            userId: new Id($result->userId),
            balance: new AmountVo($result->balance),
            name: new StringVO($result->name),
            isIncludeInTotalBalance: $result->isIncludeInTotalBalance,
            id: new Id($result->id),
            createdAt: new Datevo($result->createdAt),
            updatedAt: new Datevo($result->updatedAt),
            totalIncomes: new AmountVo($result->totalIncomes),
            totalExpenses: new AmountVo($result->totalExpenses),
            lastTransactionDate: new Datevo($result->lastTransactionDate),
            color: new StringVO($result->color),
            iconName: new StringVO($result->iconName),
        );
    }

    /**
     * @param Account $accountToUpdate
     * @return true
     * @throws Exception
     */
    public function update(Account $accountToUpdate): true
    {
        $id = $accountToUpdate->id()->value();
        $userId = $accountToUpdate->userId()->value();
        $name = $accountToUpdate->name()->value();
        $isIncludeInTotal = $accountToUpdate->isIncludeInTotalBalance();
        $balance = $accountToUpdate->balance()->value();
        $totalIncomes = $accountToUpdate->totalIncomes()->value();
        $totalExpenses = $accountToUpdate->totalExpenses()->value();
        $lastTransactionDate = $accountToUpdate->lastTransactionDate()->formatYMDHIS();
        $iconName = $accountToUpdate->iconName()->value();
        $color = $accountToUpdate->color()->value();
        $createdAt = $accountToUpdate->createdAt()->formatYMDHIS();
        $updatedAt = $accountToUpdate->updatedAt()->formatYMDHIS();

        $sql = "
            UPDATE Accounts
            SET 
                user_id = :userId,
                name = :name,
                is_include_in_total_balance = :isIncludeInTotalBalance,
                balance = :balance,
                total_incomes = :totalIncomes,
                total_expenses = :totalExpenses,
                last_transaction_date = :lastTransactionDate,
                icon_name = :iconName,
                color = :color,
                created_at = :createdAt,
                updated_at = :updatedAt
            WHERE uuid = :id
        ";

        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('id', $id);
        $statement->bindParam('userId', $userId);
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

    public function delete(Id $accountId): true
    {
        $accountIdValue = $accountId->value();

        $sql = "
            DELETE 
            FROM Accounts
            WHERE uuid = :accountId
        ";

        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('accountId', $accountIdValue);
        $statement->execute();

        return true;
    }
}