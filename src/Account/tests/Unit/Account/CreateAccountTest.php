<?php

namespace Code237\Nkap\Account\tests\Unit\Account;

use Code237\Nkap\Account\Application\Command\Account\Create\CreateAccountCommand;
use Code237\Nkap\Account\Application\Command\Account\Create\CreateAccountHandler;
use Code237\Nkap\Account\Application\Command\Account\Create\CreateAccountResponse;
use Code237\Nkap\Account\Domain\Exceptions\UserLinkedToAccountDoesNotExistException;
use Code237\Nkap\Account\Domain\Repositories\AccountRepository;
use Code237\Nkap\Account\Domain\Services\CheckIfUserLinkedToAccountExistOrThrowException;
use Code237\Nkap\Account\tests\Unit\CommandBuilder\Account\CreateAccountCommandBuilder;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use Code237\Nkap\Account\tests\Unit\Services\InMemoryCheckIfUserLinkedToAccountExistOrThrowException;
use Code237\Nkap\Shared\VO\EmailVO;
use Code237\Nkap\Shared\VO\PasswordVO;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\User\Domain\User;
use Exception;
use PHPUnit\Framework\TestCase;

class CreateAccountTest extends TestCase
{
    private AccountRepository $repository;
    private CheckIfUserLinkedToAccountExistOrThrowException $checkIfUserLinkedToAccountExistOrThrowException;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryAccountRepository();
        $this->checkIfUserLinkedToAccountExistOrThrowException = new InMemoryCheckIfUserLinkedToAccountExistOrThrowException();
    }

    /**
     * @throws Exception
     */
    public function test_can_create_account(): void
    {
        $user = $this->createNewUserForAccount();
        $createAccountCommand = CreateAccountCommandBuilder::asBuilder()
                    ->withUserId($user->id()->value())
                    ->withBalance(2000)
                    ->withAccountName('compte étudiant')
                    ->withIsIncludeInTotalBalance(true)
                    ->build();

        $response = $this->saveUser($createAccountCommand);

        $this->assertTrue($response->isSaved);
    }

    public function test_cannot_create_account_if_user_not_exist(): void
    {
        $user = $this->createNewUserForAccount();
        $createAccountCommand = CreateAccountCommandBuilder::asBuilder()
            ->withUserId($user->id()->value().'bad_password')
            ->withBalance(2000)
            ->withAccountName('compte étudiant')
            ->withIsIncludeInTotalBalance(true)
            ->build();

        $this->expectException(UserLinkedToAccountDoesNotExistException::class);

        $this->saveUser($createAccountCommand);
    }

    /**
     * @throws UserLinkedToAccountDoesNotExistException
     */
    private function saveUser(CreateAccountCommand $saveAccountCommand): CreateAccountResponse
    {
        $handler = new CreateAccountHandler(
            $this->repository,
            $this->checkIfUserLinkedToAccountExistOrThrowException
        );
        return $handler->handle($saveAccountCommand);
    }

    private function createNewUserForAccount(): User
    {
        $user = User::create(
            name: new StringVO("ALKEBULAN"),
            surname: new StringVO("LEKENE"),
            email: new EmailVO("alkebulan@gmail.com"),
            password: PasswordVO::fromPassword('alkebulan@1234')
        );

        $this->checkIfUserLinkedToAccountExistOrThrowException->users[$user->id()->value()] = $user;

        return $user;
    }
}