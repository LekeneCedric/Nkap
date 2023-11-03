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

    public function createdAt(): DateVO
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateVO
    {
        return $this->updatedAt;
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
     * @throws InvalidTransactionException
     */
    public function addTransaction(
        Id                  $transactionCategoryId,
        TransactionTypeEnum $transactionType,
        AmountVO            $transactionAmount,
        StringVO            $transactionDescription,
        DateVO              $transactionOperationDate,
        ?Id                 $id = null,
    ): void
    {
        $newTransaction = Transaction::create(
            accountId: $this->id,
            transactionCategoryId: $transactionCategoryId,
            type: $transactionType,
            amount: $transactionAmount,
            description: $transactionDescription,
            operationDate: $transactionOperationDate,
            id: $id ?: new Id()
        );
        $this->checkIfTransactionIsPermitted($newTransaction);
        $this->updateAccountInformationsAfterAddTransaction($newTransaction);
        $this->transactions[$newTransaction->id()->value()] = $newTransaction;
    }

    /**
     * @param Id $transactionId
     * @return void
     */
    public function removeTransaction(Id $transactionId): void
    {
        $this->updateAccountInformationsAfterDeleteTransaction($this->transactions[$transactionId->value()]);
        unset($this->transactions[$transactionId->value()]);
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

    private function changeTotalIncomes(AmountVO $amount): void
    {
        $this->totalIncomes = $amount;
    }

    private function changeTotalExpenses(AmountVO $amount): void
    {
        $this->totalExpenses = $amount;
    }

    private function changeLastTransactionDate(DateVO $currentDate): void
    {
        $this->lastTransactionDate = $currentDate;
    }

    private function changeUpdatedAt(DateVO $currentDate): void
    {
        $this->updatedAt = $currentDate;
    }



    public function id(): Id
    {
        return $this->id;
    }

    public function totalIncomes(): AmountVO
    {
        return $this->totalIncomes;
    }

    public function totalExpenses(): AmountVO
    {
        return $this->totalExpenses;
    }

    public function changeTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }
}