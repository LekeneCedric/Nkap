<?php

namespace Code237\Nkap\Account\tests\Unit;

use Code237\Nkap\Account\Application\Command\update\UpdateAccountCommand;
use Code237\Nkap\Account\Application\Command\update\UpdateAccountHandler;
use Code237\Nkap\Account\Application\Command\update\UpdateAccountResponse;
use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Account\Domain\Services\CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService;
use Code237\Nkap\Account\tests\Unit\CommandBuilder\UpdateAccountCommandBuilder;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use Code237\Nkap\Account\tests\Unit\Services\InMemoryCheckIfAlreadyExistAccountByIdOrThrowAccountExceptionService;
use Code237\Nkap\Shared\VO\StringVO;
use PHPUnit\Framework\TestCase;

class UpdateAccountTest extends TestCase
{
    private AccountRepository $repository;
    private CheckIfAlreadyExistAccountByIdOrThrowNotFoundAccountExceptionService $checkIfAlreadyExistAccountByIdOrThrowAccountExceptionService;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository  = new InMemoryAccountRepository();
        $this->checkIfAlreadyExistAccountByIdOrThrowAccountExceptionService = new InMemoryCheckIfAlreadyExistAccountByIdOrThrowAccountExceptionService();
    }

    /**
     * @throws \Exception
     */
    public function test_can_update_account()
    {
        $accountSUT = AccountSUT::asSUT()->build();
        $account  = $accountSUT->account;

        $this->saveInMemory($account);

        $account->changeName(new StringVO("compte economie"));

        $command = UpdateAccountCommandBuilder::asBuilder()
            ->withId($account->id()->value())
            ->withUserId($account->userId()->value())
            ->withAccountName($account->name()->value())
            ->withBalance($account->balance()->value())
            ->withIsIncludeInTotalBalance($account->isIncludeInTotalBalance())
            ->withTotalIncomes($account->totalIncomes()->value())
            ->withTotalExpenses($account->totalExpenses()->value())
            ->withLastTransactionDate($account->lastTransactionDate()->formatYMDHIS())
            ->withIconName($account->iconName()->value())
            ->withColor($account->color()->value())
            ->withCreatedAt($account->createdAt()->formatYMDHIS())
            ->withUpdatedAt($account->updatedAt()->formatYMDHIS())
            ->build();

        $response = $this->updateAccount($command);

        $this->assertTrue($response->isUpdate);
    }

    private function saveInMemory(Account $account): void
    {
        $this->repository->create($account);
        $this->checkIfAlreadyExistAccountByIdOrThrowAccountExceptionService->accounts[$account->id()->value()] = $account;
    }

    private function updateAccount(UpdateAccountCommand $command): UpdateAccountResponse
    {
        $handler = new UpdateAccountHandler(
            $this->repository,
            $this->checkIfAlreadyExistAccountByIdOrThrowAccountExceptionService
        );

        return $handler->handle($command);
    }
}