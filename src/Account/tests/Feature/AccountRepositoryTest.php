<?php

namespace Code237\Nkap\Account\tests\Feature;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Exceptions\InvalidTransactionException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\Domain\Transaction;
use Code237\Nkap\Account\Infrastructure\Repositories\PdoAccountRepository;
use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use Exception;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $accountRepository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = new PdoAccountRepository(new MySQLPdoConnection());
    }
    /**
     * @throws Exception
     */
    public function test_can_save_account()
    {
        $accountToSave = Account::create(
            userId: new Id(),
            balance: new AmountVO(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true
        );

        $response = $this->accountRepository->create($accountToSave);

        $this->assertTrue($response);
    }

    /**
     * @throws Exception
     */
    public function test_can_get_account_by_id()
    {
        $savedAccount = $this->createAccountSUT();

        $getAccount = $this->accountRepository->getById($savedAccount->id());

        $this->assertEquals($savedAccount->id()->value(), $getAccount->id()->value());
        $this->assertEquals($savedAccount->balance()->value(), $getAccount->balance()->value());
        $this->assertEquals($savedAccount->name()->value(), $getAccount->name()->value());
    }

    /**
     * @throws Exception
     */
    public function test_can_udpate_account()
    {
        $savedAccount = $this->createAccountSUT();

        $savedAccount->changeName(new StringVO("compte epargne update"));

        $response = $this->accountRepository->update($savedAccount);

        $this->assertTrue($response);
    }

    /**
     * @throws Exception
     */
    public function test_can_delete_account()
    {
        $createdAccount = $this->createAccountSUT();

        $response = $this->accountRepository->delete($createdAccount->id());

        $this->assertTrue($response);
        $this->assertNull($this->accountRepository->getById($createdAccount->id()));
    }

    /**
     * @throws InvalidTransactionException
     * @throws Exception
     */
    public function test_can_get_account_with_his_transactions()
    {
        list($accountToCreate, $transaction) = $this->createAccountWithTransactionSUT();

        $this->accountRepository->create($accountToCreate);

        $createdAccount = $this->accountRepository->getById($accountToCreate->id());
        $createdTransaction = $createdAccount->getTransaction($transaction->id());

        $this->assertCount(1, $createdAccount->transactions());
        $this->assertEquals(TransactionTypeEnum::INCOME, $createdTransaction->transactionType());
        $this->assertEquals(2100, $createdTransaction->amount()->value());
        $this->assertEquals("achat gateau", $createdTransaction->description()->value());
    }

    /**
     * @throws InvalidTransactionException
     * @throws Exception
     */
    public function test_can_update_account_with_his_transactions()
    {
        list ($account, $transaction) = $this->createAccountWithTransactionSUT();

        $this->accountRepository->create($account);

        $createdAccount = $this->accountRepository->getById($account->id());

        $transaction->changeAmount(1000);
        $createdAccount->updateTransaction($transaction);

        $this->accountRepository->update($createdAccount);

        $updatedAccount = $this->accountRepository->getById($account->id());
        $updatedTransaction = $updatedAccount->getTransaction($transaction->id());

        $this->assertCount(1, $updatedAccount->transactions());
        $this->assertEquals(3000, $updatedAccount->balance()->value());
        $this->assertEquals(1000, $updatedTransaction->amount()->value());
    }

    /**
     * @throws InvalidTransactionException
     * @throws Exception
     */
    public function test_can_delete_account_transaction()
    {
        list($accountToCreate, $transaction) = $this->createAccountWithTransactionSUT();

        $this->accountRepository->create($accountToCreate);

        $createdAccount = $this->accountRepository->getById($accountToCreate->id());
        $createdAccount->removeTransaction($transaction->id());

        $this->accountRepository->update($createdAccount);

        $updatedAccount = $this->accountRepository->getById($createdAccount->id());

        $this->assertCount(0, $updatedAccount->transactions());
        $this->assertEquals(2000, $updatedAccount->balance()->value());
    }

    /**
     * @throws Exception
     */
    private function createAccountSUT(): Account
    {
        $savedAccount = Account::create(
            userId: new Id(),
            balance: new AmountVO(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true
        );

        $this->accountRepository->create($savedAccount);

        return $savedAccount;
    }

    /**
     * @throws InvalidTransactionException
     */
    public function createAccountWithTransactionSUT(): array
    {
        $account = Account::create(
            userId: new Id(),
            balance: new AmountVO(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true,
        );
        $transaction = Transaction::create(
            accountId: $account->id(),
            transactionCategoryId: new Id(),
            type: TransactionTypeEnum::INCOME,
            amount: new AmountVO(2100),
            description: new StringVO("achat gateau"),
            operationDate: new DateVO(),
            id: new Id()
        );
        $account->addTransaction(
            transactionCategoryId: $transaction->transactionCategory(),
            transactionType: $transaction->type(),
            transactionAmount: $transaction->amount(),
            transactionDescription: $transaction->description(),
            transactionOperationDate: $transaction->operationDate(),
            id: $transaction->id(),
        );

        return [$account, $transaction];
    }
}