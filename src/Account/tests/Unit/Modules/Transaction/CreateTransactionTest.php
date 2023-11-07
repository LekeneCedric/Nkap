<?php

namespace Code237\Nkap\Account\tests\Unit\Modules\Transaction;

use Code237\Nkap\Account\Application\Command\Transaction\Create\CreateTransactionCommand;
use Code237\Nkap\Account\Application\Command\Transaction\Create\CreateTransactionHandler;
use Code237\Nkap\Account\Application\Command\Transaction\Create\CreateTransactionResponse;
use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\tests\Unit\CommandBuilder\Transaction\CreateTransactionCommandBuilder;
use Code237\Nkap\Account\tests\Unit\Modules\Account\AccountSUT;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\Id;
use PHPUnit\Framework\TestCase;

class CreateTransactionTest extends TestCase
{
    private AccountRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryAccountRepository();
    }

    /**
     * @return void
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    public function test_can_add_expense_transaction_to_account()
    {
        $accountSUT = AccountSUT::asSUT()
            ->withBalance(2000)
            ->build();
        $account = $accountSUT->account;
        $createdAccountId = $account->id()->value();

        $accountBalanceBeforeTransaction = $account->balance()->value();
        $accountTotalExpensesBeforeTransaction = $account->totalExpenses()->value();

        $this->repository->accounts[$createdAccountId] = $account;

        $transactionAmount = 2000;
        $createTransactionCommand = CreateTransactionCommandBuilder::asBuilder()
            ->withAccountId($createdAccountId)
            ->withTransactionCategoryId((new Id())->value())
            ->withTransactionType(TransactionTypeEnum::EXPENSE->value)
            ->withTransactionAmount($transactionAmount)
            ->withDescription("Achat des habits pour la maternité")
            ->withOperationDate('2002-09-30 12:12:00')
            ->build();

        $response = $this->createTransaction($createTransactionCommand);

        $estimateNewAccountBalance = $accountBalanceBeforeTransaction - $transactionAmount;
        $estimateNewAccountExpense = $accountTotalExpensesBeforeTransaction + $transactionAmount;

        $this->assertTrue($response->isSaved);
        $this->assertEquals($this->repository->accounts[$createdAccountId]->balance()->value() ,$estimateNewAccountBalance);
        $this->assertEquals($this->repository->accounts[$createdAccountId]->totalExpenses()->value(), $estimateNewAccountExpense);
    }

    /**
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    public function test_can_add_income_transacion()
    {
        $accountSUT = AccountSUT::asSUT()
            ->withBalance(0)
            ->build();
        $account = $accountSUT->account;
        $createdAccountId = $account->id()->value();

        $accountBalanceBeforeTransaction = $account->balance()->value();
        $accountTotalIncomesBeforeTransaction = $account->totalIncomes()->value();

        $this->repository->accounts[$createdAccountId] = $account;

        $transactionAmount = 16000;
        $createTransactionCommand = CreateTransactionCommandBuilder::asBuilder()
            ->withAccountId($createdAccountId)
            ->withTransactionCategoryId((new Id())->value())
            ->withTransactionType(TransactionTypeEnum::INCOME->value)
            ->withTransactionAmount($transactionAmount)
            ->withDescription("Achat des habits pour la maternité")
            ->withOperationDate('2002-09-30 12:12:00')
            ->build();

        $response = $this->createTransaction($createTransactionCommand);

        $estimateNewAccountBalance = $accountBalanceBeforeTransaction + $transactionAmount;
        $estimateNewAccountIncomes = $accountTotalIncomesBeforeTransaction + $transactionAmount;

        $this->assertTrue($response->isSaved);
        $this->assertEquals($this->repository->accounts[$createdAccountId]->balance()->value() ,$estimateNewAccountBalance);
        $this->assertEquals($this->repository->accounts[$createdAccountId]->totalIncomes()->value(), $estimateNewAccountIncomes);
    }

    /**
     * @return void
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    public function test_can_throw_exception_if_transaction_is_an_expense_and_his_amount_is_greater_than_account_balance()
    {
        $initialAccountBalanceAmount = 50000;
        $firstTransactionAmount = 80000;

        $account = (AccountSUT::asSUT()->withBalance($initialAccountBalanceAmount)->build())->account;

        $this->repository->accounts[$account->id()->value()] = $account;

        $createTransactionCommand = CreateTransactionCommandBuilder::asBuilder()
            ->withAccountId($account->id()->value())
            ->withTransactionCategoryId((new Id())->value())
            ->withTransactionType(TransactionTypeEnum::EXPENSE->value)
            ->withTransactionAmount($firstTransactionAmount)
            ->withDescription("Achat des habits pour la maternité")
            ->withOperationDate('2002-09-30 12:12:00')
            ->build();

        $this->expectException(InvalidTransactionException::class);
        $this->createTransaction($createTransactionCommand);
        $this->assertCount(0, $account->transactions());
    }

    /**
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     */
    private function createTransaction(CreateTransactionCommand $command): CreateTransactionResponse
    {
        $handler = new CreateTransactionHandler(
            $this->repository,
        );
        return $handler->handle($command);
    }
}