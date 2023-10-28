<?php

namespace Code237\Nkap\Account\tests\Unit;

use Code237\Nkap\Account\Application\Command\save\SaveAccountCommand;
use Code237\Nkap\Account\Application\Command\save\SaveAccountHandler;
use Code237\Nkap\Account\Application\Command\save\SaveAccountResponse;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Account\tests\Unit\CommandBuilder\SaveAccountCommandBuilder;
use Code237\Nkap\Account\tests\Unit\Repository\InMemoryAccountRepository;
use Code237\Nkap\Shared\VO\Id;
use Exception;
use PHPUnit\Framework\TestCase;

class SaveAccountTest extends TestCase
{
    private AccountRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryAccountRepository();
    }

    /**
     * @throws Exception
     */
    public function test_can_save_account(): void
    {
        $saveAccountCommand = SaveAccountCommandBuilder::asBuilder()
                    ->withUserId((new Id())->value())
                    ->withBalance(2000)
                    ->withAccountName('compte étudiant')
                    ->withIsIncludeInTotalBalance(true)
                    ->build();

        $response = $this->saveUser($saveAccountCommand);

        $this->assertTrue($response->isSaved);
    }

    private function saveUser(SaveAccountCommand $saveAccountCommand): SaveAccountResponse
    {
        $handler = new SaveAccountHandler(
            $this->repository
        );
        return $handler->handle($saveAccountCommand);
    }
}