<?php

namespace Code237\Nkap\Account\Infrastructure\Repositories;

use Code237\Nkap\Account\Application\Command\Account\AccountDto;
use Code237\Nkap\Account\Application\Command\Transaction\TransactionDto;
use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\Domain\Transaction;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\Lib\PdoConnection;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
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

        if (empty($result)) {
            return null;
        }
        $account = Account::create(
            userId: new Id($result->userId),
            balance: new AmountVO($result->balance),
            name: new StringVO($result->name),
            isIncludeInTotalBalance: $result->isIncludeInTotalBalance,
            id: new Id($result->id),
            createdAt: new DateVO($result->createdAt),
            updatedAt: new DateVO($result->updatedAt),
            totalIncomes: new AmountVO($result->totalIncomes),
            totalExpenses: new AmountVO($result->totalExpenses),
            lastTransactionDate: new DateVO($result->lastTransactionDate),
            color: new StringVO($result->color),
            iconName: new StringVO($result->iconName),
        );
        $account->changeTransactions($this->getTransactionsByAccountId($accountId));

        return $account;
    }

    /**
     * @throws Exception
     */
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

        $this->createTransactions($account->transactions());

        return true;
    }

    /**
     * @param Account $accountToUpdate
     * @return true
     * @throws Exception
     */
    public function update(Account $accountToUpdate): true
    {
        $accountTransactions = $accountToUpdate->transactions();

        $accountId = $accountToUpdate->id()->value();
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
        $statement->bindParam('id', $accountId);
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

        $this->updateTransactionsByAccountId($accountId, $accountTransactions);

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

    /**
     * @param Id $accountId
     * @return Transaction[]
     */
    private function getTransactionsByAccountId(Id $accountId): array
    {
        $accountIdValue = $accountId->value();

        $sql = "
            SELECT 
                uuid as id,
                account_id as accountId,
                category_id as categoryId,
                type,
                amount,
                description,
                operation_date as operationDate,
                created_at as createdAt,
                updated_at as updatedAt
            FROM Transactions WHERE account_id = :accountId
        ";

        $statement = $this->pdoConnection->getPdo()->prepare($sql);
        $statement->bindParam('accountId', $accountIdValue);
        $statement->setFetchMode(\PDO::FETCH_CLASS, TransactionDto::class);
        $statement->execute();
        $result = $statement->fetchAll();

        return array_map(fn(TransactionDto $transaction) => Transaction::create(
            accountId: new Id($transaction->accountId),
            transactionCategoryId: new Id($transaction->categoryId),
            type: TransactionTypeEnum::in($transaction->type),
            amount: new AmountVO($transaction->amount),
            description: new StringVO($transaction->description),
            operationDate: new DateVO($transaction->operationDate),
            id: new Id($transaction->id),
            createdAt: new DateVO($transaction->createdAt),
            updatedAt: new DateVO($transaction->updatedAt)
        ), $result);
    }

    /**
     * @param Transaction[] $transactions
     * @return void
     * @throws Exception
     */
    private function createTransactions(array $transactions): void
    {
        foreach ($transactions as $transaction) {
            $id = $transaction->id()->value();
            $accountId = $transaction->accountId()->value();
            $transactionCategoryId = $transaction->transactionCategory()->value();
            $transactionType = $transaction->transactionType()->value;
            $transactionAmount = $transaction->amount()->value();
            $transactionDescripion = $transaction->description()->value();
            $transactionOperateDate = $transaction->operationDate()->formatYMDHIS();
            $transactionCreatedAt = $transaction->createdAt()->formatYMDHIS();
            $transactionUpdatedAt = $transaction->updatedAt()->formatYMDHIS();

            $sql = "
                INSERT INTO Transactions (uuid, account_id, category_id, type, amount, description, operation_date, created_at, updated_at) 
                VALUES (:uuid, :accountId, :categoryId, :type, :amount, :description, :operationDate, :createdAt, :updatedAt)
            ";

            $statement = $this->pdoConnection->getPdo()->prepare($sql);

            $statement->bindParam('uuid', $id);
            $statement->bindParam('accountId', $accountId);
            $statement->bindParam('categoryId', $transactionCategoryId);
            $statement->bindParam('type', $transactionType);
            $statement->bindParam('amount', $transactionAmount);
            $statement->bindParam('description', $transactionDescripion);
            $statement->bindParam('operationDate', $transactionOperateDate);
            $statement->bindParam('createdAt', $transactionCreatedAt);
            $statement->bindParam('updatedAt', $transactionUpdatedAt);

            $statement->execute();
        }
    }

    /**
     * @param string $accountId
     * @param Transaction[] $accountTransactions
     * @return void
     * @throws Exception
     */
    private function updateTransactionsByAccountId(string $accountId, array $accountTransactions): void
    {
        foreach ($accountTransactions as $transaction)
        {
            $transactionId = $transaction->id()->value();
            $transactionCategoryId = $transaction->transactionCategory()->value();
            $transactionType = $transaction->type()->value;
            $transactionAmount = $transaction->amount()->value();
            $transactionDescription = $transaction->description()->value();
            $transactionOperationDate = $transaction->operationDate()->formatYMDHIS();
            $transactionCreatedAt = $transaction->createdAt()->formatYMDHIS();
            $transactionUpdatedAt = $transaction->updatedAt()->formatYMDHIS();

            $sql = "
                UPDATE Transactions 
                SET
                    category_id = :categoryId,
                    type = :type,
                    amount = :amount,
                    description = :description,
                    operation_date = :operationDate,
                    created_at = :createdAt,
                    updated_at = :updatedAt
                WHERE uuid = :id AND account_id = :accountId";

            $statement = $this->pdoConnection->getPdo()->prepare($sql);

            $statement->bindParam('id', $transactionId);
            $statement->bindParam('accountId', $accountId);
            $statement->bindParam('categoryId', $transactionCategoryId);
            $statement->bindParam('type', $transactionType);
            $statement->bindParam('amount', $transactionAmount);
            $statement->bindParam('description', $transactionDescription);
            $statement->bindParam('operationDate', $transactionOperationDate);
            $statement->bindParam('createdAt', $transactionCreatedAt);
            $statement->bindParam('updatedAt', $transactionUpdatedAt);

            $statement->execute();
        }

    }
}