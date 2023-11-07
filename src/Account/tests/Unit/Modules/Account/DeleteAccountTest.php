<?php

namespace Code237\Nkap\Account\tests\Unit\Modules\Account;

use Code237\Nkap\Account\Application\Command\Account\Delete\DeleteAccountHandler;
use Code237\Nkap\Account\Application\Command\Account\Delete\DeleteAccountResponse;
use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use PHPUnit\Framework\TestCase;

class DeleteAccountTest extends TestCase
{
    private AccountRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryAccountRepository();
    }

    public function test_can_delete_account()
    {
        $accountSUT = AccountSUT::asSUT()->build();
        $account  = $accountSUT->account;

        $this->repository->create($account);

        $response = $this->deleteAccount($account);

        $this->assertTrue($response->isDeleted);

        $this->assertNull($this->repository->getById($account->id()));
    }

    private function deleteAccount(Account $account): DeleteAccountResponse
    {
        $handler = new DeleteAccountHandler(
            $this->repository,
        );

        return $handler->handle($account->id()->value());
    }
}