<?php

namespace Code237\Nkap\Account\tests\Feature;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Account\Domain\AccountRepository;
use Code237\Nkap\Account\Infrastructure\Repositories\PdoAccountRepository;
use Code237\Nkap\Shared\Infrastructure\Lib\MySQLPdoConnection;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PdoAccountRepository(new MySQLPdoConnection());
    }

    public function test_can_save_account()
    {
        $accountToSave = Account::create(
            userId: new Id(),
            balance: new AmountVo(2000),
            name: new StringVO("compte epargne"),
            isIncludeInTotalBalance: true
        );

        $response = $this->repository->save($accountToSave);

        $this->assertTrue($response);
    }
}