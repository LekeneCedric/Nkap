<?php

namespace Code237\Nkap\Account\tests\Unit\Modules\Transaction;

use Code237\Nkap\Account\Application\Command\Transaction\Update\UpdateTransactionCommand;
use Code237\Nkap\Account\Application\Command\Transaction\Update\UpdateTransactionHandler;
use Code237\Nkap\Account\Application\Command\Transaction\Update\UpdateTransactionResponse;
use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Account\Domain\Exceptions\NotFoundAccountException;
use Code237\Nkap\Account\Domain\Exceptions\NotFoundTransactionException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\tests\Unit\CommandBuilder\Transaction\UpdateTransactionCommandBuilder;
use Code237\Nkap\Account\tests\Unit\Modules\Account\AccountSUT;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use PHPUnit\Framework\TestCase;

class UpdateTransactionTest extends TestCase
{
    const INITIAL_ACCOUNT_BALANCE = 20000;
    const FIRST_TRANSACTION_AMOUNT = 2000;
    const UPDATE_TRANSACTION_AMOUNT = 5000;
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
     * @throws NotFoundTransactionException
     */
    public function test_can_update_transaction()
    {
        $createdAccount = (AccountSUT::asSUT()->withBalance(self::INITIAL_ACCOUNT_BALANCE)->build())->account;
        $transactionId = new Id();
        $createdAccount->addTransaction(
            transactionCategoryId: new Id(),
            transactionType: TransactionTypeEnum::EXPENSE,
            transactionAmount: new AmountVO(self::FIRST_TRANSACTION_AMOUNT),
            transactionDescription: new StringVO("acheter des oranges"),
            transactionOperationDate: new DateVO("2022-09-30 10:00:00"),
            id: $transactionId,
        );
        $createdAccountId = $createdAccount->id()->value();

        $this->repository->create($createdAccount);

        $updateTransactionCommand = UpdateTransactionCommandBuilder::asBuilder()
            ->withId($transactionId->value())
            ->withAccountId($createdAccountId)
            ->withTransactionCategoryId((new Id())->value())
            ->withTransactionType(TransactionTypeEnum::EXPENSE->value)
            ->withTransactionAmount(self::UPDATE_TRANSACTION_AMOUNT)
            ->withDescription("acheter des oranges et des tomates")
            ->withOperationDate("2023-09-30 10:00:00")
            ->withCreatedAt("2023-10-31 15:00:00")
            ->withUpdatedAt("2023-10-31 15:00:00")
            ->build();
        $response = $this->updateTransaction($updateTransactionCommand);

        $accountAfterTransactionUpdate = $this->repository->accounts[$createdAccountId];
        $assertUpdatedAccountBalance = self::INITIAL_ACCOUNT_BALANCE - self::UPDATE_TRANSACTION_AMOUNT;

        $this->assertTrue($response->isUpdated);
        $this->assertEquals($accountAfterTransactionUpdate->balance()->value(), $assertUpdatedAccountBalance);
        $this->assertEquals(self::UPDATE_TRANSACTION_AMOUNT, $accountAfterTransactionUpdate->totalExpenses()->value());
        $this->assertEquals($accountAfterTransactionUpdate->totalIncomes()->value(), $createdAccount->totalIncomes()->value());
    }

    /**
     * @throws InvalidTransactionException
     * @throws NotFoundTransactionException
     * @throws NotFoundAccountException
     */
    public function test_can_throw_not_found_transaction_if_transaction_is_not_already_found_in_account()
    {
        $createdAccount = (AccountSUT::asSUT()->withBalance(self::INITIAL_ACCOUNT_BALANCE)->build())->account;
        $transactionId = new Id();
        $createdAccount->addTransaction(
            transactionCategoryId: new Id(),
            transactionType: TransactionTypeEnum::EXPENSE,
            transactionAmount: new AmountVO(self::FIRST_TRANSACTION_AMOUNT),
            transactionDescription: new StringVO("acheter des oranges"),
            transactionOperationDate: new DateVO("2022-09-30 10:00:00"),
            id: $transactionId,
        );
        $createdAccountId = $createdAccount->id()->value();

        $this->repository->create($createdAccount);

        $updateTransactionCommand = UpdateTransactionCommandBuilder::asBuilder()
            ->withId('erroned_transaction_id')
            ->withAccountId($createdAccountId)
            ->withTransactionCategoryId((new Id())->value())
            ->withTransactionType(TransactionTypeEnum::EXPENSE->value)
            ->withTransactionAmount(self::UPDATE_TRANSACTION_AMOUNT)
            ->withDescription("acheter des oranges et des tomates")
            ->withOperationDate("2023-09-30 10:00:00")
            ->withCreatedAt("2023-10-31 15:00:00")
            ->withUpdatedAt("2023-10-31 15:00:00")
            ->build();

        $this->expectException(NotFoundTransactionException::class);

        $this->updateTransaction($updateTransactionCommand);
    }

    /**
     * @param UpdateTransactionCommand $updateTransactionCommand
     * @return UpdateTransactionResponse
     * @throws InvalidTransactionException
     * @throws NotFoundAccountException
     * @throws NotFoundTransactionException
     */
    private function updateTransaction(UpdateTransactionCommand $updateTransactionCommand): UpdateTransactionResponse
    {
        $handler = new UpdateTransactionHandler(
            $this->repository,
        );
        return $handler->handle($updateTransactionCommand);
    }
}