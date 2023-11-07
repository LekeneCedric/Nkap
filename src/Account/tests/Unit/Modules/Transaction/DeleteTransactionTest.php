<?php

namespace Code237\Nkap\Account\tests\Unit\Modules\Transaction;

use Code237\Nkap\Account\Application\Command\Transaction\Delete\DeleteTransactionHandler;
use Code237\Nkap\Account\Application\Command\Transaction\Delete\DeleteTransactionResponse;
use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\tests\Unit\Modules\Account\AccountSUT;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use PHPUnit\Framework\TestCase;

class DeleteTransactionTest extends TestCase
{
    private AccountRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryAccountRepository();
    }

    /**
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    public function test_can_delete_expense_transaction_in_account()
    {
        $account = (AccountSUT::asSUT()->withBalance(20000)->build())->account;
        $transactionAmount = 2000;
        $transactionId = new Id();
        $account->addTransaction(
            transactionCategoryId: new Id(),
            transactionType: TransactionTypeEnum::EXPENSE,
            transactionAmount: new AmountVO($transactionAmount),
            transactionDescription: new StringVO("acheter des oranges"),
            transactionOperationDate: new DateVO("2022-09-30 10:00:00"),
            id: $transactionId,
        );
        $createdAccountId = $account->id()->value();

        $accountBalanceBeforeDeleteTransaction = $account->balance()->value();
        $accountTotalExpensesBeforeDeleteTransaction = $account->totalExpenses()->value();

        $this->repository->accounts[$createdAccountId] = $account;

        $transactionToDelete =  $account->transactions()[$transactionId->value()];

        $response = $this->deleteTransaction($account->id()->value(), $transactionToDelete->id()->value());

        $estimateNewAccountBalance = $accountBalanceBeforeDeleteTransaction + $transactionToDelete->amount()->value();
        $estimateNewAccountTotalExpenses = $accountTotalExpensesBeforeDeleteTransaction - $transactionAmount;

        $this->assertEquals($this->repository->accounts[$createdAccountId]->balance()->value(), $estimateNewAccountBalance);
        $this->assertEquals($this->repository->accounts[$createdAccountId]->totalExpenses()->value(), $estimateNewAccountTotalExpenses);
        $this->assertTrue($response->isDeleted);
    }

    /**
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    public function test_can_delete_income_transaction_in_account()
    {
        $account = (AccountSUT::asSUT()->withBalance(20000)->build())->account;
        $transactionAmount = 12598.254;
        $transactionId = new Id();
        $account->addTransaction(
            transactionCategoryId: new Id(),
            transactionType: TransactionTypeEnum::INCOME,
            transactionAmount: new AmountVO($transactionAmount),
            transactionDescription: new StringVO("acheter des oranges"),
            transactionOperationDate: new DateVO("2022-09-30 10:00:00"),
            id: $transactionId
        );
        $createdAccountId = $account->id()->value();

        $accountBalanceBeforeDeleteTransaction = $account->balance()->value();
        $accountTotalIncomesBeforeDeleteTransaction = $account->totalIncomes()->value();

        $this->repository->accounts[$createdAccountId] = $account;

        $transactionToDelete =  $account->transactions()[$transactionId->value()];

        $response = $this->deleteTransaction($account->id()->value(), $transactionToDelete->id()->value());

        $estimateNewAccountBalance = $accountBalanceBeforeDeleteTransaction - $transactionToDelete->amount()->value();
        $estimateNewAccountTotalIncomes = $accountTotalIncomesBeforeDeleteTransaction - $transactionAmount;

        $this->assertEquals($this->repository->accounts[$createdAccountId]->balance()->value(), $estimateNewAccountBalance);
        $this->assertEquals($this->repository->accounts[$createdAccountId]->totalIncomes()->value(), $estimateNewAccountTotalIncomes);
        $this->assertTrue($response->isDeleted);
    }

    /**
     * @throws NotFoundAccountException
     */
    private function deleteTransaction(string $accountId, string $transactionId): DeleteTransactionResponse
    {
        $handler = new DeleteTransactionHandler(
            $this->repository,
        );

        return $handler->handle($accountId, $transactionId);
    }
}