<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class Account
{
    private ?DateVO $createdAt = null;
    private ?DateVO $updatedAt = null;
    private array $deletedTransactionIds = [];
    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    public function __construct(
        private readonly Id $id,
        private Id          $userId,
        private AmountVO    $balance,
        private StringVO    $accountName,
        private bool        $isIncludeInTotalBalance,
        private StringVO    $iconName,
        private StringVO    $color,
        private AmountVO    $totalIncomes,
        private AmountVO    $totalExpenses,
        private DateVO      $lastTransactionDate,
    )
    {
    }

    public function userId(): Id
    {
        return $this->userId;
    }

    public function balance(): AmountVO
    {
        return $this->balance;
    }

    public function name(): StringVO
    {
        return $this->accountName;
    }

    public function isIncludeInTotalBalance(): bool
    {
        return $this->isIncludeInTotalBalance;
    }

    public function lastTransactionDate(): DateVO
    {
        return $this->lastTransactionDate;
    }

    public function iconName(): StringVO
    {
        return $this->iconName;
    }

    public function color(): StringVO
    {
        return $this->color;
    }

    public function changeName(StringVO $name): void
    {
        $this->accountName = $name;
    }

    public function transactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Id $transactionId
     * @return Transaction|null
     */
    public function getTransaction(Id $transactionId): ?Transaction
    {
        if (!array_key_exists($transactionId->value(), $this->transactions)) {
            return null;
        }
        return $this->transactions[$transactionId->value()];
    }

    /**
     * @throws InvalidTransactionException
     */
    public function updateTransaction(Transaction $updatedTransaction): void
    {
        $previousTransactionCreatedAt = $this->transactions[$updatedTransaction->id()->value()]->createdAt();
        $this->removeTransaction( transactionId: $updatedTransaction->id(), toAddToTransactionToDelete: false);
        $this->addTransaction(
            transactionCategoryId: $updatedTransaction->transactionCategory(),
            transactionType: $updatedTransaction->transactionType(),
            transactionAmount: $updatedTransaction->amount(),
            transactionDescription: $updatedTransaction->description(),
            transactionOperationDate: $updatedTransaction->operationDate(),
            id: $updatedTransaction->id(),
            createdAt: $previousTransactionCreatedAt,
            updatedAt: $updatedTransaction->updatedAt(),
        );
    }

    public function createdAt(): DateVO
    {
        return $this->createdAt;
    }

    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @param Id $transactionId
     * @param bool $toAddToTransactionToDelete
     * @return void
     */
    public function removeTransaction(Id $transactionId, bool $toAddToTransactionToDelete = true): void
    {
        $this->updateAccountInformationsAfterDeleteTransaction($this->transactions[$transactionId->value()]);
        unset($this->transactions[$transactionId->value()]);
        if ($toAddToTransactionToDelete) {
            $this->deletedTransactionIds[] = $transactionId->value();
        }
    }

    /**
     * @param Transaction $transactionToDelete
     * @return void
     */
    private function updateAccountInformationsAfterDeleteTransaction(Transaction $transactionToDelete): void
    {
        $currentDate = new DateVO();
        $transactionAmount = $transactionToDelete->amount()->value();

        if ($transactionToDelete->transactionType() === TransactionTypeEnum::INCOME) {
            $newAccountBalanceValue = $this->balance->remove($transactionAmount);
            $this->changeBalance($newAccountBalanceValue);
            $newAccountTotalIncomesAmount = $this->totalIncomes()->remove($transactionAmount);
            $this->changeTotalIncomes($newAccountTotalIncomesAmount);
        }
        if ($transactionToDelete->transactionType() === TransactionTypeEnum::EXPENSE) {
            $newAccountBalanceValue = $this->balance->add($transactionAmount);
            $this->changeBalance($newAccountBalanceValue);
            $newAccountTotalExpensesAmount = $this->totalExpenses()->remove($transactionAmount);
            $this->changeTotalExpenses($newAccountTotalExpensesAmount);
        }

        $this->changeUpdatedAt($currentDate);
    }

    private function changeBalance(AmountVO $amount): void
    {
        $this->balance = $amount;
    }

    public function totalIncomes(): AmountVO
    {
        return $this->totalIncomes;
    }

    private function changeTotalIncomes(AmountVO $amount): void
    {
        $this->totalIncomes = $amount;
    }

    public function totalExpenses(): AmountVO
    {
        return $this->totalExpenses;
    }

    private function changeTotalExpenses(AmountVO $amount): void
    {
        $this->totalExpenses = $amount;
    }

    private function changeUpdatedAt(DateVO $currentDate): void
    {
        $this->updatedAt = $currentDate;
    }

    /**
     * @throws InvalidTransactionException
     */
    public function addTransaction(
        Id                  $transactionCategoryId,
        TransactionTypeEnum $transactionType,
        AmountVO            $transactionAmount,
        StringVO            $transactionDescription,
        DateVO              $transactionOperationDate,
        ?Id                 $id = null,
        ?DateVO             $createdAt = null,
        ?DateVO             $updatedAt = null,
    ): void
    {
        $newTransaction = Transaction::create(
            accountId: $this->id,
            transactionCategoryId: $transactionCategoryId,
            type: $transactionType,
            amount: $transactionAmount,
            description: $transactionDescription,
            operationDate: $transactionOperationDate,
            id: $id ?: new Id(),
            createdAt: $createdAt ?: new DateVO(),
            updatedAt: $updatedAt ?: new DateVO(),
        );
        $this->checkIfTransactionIsPermitted($newTransaction);
        $this->updateAccountInformationsAfterAddTransaction($newTransaction);
        $this->transactions[$newTransaction->id()->value()] = $newTransaction;
    }

    public static function create(
        Id        $userId,
        AmountVO  $balance,
        StringVO  $name,
        bool      $isIncludeInTotalBalance,
        ?Id       $id = new Id(),
        ?DateVO   $createdAt = new DateVO(),
        ?DateVO   $updatedAt = new DateVO(),
        ?AmountVO $totalIncomes = new AmountVO(0),
        ?AmountVO $totalExpenses = new AmountVO(0),
        ?DateVO   $lastTransactionDate = new DateVO(),
        ?StringVO $color = new StringVO('green'),
        ?StringVO $iconName = new StringVO('balance'),
    ): Account
    {
        $newAccount = new self(
            id: $id ?: new Id(),
            userId: $userId,
            balance: $balance,
            accountName: $name,
            isIncludeInTotalBalance: $isIncludeInTotalBalance,
            iconName: $iconName,
            color: $color,
            totalIncomes: $totalIncomes,
            totalExpenses: $totalExpenses,
            lastTransactionDate: $lastTransactionDate,
        );
        $newAccount->createdAt = $createdAt;
        $newAccount->updatedAt = $updatedAt;

        return $newAccount;
    }

    /**
     * @throws InvalidTransactionException
     */
    private function checkIfTransactionIsPermitted(Transaction $newTransaction): void
    {
        if ($newTransaction->transactionType() === TransactionTypeEnum::EXPENSE) {
            $this->checkIfTransactionAmountIsNotGreaterThanAccountBalanceOrThrowException($newTransaction->amount());
        }
    }

    /**
     * @throws InvalidTransactionException
     */
    private function checkIfTransactionAmountIsNotGreaterThanAccountBalanceOrThrowException(AmountVO $amount): void
    {
        if ($this->balance->isLowerThan($amount->value())) {
            throw new InvalidTransactionException("Le montant de la transaction doit être supérieur au solde du compte !");
        }
    }

    /**
     * @param Transaction $newTransaction
     * @return void
     */
    private function updateAccountInformationsAfterAddTransaction(Transaction $newTransaction): void
    {
        $currentDate = new DateVO();
        $transactionAmount = $newTransaction->amount()->value();

        if ($newTransaction->transactionType() === TransactionTypeEnum::INCOME) {
            $newAccountBalancevalue = $this->balance->add($transactionAmount);
            $this->changeBalance($newAccountBalancevalue);
            $newAccountTotalIncomesAmount = $this->totalIncomes->add($transactionAmount);
            $this->changeTotalIncomes($newAccountTotalIncomesAmount);
        }
        if ($newTransaction->transactionType() === TransactionTypeEnum::EXPENSE) {
            $newAccountBalancevalue = $this->balance->remove($transactionAmount);
            $this->changeBalance($newAccountBalancevalue);
            $newAccountTotalExpensesAmount = $this->totalExpenses->add($transactionAmount);
            $this->changeTotalExpenses($newAccountTotalExpensesAmount);
        }
        $this->changeLastTransactionDate($currentDate);
        $this->changeUpdatedAt($currentDate);
    }

    private function changeLastTransactionDate(DateVO $currentDate): void
    {
        $this->lastTransactionDate = $currentDate;
    }

    public function updatedAt(): DateVO
    {
        return $this->updatedAt;
    }

    public function deletedTransactions(): array
    {
        return $this->deletedTransactionIds;
    }

    /**
     * @param Transaction[] $transactions
     * @return void
     */
    public function changeTransactions(array $transactions): void
    {
        $newTransactions = [];
        foreach ($transactions as $transaction) {
            $newTransactions[$transaction->id()->value()] = $transaction;
        }
        $this->transactions = $newTransactions;
    }
}